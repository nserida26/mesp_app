<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Filiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    private const TYPES = [
        'enseignants' => Enseignant::class,
        'etudiants' => Etudiant::class,
        'filieres' => Filiere::class,
    ];

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'en_attente') === 'traitees' ? 'traitees' : 'en_attente';
        $items = collect();

        foreach (self::TYPES as $type => $class) {
            $query = $class::query()->with('institution', 'creePar', 'validePar');

            $query = $tab === 'traitees'
                ? $query->whereIn('statut_validation', ['valide', 'rejete'])->whereNotNull('valide_le')
                : $query->where('statut_validation', 'en_attente');

            foreach ($query->latest('updated_at')->limit(100)->get() as $model) {
                $items->push(['type' => $type, 'model' => $model]);
            }
        }

        $items = $items->sortByDesc(fn ($entry) => $entry['model']->updated_at)->values();

        return view('admin.validations.index', [
            'items' => $items,
            'tab' => $tab,
        ]);
    }

    public function approve(Request $request, string $type, string $uuid)
    {
        abort_unless($request->user()->can("validate {$type}"), 403);
        $model = $this->findRecord($type, $uuid);

        $model->update([
            'statut_validation' => 'valide',
            'valide_par_id' => $request->user()->id,
            'valide_le' => now(),
            'motif_rejet' => null,
        ]);

        return back()->with('success', 'Element valide avec succes.');
    }

    public function reject(Request $request, string $type, string $uuid)
    {
        abort_unless($request->user()->can("validate {$type}"), 403);
        $model = $this->findRecord($type, $uuid);

        $data = $request->validate([
            'motif' => 'required|string|max:1000',
        ]);

        $model->update([
            'statut_validation' => 'rejete',
            'valide_par_id' => $request->user()->id,
            'valide_le' => now(),
            'motif_rejet' => $data['motif'],
        ]);

        return back()->with('success', 'Element rejete.');
    }

    private function findRecord(string $type, string $uuid): Model
    {
        abort_unless(array_key_exists($type, self::TYPES), 404);

        return self::TYPES[$type]::where('uuid', $uuid)->firstOrFail();
    }
}
