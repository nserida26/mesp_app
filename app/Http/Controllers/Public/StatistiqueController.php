<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Institution;
use App\Models\Inscription;

class StatistiqueController extends Controller
{
    public function index()
    {
        return view('public.statistiques.index', [
            'stats' => [
                'institutions' => Institution::actives()->count(),
                'filieres' => Filiere::actives()->where('statut_validation', 'valide')->count(),
                'etudiants' => Etudiant::where('statut_validation', 'valide')->count(),
                'inscriptions' => Inscription::actives()->count(),
            ],
        ]);
    }
}
