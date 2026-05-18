<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Institution;

class InstitutionController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Institution::class;
    protected string $resourceName = 'institution';
    protected array $searchable = ['nom', 'code_etablissement', 'ville', 'email'];
    protected array $with = ['accreditationActive'];
    protected array $validationRules = [
        'nom' => 'required|string|max:255',
        'code_etablissement' => 'required|string|max:100',
        'adresse' => 'nullable|string',
        'ville' => 'required|string|max:100',
        'telephone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'statut' => 'required|in:actif,suspendu,ferme',
    ];

    public function export()
    {
        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nom', 'Code', 'Ville', 'Email', 'Statut']);
            Institution::orderBy('nom')->each(fn ($institution) => fputcsv($file, [
                $institution->nom,
                $institution->code_etablissement,
                $institution->ville,
                $institution->email,
                $institution->statut,
            ]));
            fclose($file);
        }, 'institutions.csv', ['Content-Type' => 'text/csv']);
    }
}
