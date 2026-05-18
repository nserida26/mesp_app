<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Accreditation;

class AccreditationController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Accreditation::class;
    protected string $resourceName = 'accreditation';
    protected array $searchable = ['numero_arrete', 'type', 'statut'];
    protected array $with = ['institution'];
    protected array $validationRules = [
        'institution_id' => 'required|integer|exists:institutions,id',
        'numero_arrete' => 'nullable|string|max:150',
        'date_arrete' => 'required|date',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'type' => 'required|in:creation,renouvellement',
        'statut' => 'required|in:active,expiree,suspendue',
        'fichier_arrete_path' => 'nullable|string|max:255',
    ];
}
