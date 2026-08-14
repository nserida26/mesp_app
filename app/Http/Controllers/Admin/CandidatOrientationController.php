<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampagneOrientation;
use App\Models\CandidatOrientation;
use Illuminate\Http\Request;

class CandidatOrientationController extends Controller
{
    public function index(Request $request)
    {
        $candidats = CandidatOrientation::query()
            ->with(['campagne', 'typeBac', 'domaineLicence'])
            ->when($request->filled('campagne'), fn ($q) => $q->where('campagne_orientation_id', $request->integer('campagne')))
            ->when($request->filled('statut'), fn ($q) => $q->where('statut', $request->statut))
            ->when($request->filled('q'), fn ($q) => $q->where(function ($builder) use ($request) {
                $builder->where('nom_complet', 'like', '%' . $request->q . '%')
                    ->orWhere('nni', 'like', '%' . $request->q . '%');
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $campagnes = CampagneOrientation::query()->orderByDesc('annee_universitaire')->get();

        return view('admin.orientation.candidats.index', compact('candidats', 'campagnes'));
    }

    public function show(CandidatOrientation $candidat)
    {
        $candidat->load(['campagne', 'typeBac', 'domaineLicence', 'choix.offre.filiere.institution', 'orientation.offre.filiere.institution']);

        return view('admin.orientation.candidats.show', compact('candidat'));
    }
}
