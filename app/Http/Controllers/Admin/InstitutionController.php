<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Accreditation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::query()->with('accreditationActive');

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('ville')) {
            $query->byVille($request->ville);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                    ->orWhere('code_etablissement', 'like', "%{$request->search}%");
            });
        }

        $institutions = $query->orderBy('nom')->paginate(15);

        return view('admin.institutions.index', compact('institutions'));
    }

    public function create()
    {
        return view('admin.institutions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_etablissement' => 'required|string|unique:institutions',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:institutions',
            'logo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $validated['statut'] = 'actif';

        $institution = Institution::create($validated);

        return redirect()->route('admin.institutions.show', $institution->uuid)
            ->with('success', 'Institution créée avec succès');
    }

    public function show($uuid)
    {
        $institution = Institution::where('uuid', $uuid)
            ->with(['accreditations' => function ($q) {
                $q->orderBy('date_debut', 'desc');
            }, 'filieres', 'calendriers' => function ($q) {
                $q->orderBy('annee_universitaire', 'desc');
            }])
            ->firstOrFail();

        $stats = [
            'total_etudiants' => $institution->total_etudiants,
            'total_filieres' => $institution->total_filieres_actives,
            'total_enseignants' => $institution->enseignants()->count(),
        ];

        return view('admin.institutions.show', compact('institution', 'stats'));
    }

    public function edit($uuid)
    {
        $institution = Institution::where('uuid', $uuid)->firstOrFail();
        return view('admin.institutions.edit', compact('institution'));
    }

    public function update(Request $request, $uuid)
    {
        $institution = Institution::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:institutions,email,' . $institution->id,
            'statut' => 'required|in:actif,suspendu,ferme',
            'logo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            if ($institution->logo_path) {
                Storage::disk('public')->delete($institution->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $institution->update($validated);

        return redirect()->route('admin.institutions.show', $uuid)
            ->with('success', 'Institution mise à jour avec succès');
    }

    public function destroy($uuid)
    {
        $institution = Institution::where('uuid', $uuid)->firstOrFail();
        $institution->delete();

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Institution supprimée avec succès');
    }
}
