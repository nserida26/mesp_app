<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Etudiant;

class EtudiantController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Etudiant::class;
    protected string $resourceName = 'etudiant';
    protected array $searchable = ['nom', 'prenom', 'email'];
    protected array $with = ['inscriptionActive.filiere.institution'];
    protected array $validationRules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'date_naissance' => 'nullable|date',
        'lieu_naissance' => 'nullable|string|max:255',
        'numero_national' => 'nullable|string|max:255',
        'hash_numero_bac' => 'required|string|max:255',
        'serie_bac' => 'nullable|string|max:100',
        'annee_bac' => 'nullable|integer|min:1980|max:2100',
        'mention_bac' => 'nullable|string|max:100',
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:50',
    ];

    public function export()
    {
        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nom', 'Prenom', 'Email', 'Telephone']);
            Etudiant::orderBy('nom')->each(fn ($etudiant) => fputcsv($file, [
                $etudiant->nom,
                $etudiant->prenom,
                $etudiant->email,
                $etudiant->telephone,
            ]));
            fclose($file);
        }, 'etudiants.csv', ['Content-Type' => 'text/csv']);
    }
}
