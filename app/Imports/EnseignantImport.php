<?php

namespace App\Imports;

use App\Models\AffectationEnseignant;
use App\Models\Enseignant;
use App\Models\Filiere;
use App\Models\Institution;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class EnseignantImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private int $headerRowCount = 5;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nni  = trim((string) ($row['nni'] ?? ''));
            $nom  = trim((string) ($row['nom'] ?? ''));

            if ($nni === '' && $nom === '') {
                continue;
            }

            // ── 1. Find or create enseignant by NNI ───────────────────────
            $enseignant = $nni !== '' ? $this->findByNni($nni) : null;

            if (!$enseignant) {
                $enseignant = Enseignant::create([
                    'nom'                  => $nom,
                    'prenom'               => $row['prenom']              ?? '',
                    'numero_national'      => $nni ?: null,
                    'numero_accreditation' => $row['numero_accreditation'] ?? null,
                    'grade'               => $row['grade']               ?? 'assistant',
                    'specialite'          => $row['specialite']          ?? null,
                    'email'               => $row['email']               ?? null,
                    'telephone'           => $row['telephone']           ?? null,
                    'statut'              => $row['statut']              ?? 'actif',
                ]);
            } else {
                $enseignant->update([
                    'grade'      => $row['grade']      ?? $enseignant->grade,
                    'specialite' => $row['specialite'] ?? $enseignant->specialite,
                    'email'      => $row['email']      ?? $enseignant->email,
                    'statut'     => $row['statut']     ?? $enseignant->statut,
                ]);
            }

            // ── 2. Find institution + filière ─────────────────────────────
            $codeInst    = trim((string) ($row['code_etablissement'] ?? ''));
            $codeFiliere = trim((string) ($row['code_filiere'] ?? ''));

            $institution = $codeInst !== ''
                ? Institution::where('code_etablissement', $codeInst)->first()
                : null;

            $filiere = $codeFiliere !== ''
                ? Filiere::where('code_filiere', $codeFiliere)->first()
                : null;

            if (!$institution || !$filiere) {
                continue;
            }

            // ── 3. Upsert affectation (no duplicate on same key) ──────────
            // Unique DB constraint: (enseignant_id, institution_id, filiere_id, annee_universitaire)
            AffectationEnseignant::updateOrCreate(
                [
                    'enseignant_id'      => $enseignant->id,
                    'institution_id'     => $institution->id,
                    'filiere_id'         => $filiere->id,
                    'annee_universitaire'=> $row['annee_univ'] ?? date('Y'),
                ],
                [
                    'volume_horaire' => $row['volume_horaire'] ?? 0,
                    'type_contrat'   => $row['type_contrat']   ?? 'vacataire',
                ]
            );
        }
    }

    // Iterate all encrypted records to match NNI
    private function findByNni(string $nni): ?Enseignant
    {
        return Enseignant::whereNotNull('numero_national')
            ->get()
            ->first(fn ($e) => hash_equals($nni, trim((string) $e->numero_national)));
    }

    public function headingRow(): int
    {
        return $this->headerRowCount;
    }
}
