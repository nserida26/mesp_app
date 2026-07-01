<?php

namespace App\Imports;

use App\Models\Accreditation;
use App\Models\Institution;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class InstitutionImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private int $headerRowCount = 5;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nom  = trim((string) ($row['nom'] ?? ''));
            $code = trim((string) ($row['code_etablissement'] ?? ''));

            if ($nom === '') {
                continue;
            }

            // ── 1. Upsert institution ─────────────────────────────────────
            $institution = Institution::updateOrCreate(
                ['code_etablissement' => $code ?: null, 'nom' => $nom],
                [
                    'adresse'   => $row['adresse']   ?? null,
                    'ville'     => $row['ville']      ?? null,
                    'telephone' => $row['telephone']  ?? null,
                    'email'     => $row['email']      ?? null,
                    'statut'    => $row['statut']     ?? 'actif',
                ]
            );

            // ── 2. Insert/update accreditation if provided ────────────────
            $numeroArrete = trim((string) ($row['accred_numero_arrete'] ?? ''));
            if ($numeroArrete !== '') {
                $accreditation = Accreditation::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'numero_arrete'  => $numeroArrete,
                    ],
                    [
                        'type'       => $row['accred_type']       ?? 'creation',
                        'date_arrete'=> $this->parseDate($row['accred_date_arrete'] ?? null) ?? now(),
                        'date_debut' => $this->parseDate($row['accred_date_debut']  ?? null) ?? now(),
                        'date_fin'   => $this->parseDate($row['accred_date_fin']    ?? null),
                        'statut'     => $row['accred_statut']     ?? 'active',
                    ]
                );

                // ── 3. Set accreditation_id on institution ─────────────────
                $institution->update(['accreditation_id' => $accreditation->id]);
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
