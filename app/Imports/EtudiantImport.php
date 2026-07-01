<?php

namespace App\Imports;

use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Inscription;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class EtudiantImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private int $headerRowCount = 5;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nni         = trim((string) ($row['nni'] ?? ''));
            $codeFiliere = trim((string) ($row['code_filiere'] ?? ''));

            if ($nni === '' || $codeFiliere === '') {
                continue;
            }

            // ── Find or create étudiant by NNI (encrypted) ──────────────
            $etudiant = $this->findByNni($nni);

            if (!$etudiant) {
                $etudiant = Etudiant::create([
                    'nom'             => $row['nom']             ?? '',
                    'prenom'          => $row['prenom']          ?? '',
                    'numero_national' => $nni,
                    'date_naissance'  => $this->parseDate($row['date_naissance'] ?? null),
                    'lieu_naissance'  => $row['lieu_naissance']  ?? null,
                    'serie_bac'       => $row['serie_bac']       ?? null,
                    'annee_bac'       => $row['annee_bac']       ?? null,
                    'mention_bac'     => $row['mention_bac']     ?? null,
                    'email'           => $row['email']           ?? null,
                    'telephone'       => $row['telephone']       ?? null,
                ]);
            } else {
                // Update mutable fields only
                $etudiant->update([
                    'nom'            => $row['nom']    ?? $etudiant->nom,
                    'prenom'         => $row['prenom'] ?? $etudiant->prenom,
                    'email'          => $row['email']  ?? $etudiant->email,
                    'telephone'      => $row['telephone'] ?? $etudiant->telephone,
                ]);
            }

            // ── Find filière by code ──────────────────────────────────────
            $filiere = Filiere::where('code_filiere', $codeFiliere)->first();
            if (!$filiere) {
                continue;
            }

            // ── Insert inscription (allow multiple per student) ────────────
            // Unique key: etudiant + filiere + annee + semestre
            Inscription::updateOrCreate(
                [
                    'etudiant_id'        => $etudiant->id,
                    'filiere_id'         => $filiere->id,
                    'annee_universitaire'=> $row['annee_universitaire'] ?? date('Y'),
                    'semestre_courant'   => $row['semestre'] ?? 1,
                ],
                [
                    'statut'             => $row['statut_inscription'] ?? 'actif',
                    'date_inscription'   => $this->parseDate($row['date_inscription'] ?? null) ?? now(),
                ]
            );
        }
    }

    // Iterate all encrypted records to match NNI
    private function findByNni(string $nni): ?Etudiant
    {
        if ($nni === '') {
            return null;
        }
        return Etudiant::whereNotNull('numero_national')
            ->get()
            ->first(fn ($e) => hash_equals($nni, trim((string) $e->numero_national)));
    }

    private function parseDate($value): ?string
    {
        if (!$value) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    // Skip the 5 decorative header rows (maatwebsite counts from 1 after WithHeadingRow)
    public function headingRow(): int
    {
        return $this->headerRowCount;
    }
}
