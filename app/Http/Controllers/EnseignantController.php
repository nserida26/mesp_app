<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Enseignant;

class EnseignantController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Enseignant::class;
    protected string $resourceName = 'enseignant';
    protected array $searchable = ['nom', 'prenom', 'numero_national', 'email', 'specialite'];
    protected array $validationRules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'numero_national' => 'nullable|string|max:255',
        'numero_accreditation' => 'nullable|string|max:150',
        'grade' => 'required|string|max:100',
        'specialite' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:50',
        'statut' => 'required|in:actif,inactif',
    ];
}
