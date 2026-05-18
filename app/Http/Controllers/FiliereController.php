<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Filiere;

class FiliereController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Filiere::class;
    protected string $resourceName = 'filiere';
    protected array $searchable = ['nom', 'code_filiere', 'niveau'];
    protected array $with = ['institution'];
    protected array $validationRules = [
        'institution_id' => 'required|integer|exists:institutions,id',
        'code_filiere' => 'nullable|string|max:100',
        'nom' => 'required|string|max:255',
        'niveau' => 'required|string|max:50',
        'duree_semestres' => 'required|integer|min:1|max:16',
        'numero_arrete_autorisation' => 'nullable|string|max:150',
        'date_arrete_autorisation' => 'nullable|date',
        'capacite_accueil' => 'required|integer|min:0',
        'statut' => 'required|in:active,inactive',
    ];
}
