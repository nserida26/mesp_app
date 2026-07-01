<?php

namespace App\Imports;

use App\Models\Accreditation;
use App\Models\Filiere;
use App\Models\Institution;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class FiliereImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private int $headerRowCount = 5;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $codeFiliere = trim((string) ($row['code_filiere'] ?? ''));
            $nomFiliere  = trim((string) ($row['nom_filiere'] ?? ''));
            $codeInst    = trim((string) ($row['code_etablissement'] ?? ''));

            if ($nomFiliere === '') {
                continue;
            }

            // ── 1. Find institution ────────────────────────────────────────
            $institution = null;
            if ($codeInst !== '') {
                $institution = Institution::where('code_etablissement', $codeInst)->first();
            }
            if (!$institution) {
                $nomInst = trim((string) ($row['institution'] ?? ''));
                if ($nomInst) {
                    $institution = Institution::where('nom', 'like', "%{$nomInst}%")->first();
                }
            }
            if (!$institution) {
                continue;
            }

            // ── 2. Upsert accreditation for this filière ──────────────────
            $accreditationId = null;
            $numeroArrete = trim((string) ($row['accred_numero_arrete'] ?? ''));
            if ($numeroArrete !== '') {
                $accreditation = Accreditation::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'numero_arrete'  => $numeroArrete,
                    ],
                    [
                        'type'        => $row['accred_type']        ?? 'creation',
                        'date_arrete' => $this->parseDate($row['accred_date_debut'] ?? null) ?? now(),
                        'date_debut'  => $this->parseDate($row['accred_date_debut'] ?? null) ?? now(),
                        'date_fin'    => $this->parseDate($row['accred_date_fin']   ?? null),
                        'statut'      => $row['accred_statut']      ?? 'active',
                    ]
                );
                $accreditationId = $accreditation->id;
            }

            // ── 3. Upsert filière ──────────────────────────────────────────
            $filiere = Filiere::updateOrCreate(
                [
                    'code_filiere'   => $codeFiliere ?: ('FIL-' . uniqid()),
                    'institution_id' => $institution->id,
                ],
                [
                    'nom'                        => $nomFiliere,
                    'niveau'                     => $row['niveau']           ?? 'licence',
                    'duree_semestres'            => $row['duree_semestres']  ?? 6,
                    'capacite_accueil'           => $row['capacite']         ?? 30,
                    'numero_arrete_autorisation' => $row['numero_arrete_autorisation'] ?? $numeroArrete,
                    'date_arrete_autorisation'   => $this->parseDate($row['date_arrete'] ?? null),
                    'statut'                     => $row['statut']           ?? 'active',
                    'accreditation_id'           => $accreditationId,
                ]
            );

            // ── 4. Link accreditation to filière ──────────────────────────
            if ($accreditationId) {
                Accreditation::where('id', $accreditationId)
                    ->update(['filiere_id' => $filiere->id]);
            }
        }
    }

    public function headingRow(): int
    {
        return $this->headerRowCount;
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
}
