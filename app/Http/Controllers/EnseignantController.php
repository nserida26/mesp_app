<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Enseignant;

class EnseignantController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Enseignant::class;
    protected string $resourceName = 'enseignant';
    protected array $searchable = ['nom', 'prenom', 'numero_national', 'email', 'specialite'];
    protected array $validationRules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'numero_national' => 'nullable|string|max:255',
        'numero_accreditation' => 'nullable|string|max:150',
        'grade' => 'required|string|max:100',
        'specialite' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:50',
        'statut' => 'required|in:actif,inactif',
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
}
