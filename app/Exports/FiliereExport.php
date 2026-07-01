<?php

namespace App\Exports;

use App\Models\Filiere;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FiliereExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    use ExcelHeaderTrait;

    protected string $sheetTitle = 'Filières';

    public function collection()
    {
        return Filiere::with(['institution', 'accreditation'])
            ->orderBy('nom')
            ->get()
            ->map(fn ($fil) => [
                // ── Filière ───────────────────────────────────────────────
                $fil->nom                                         ?? '',
                $fil->code_filiere                                ?? '',
                $fil->niveau                                      ?? '',
                $fil->duree_semestres                             ?? '',
                $fil->capacite_accueil                            ?? '',
                $fil->numero_arrete_autorisation                  ?? '',
                $fil->date_arrete_autorisation?->format('d/m/Y') ?? '',
                $fil->statut                                      ?? '',
                // ── Institution ───────────────────────────────────────────
                $fil->institution?->nom                           ?? '',
                $fil->institution?->code_etablissement            ?? '',
                $fil->institution?->ville                         ?? '',
                // ── Accréditation ─────────────────────────────────────────
                $fil->accreditation?->numero_arrete               ?? '',
                $fil->accreditation?->type                        ?? '',
                $fil->accreditation?->date_debut?->format('d/m/Y') ?? '',
                $fil->accreditation?->date_fin?->format('d/m/Y')   ?? '',
                $fil->accreditation?->statut                      ?? '',
            ]);
    }

    public function headings(): array
    {
        return [
            'Nom filière', 'Code filière', 'Niveau', 'Durée (sem.)', 'Capacité',
            'N° arrêté autorisation', 'Date arrêté', 'Statut',
            'Institution', 'Code établissement', 'Ville',
            'Accréd. N° arrêté', 'Accréd. Type',
            'Accréd. Date début', 'Accréd. Date fin', 'Accréd. Statut',
        ];
    }
}
