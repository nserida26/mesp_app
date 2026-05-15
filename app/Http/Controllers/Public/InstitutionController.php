<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::actives()->with('accreditationActive');

        // Filtres publics
        if ($request->filled('ville')) {
            $query->byVille($request->ville);
        }

        if ($request->filled('search')) {
            $query->where('nom', 'like', "%{$request->search}%");
        }

        $institutions = $query->orderBy('nom')->paginate(12);
        $villes = Institution::distinct()->pluck('ville')->sort();

        return view('public.institutions.index', compact('institutions', 'villes'));
    }

    public function show($uuid)
    {
        $institution = Institution::where('uuid', $uuid)
            ->actives()
            ->with(['accreditationActive', 'filieres' => function ($q) {
                $q->where('statut', 'active');
            }])
            ->firstOrFail();

        return view('public.institutions.show', compact('institution'));
    }
}
