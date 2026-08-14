<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Etudiant;

class EtudiantController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Etudiant::class;
    protected string $resourceName = 'etudiant';
    protected array $searchable = ['nom', 'prenom', 'email'];
    protected array $with = ['inscriptionActive.filiere.institution'];
    protected array $validationRules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'date_naissance' => 'nullable|date',
        'lieu_naissance' => 'nullable|string|max:255',
        'numero_national' => 'nullable|string|max:255',
        'hash_numero_bac' => 'required|string|max:255',
        'serie_bac' => 'nullable|string|max:100',
        'annee_bac' => 'nullable|integer|min:1980|max:2100',
        'mention_bac' => 'nullable|string|max:100',
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:50',
    ];

    protected function scopeForUser($query)
    {
        $user = auth()->user();

        return $user->hasRole('institution')
            ? $query->where('institution_id', $user->institution_id)
            : $query;
    }

    protected function applyCreationDefaults(array $validated): array
    {
        $user = auth()->user();

        if ($user->hasRole('institution')) {
            $validated['institution_id'] = $user->institution_id;
            $validated['statut_validation'] = 'en_attente';
        } else {
            $validated['statut_validation'] = 'valide';
            $validated['valide_par_id'] = $user->id;
            $validated['valide_le'] = now();
        }

        $validated['cree_par_id'] = $user->id;

        return $validated;
    }

    protected function applyUpdateDefaults(array $validated, $item): array
    {
        $user = auth()->user();

        if ($user->hasRole('institution')) {
            $validated['institution_id'] = $user->institution_id;
            $validated['statut_validation'] = 'en_attente';
            $validated['valide_par_id'] = null;
            $validated['valide_le'] = null;
            $validated['motif_rejet'] = null;
        }

        return $validated;
    }

    protected function guardEditable($item): void
    {
        if (auth()->user()->hasRole('institution') && $item->statut_validation === 'valide') {
            abort(403, "Cet enregistrement est deja valide ; contactez l'administrateur pour le modifier.");
        }
    }

    public function export()
    {
        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nom', 'Prenom', 'Email', 'Telephone']);
            Etudiant::orderBy('nom')->each(fn ($etudiant) => fputcsv($file, [
                $etudiant->nom,
                $etudiant->prenom,
                $etudiant->email,
                $etudiant->telephone,
            ]));
            fclose($file);
        }, 'etudiants.csv', ['Content-Type' => 'text/csv']);
    }
}
