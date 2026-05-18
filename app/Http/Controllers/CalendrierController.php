<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\CalendrierAcademique;

class CalendrierController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = CalendrierAcademique::class;
    protected string $resourceName = 'calendrier';
    protected array $searchable = ['annee_universitaire', 'statut'];
    protected array $with = ['institution'];
    protected array $validationRules = [
        'institution_id' => 'required|integer|exists:institutions,id',
        'annee_universitaire' => 'required|integer|min:2000|max:2100',
        'debut_semestre_1' => 'required|date',
        'fin_semestre_1' => 'required|date|after:debut_semestre_1',
        'debut_examens_s1' => 'required|date',
        'fin_examens_s1' => 'required|date|after:debut_examens_s1',
        'debut_vacances_hiver' => 'nullable|date',
        'fin_vacances_hiver' => 'nullable|date',
        'debut_semestre_2' => 'required|date',
        'fin_semestre_2' => 'required|date|after:debut_semestre_2',
        'debut_examens_s2' => 'required|date',
        'fin_examens_s2' => 'required|date|after:debut_examens_s2',
        'debut_vacances_ete' => 'nullable|date',
        'fin_vacances_ete' => 'nullable|date',
        'statut' => 'required|in:brouillon,valide,publie',
    ];

    protected function routeName(): string
    {
        return 'calendrier';
    }
}
