<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesSimpleResources;
use App\Models\Filiere;
use App\Models\Institution;

class FiliereController extends Controller
{
    use ManagesSimpleResources;

    protected string $modelClass = Filiere::class;
    protected string $resourceName = 'filiere';
    protected array $searchable = ['nom', 'code_filiere', 'niveau'];
    protected array $with = ['institution'];
    protected array $validationRules = [
        'institution_id' => 'required|integer|exists:institutions,id',
        'code_filiere' => 'nullable|string|max:100',
        'nom' => 'required|string|max:255',
        'niveau' => 'required|string|max:50',
        'duree_semestres' => 'required|integer|min:1|max:16',
        'numero_arrete_autorisation' => 'nullable|string|max:150',
        'date_arrete_autorisation' => 'nullable|date',
        'capacite_accueil' => 'required|integer|min:0',
        'statut' => 'required|in:active,inactive',
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

    protected function formExtras(): array
    {
        $user = auth()->user();

        return $user->hasRole('institution')
            ? ['userInstitution' => $user->institution]
            : ['institutions' => Institution::orderBy('nom')->get()];
    }
}
