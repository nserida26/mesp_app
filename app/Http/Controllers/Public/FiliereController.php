<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Institution;
use App\Models\Inscription;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index(Request $request)
    {
        $query = Filiere::query()
            ->with(['institution'])
            ->withCount(['inscriptionsActives'])
            ->where('statut', 'active')
            ->whereHas('institution', function ($q) {
                $q->where('statut', 'actif')
                    ->whereHas('accreditationActive');
            });

        // Filtres
        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }

        // CORRECTION : Utiliser uuid pour filtrer par institution
        if ($request->filled('institution')) {
            $query->whereHas('institution', function ($q) use ($request) {
                $q->where('uuid', $request->institution);
            });
        }

        if ($request->filled('ville')) {
            $query->whereHas('institution', function ($q) use ($request) {
                $q->where('ville', 'like', "%{$request->ville}%");
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                    ->orWhere('code_filiere', 'like', "%{$request->search}%");
            });
        }

        // Statistiques
        $stats = [
            'total_filieres' => Filiere::where('statut', 'active')->count(),
            'total_institutions' => Institution::actives()->count(),
            'capacite_totale' => Filiere::where('statut', 'active')->sum('capacite_accueil'),
            'etudiants_inscrits' => Inscription::where('statut', 'actif')->count(),
        ];

        $filieres = $query->orderBy('nom')->paginate(12);

        // Données pour les filtres
        $niveaux = ['licence', 'master', 'doctorat'];
        $institutions = Institution::actives()->orderBy('nom')->get();
        $villes = Institution::distinct()->orderBy('ville')->pluck('ville');

        return view('public.filieres.index', compact(
            'filieres',
            'stats',
            'niveaux',
            'institutions',
            'villes'
        ));
    }

    public function show($uuid)
    {
        // CORRECTION : Rechercher par uuid de la filière, pas de l'institution
        $filiere = Filiere::where('uuid', $uuid)
            ->with(['institution'])
            ->withCount(['inscriptionsActives'])
            ->where('statut', 'active')
            ->firstOrFail();

        // Récupérer les statistiques par semestre (anonymisées)
        $statsParSemestre = [];
        for ($i = 1; $i <= $filiere->duree_semestres; $i++) {
            $statsParSemestre[$i] = Inscription::where('filiere_id', $filiere->id)
                ->where('statut', 'actif')
                ->where('semestre_courant', $i)
                ->count();
        }

        // Filières similaires dans la même institution
        $filieresSimilaires = Filiere::where('institution_id', $filiere->institution_id)
            ->where('id', '!=', $filiere->id)
            ->where('statut', 'active')
            ->take(4)
            ->get();

        return view('public.filieres.show', compact(
            'filiere',
            'statsParSemestre',
            'filieresSimilaires'
        ));
    }
}
