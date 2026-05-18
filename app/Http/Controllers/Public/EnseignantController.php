<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Institution;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {
        $query = Enseignant::query()
            ->with(['affectationsActuelles.institution', 'affectationsActuelles.filiere'])
            ->actifs();

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('nom', 'like', "%{$term}%")
                    ->orWhere('prenom', 'like', "%{$term}%")
                    ->orWhere('specialite', 'like', "%{$term}%")
                    ->orWhere('numero_accreditation', 'like', "%{$term}%");
            });
        }

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        if ($request->filled('institution')) {
            $query->whereHas('affectationsActuelles.institution', function ($q) use ($request) {
                $q->where('uuid', $request->institution);
            });
        }

        $enseignants = $query->orderBy('nom')->paginate(20)->withQueryString();
        $institutions = Institution::actives()->orderBy('nom')->get();
        $grades = ['assistant', 'maitre_assistant', 'maitre_conference', 'professeur'];

        return view('public.enseignants.index', compact('enseignants', 'institutions', 'grades'));
    }
}
