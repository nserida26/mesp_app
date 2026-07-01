<?php

namespace App\Exports;

use App\Models\Inscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EtudiantExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    use ExcelHeaderTrait;

    protected string $sheetTitle = 'Étudiants & Inscriptions';

    public function collection()
    {
        return Inscription::with([
            'etudiant',
            'filiere.institution',
        ])
        ->orderBy('annee_universitaire', 'desc')
        ->get()
        ->map(fn ($ins) => [
            // ── Étudiant ──────────────────────────────────────────────────
            $ins->etudiant?->nom                ?? '',
            $ins->etudiant?->prenom             ?? '',
            $ins->etudiant?->numero_national    ?? '',   // décrypté via accessor
            $ins->etudiant?->date_naissance?->format('d/m/Y') ?? '',
            $ins->etudiant?->lieu_naissance     ?? '',
            $ins->etudiant?->serie_bac          ?? '',
            $ins->etudiant?->annee_bac          ?? '',
            $ins->etudiant?->mention_bac        ?? '',
            $ins->etudiant?->email              ?? '',
            $ins->etudiant?->telephone          ?? '',
            // ── Filière & Institution ──────────────────────────────────────
            $ins->filiere?->code_filiere        ?? '',
            $ins->filiere?->nom                 ?? '',
            $ins->filiere?->niveau              ?? '',
            $ins->filiere?->institution?->nom   ?? '',
            $ins->filiere?->institution?->ville ?? '',
            // ── Inscription ───────────────────────────────────────────────
            $ins->annee_universitaire           ?? '',
            $ins->semestre_courant              ?? '',
            $ins->statut                        ?? '',
            $ins->date_inscription?->format('d/m/Y') ?? '',
            $ins->numero_inscription            ?? '',
        ]);
    }

    public function headings(): array
    {
        return [
            // Étudiant
            'Nom', 'Prénom', 'NNI', 'Date naissance', 'Lieu naissance',
            'Série Bac', 'Année Bac', 'Mention Bac', 'Email', 'Téléphone',
            // Filière
            'Code filière', 'Filière', 'Niveau', 'Institution', 'Ville',
            // Inscription
            'Année universitaire', 'Semestre', 'Statut inscription',
            'Date inscription', 'N° inscription',
        ];
    }
}
