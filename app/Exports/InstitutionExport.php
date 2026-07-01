<?php

namespace App\Exports;

use App\Models\Institution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InstitutionExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    use ExcelHeaderTrait;

    protected string $sheetTitle = 'Institutions';

    public function collection()
    {
        return Institution::with(['accreditation', 'accreditationActive'])
            ->orderBy('nom')
            ->get()
            ->map(function ($inst) {
                $accred = $inst->accreditation ?? $inst->accreditationActive;
                return [
                    // ── Institution ───────────────────────────────────────
                    $inst->nom                              ?? '',
                    $inst->code_etablissement               ?? '',
                    $inst->adresse                          ?? '',
                    $inst->ville                            ?? '',
                    $inst->telephone                        ?? '',
                    $inst->email                            ?? '',
                    $inst->statut                           ?? '',
                    // ── Accréditation ─────────────────────────────────────
                    $accred?->numero_arrete                 ?? '',
                    $accred?->type                          ?? '',
                    $accred?->date_arrete?->format('d/m/Y') ?? '',
                    $accred?->date_debut?->format('d/m/Y')  ?? '',
                    $accred?->date_fin?->format('d/m/Y')    ?? '',
                    $accred?->statut                        ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nom', 'Code établissement', 'Adresse', 'Ville', 'Téléphone', 'Email', 'Statut',
            'Accréd. N° arrêté', 'Accréd. Type', 'Accréd. Date arrêté',
            'Accréd. Date début', 'Accréd. Date fin', 'Accréd. Statut',
        ];
    }
}
