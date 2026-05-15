<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Filiere;
use App\Models\Institution;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscription::query()
            ->with(['filiere.institution'])
            ->where('statut', 'actif');

        // Filtres
        if ($request->filled('niveau')) {
            $query->whereHas('filiere', function ($q) use ($request) {
                $q->where('niveau', $request->niveau);
            });
        }

        if ($request->filled('institution')) {
            $query->whereHas('filiere.institution', function ($q) use ($request) {
                $q->where('uuid', $request->institution);
            });
        }

        if ($request->filled('filiere')) {
            $query->whereHas('filiere', function ($q) use ($request) {
                $q->where('uuid', $request->filiere);
            });
        }

        if ($request->filled('annee')) {
            $query->where('annee_universitaire', $request->annee);
        }

        // Statistiques globales (anonymisées)
        $stats = [
            'total_actifs' => Inscription::where('statut', 'actif')->count(),
            'total_licence' => Inscription::where('statut', 'actif')
                ->whereHas('filiere', fn($q) => $q->where('niveau', 'licence'))
                ->count(),
            'total_master' => Inscription::where('statut', 'actif')
                ->whereHas('filiere', fn($q) => $q->where('niveau', 'master'))
                ->count(),
            'total_doctorat' => Inscription::where('statut', 'actif')
                ->whereHas('filiere', fn($q) => $q->where('niveau', 'doctorat'))
                ->count(),
        ];

        // Récupération paginée (données anonymisées)
        $inscriptions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Données pour les filtres
        $niveaux = ['licence', 'master', 'doctorat'];
        $institutions = Institution::actives()->orderBy('nom')->get();
        $annees = Inscription::distinct()
            ->orderBy('annee_universitaire', 'desc')
            ->pluck('annee_universitaire');

        return view('public.etudiants.index', compact(
            'inscriptions',
            'stats',
            'niveaux',
            'institutions',
            'annees'
        ));
    }
}
