<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OffreOrientationRequest;
use App\Models\CampagneOrientation;
use App\Models\DomaineLicence;
use App\Models\Filiere;
use App\Models\OffreOrientation;
use App\Models\TypeBac;
use Illuminate\Http\Request;

class OffreOrientationController extends Controller
{
    public function index(Request $request)
    {
        $offres = OffreOrientation::query()
            ->with(['campagne', 'filiere.institution'])
            ->when($request->filled('campagne'), fn ($q) => $q->where('campagne_orientation_id', $request->integer('campagne')))
            ->latest()
            ->paginate(20);

        $campagnes = CampagneOrientation::query()->orderByDesc('annee_universitaire')->get();

        return view('admin.orientation.offres.index', compact('offres', 'campagnes'));
    }

    public function create(Request $request)
    {
        $campagne = $request->filled('campagne')
            ? CampagneOrientation::where('uuid', $request->campagne)->firstOrFail()
            : null;

        return view('admin.orientation.offres.form', array_merge(
            ['offre' => null, 'campagne' => $campagne],
            $this->formOptions()
        ));
    }

    public function store(OffreOrientationRequest $request)
    {
        $offre = OffreOrientation::create($request->safe()->only([
            'campagne_orientation_id', 'filiere_id', 'capacite', 'moyenne_minimale', 'statut',
        ]))->fresh();

        $this->syncCriteres($offre, $request);

        return redirect()->route('admin.orientation.campagnes.show', $offre->campagne->uuid)
            ->with('success', __('lang.crud.created', ['resource' => __('lang.resources.offre_orientation')]));
    }

    public function edit(OffreOrientation $offre)
    {
        $offre->load(['typesBac', 'domainesLicence']);

        return view('admin.orientation.offres.form', array_merge(
            ['offre' => $offre, 'campagne' => $offre->campagne],
            $this->formOptions()
        ));
    }

    public function update(OffreOrientationRequest $request, OffreOrientation $offre)
    {
        $offre->update($request->safe()->only([
            'campagne_orientation_id', 'filiere_id', 'capacite', 'moyenne_minimale', 'statut',
        ]));

        $this->syncCriteres($offre, $request);

        return redirect()->route('admin.orientation.campagnes.show', $offre->campagne->uuid)
            ->with('success', __('lang.crud.updated', ['resource' => __('lang.resources.offre_orientation')]));
    }

    public function destroy(OffreOrientation $offre)
    {
        $campagneUuid = $offre->campagne->uuid;
        $offre->delete();

        return redirect()->route('admin.orientation.campagnes.show', $campagneUuid)
            ->with('success', __('lang.crud.deleted', ['resource' => __('lang.resources.offre_orientation')]));
    }

    private function syncCriteres(OffreOrientation $offre, OffreOrientationRequest $request): void
    {
        if ($offre->campagne->type_orientation === 'bac_licence') {
            $offre->typesBac()->sync($request->input('types_bac', []));
            $offre->domainesLicence()->sync([]);
        } else {
            $offre->domainesLicence()->sync($request->input('domaines_licence', []));
            $offre->typesBac()->sync([]);
        }
    }

    private function formOptions(): array
    {
        return [
            'campagnes' => CampagneOrientation::query()->orderByDesc('annee_universitaire')->get(),
            'filieres' => Filiere::query()->with('institution')->orderBy('nom')->get(),
            'typesBac' => TypeBac::actifs()->get(),
            'domainesLicence' => DomaineLicence::actifs()->get(),
        ];
    }
}
