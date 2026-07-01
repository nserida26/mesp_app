<?php

namespace App\Exports;

use App\Models\AffectationEnseignant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EnseignantExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    use ExcelHeaderTrait;

    protected string $sheetTitle = 'Enseignants & Affectations';

    public function collection()
    {
        return AffectationEnseignant::with([
            'enseignant',
            'institution',
            'filiere',
        ])
        ->orderBy('annee_universitaire', 'desc')
        ->get()
        ->map(fn ($aff) => [
            // ── Enseignant ────────────────────────────────────────────────
            $aff->enseignant?->nom                  ?? '',
            $aff->enseignant?->prenom               ?? '',
            $aff->enseignant?->numero_national      ?? '',   // décrypté via accessor
            $aff->enseignant?->numero_accreditation ?? '',
            $aff->enseignant?->grade                ?? '',
            $aff->enseignant?->specialite           ?? '',
            $aff->enseignant?->email                ?? '',
            $aff->enseignant?->telephone            ?? '',
            $aff->enseignant?->statut               ?? '',
            // ── Affectation ───────────────────────────────────────────────
            $aff->institution?->nom                 ?? '',
            $aff->institution?->code_etablissement  ?? '',
            $aff->institution?->ville               ?? '',
            $aff->filiere?->nom                     ?? '',
            $aff->filiere?->code_filiere            ?? '',
            $aff->annee_universitaire               ?? '',
            $aff->volume_horaire                    ?? '',
            $aff->type_contrat                      ?? '',
        ]);
    }

    public function headings(): array
    {
        return [
            // Enseignant
            'Nom', 'Prénom', 'NNI', 'N° accréditation', 'Grade',
            'Spécialité', 'Email', 'Téléphone', 'Statut',
            // Affectation
            'Institution', 'Code établissement', 'Ville',
            'Filière', 'Code filière',
            'Année univ.', 'Volume horaire', 'Type contrat',
        ];
    }
}
