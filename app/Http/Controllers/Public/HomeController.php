<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Filiere;
use App\Models\Inscription;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'institutions' => Institution::actives()->count(),
            'filieres' => Filiere::where('statut', 'active')->count(),
            'etudiants' => Inscription::actives()->count(),
        ];

        $institutions_recentes = Institution::actives()
            ->with('accreditationActive')
            ->latest()
            ->take(6)
            ->get();

        return view('public.home', compact('stats', 'institutions_recentes'));
    }
}
