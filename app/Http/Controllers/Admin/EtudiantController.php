<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\Filiere;
use Illuminate\Http\Request;
use App\Http\Requests\EtudiantRequest;

class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $query = Etudiant::query()->with('inscriptionActive.filiere');

        // Recherche sécurisée
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtre par institution (pour le rôle institution)
        if (auth()->user()->role === 'institution') {
            $query->whereHas('inscriptionActive.filiere', function ($q) {
                $q->where('institution_id', auth()->user()->institution_id);
            });
        }

        $etudiants = $query->orderBy('nom')->paginate(20);

        return view('admin.etudiants.index', compact('etudiants'));
    }

    public function create()
    {
        $filieres = Filiere::where('statut', 'active')->get();
        return view('admin.etudiants.create', compact('filieres'));
    }

    public function store(EtudiantRequest $request)
    {
        $validated = $request->validated();

        $etudiant = Etudiant::create($validated);

        // Création automatique de l'inscription
        if ($request->filled('filiere_id')) {
            Inscription::create([
                'etudiant_id' => $etudiant->id,
                'filiere_id' => $request->filiere_id,
                'date_inscription' => now(),
                'statut' => 'actif',
                'semestre_courant' => 1,
                'annee_universitaire' => date('Y')
            ]);
        }

        return redirect()->route('admin.etudiants.show', $etudiant->uuid)
            ->with('success', 'Étudiant créé avec succès');
    }

    public function show($uuid)
    {
        $etudiant = Etudiant::where('uuid', $uuid)
            ->with(['inscriptions' => function ($q) {
                $q->with('filiere.institution')->orderBy('date_inscription', 'desc');
            }])
            ->firstOrFail();

        return view('admin.etudiants.show', compact('etudiant'));
    }

    public function edit($uuid)
    {
        $etudiant = Etudiant::where('uuid', $uuid)->firstOrFail();
        $filieres = Filiere::where('statut', 'active')->get();

        return view('admin.etudiants.edit', compact('etudiant', 'filieres'));
    }

    public function update(EtudiantRequest $request, $uuid)
    {
        $etudiant = Etudiant::where('uuid', $uuid)->firstOrFail();
        $etudiant->update($request->validated());

        return redirect()->route('admin.etudiants.show', $uuid)
            ->with('success', 'Étudiant mis à jour avec succès');
    }

    public function destroy($uuid)
    {
        $etudiant = Etudiant::where('uuid', $uuid)->firstOrFail();
        $etudiant->delete();

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant supprimé avec succès');
    }

    // Export des données
    public function export()
    {
        $etudiants = Etudiant::with('inscriptionActive.filiere.institution')->get();

        $filename = 'etudiants_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($etudiants) {
            $file = fopen('php://output', 'w');

            // En-têtes
            fputcsv($file, ['Nom', 'Prénom', 'Email', 'Niveau', 'Filière', 'Institution', 'Statut']);

            // Données
            foreach ($etudiants as $etudiant) {
                $inscription = $etudiant->inscriptionActive;
                fputcsv($file, [
                    $etudiant->nom,
                    $etudiant->prenom,
                    $etudiant->email,
                    $inscription ? $inscription->filiere->niveau : 'N/A',
                    $inscription ? $inscription->filiere->nom : 'N/A',
                    $inscription ? $inscription->filiere->institution->nom : 'N/A',
                    $inscription ? $inscription->statut : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
