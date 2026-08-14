<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrientationProfilRequest;
use App\Models\CandidatOrientation;
use App\Models\DomaineLicence;
use App\Models\TypeBac;
use App\Services\OrientationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrientationController extends Controller
{
    public function __construct(private readonly OrientationService $orientationService)
    {
    }

    public function choixType()
    {
        return view('public.orientation.choix-type');
    }

    public function formulaire(Request $request, string $type)
    {
        $this->validerType($type);

        $campagne = $this->orientationService->campagneActive($type === 'bac-licence' ? 'bac_licence' : 'licence_master');

        return view('public.orientation.formulaire', [
            'type' => $type,
            'campagne' => $campagne,
            'typesBac' => $type === 'bac-licence' ? TypeBac::actifs()->get() : collect(),
            'domainesLicence' => $type === 'licence-master' ? DomaineLicence::actifs()->get() : collect(),
        ]);
    }

    public function soumettreProfil(OrientationProfilRequest $request, string $type)
    {
        $this->validerType($type);

        $campagne = $request->campagne();

        if (!$campagne) {
            return back()->withInput()->with('error', __('lang.orientation.no_active_campaign'));
        }

        if (CandidatOrientation::where('campagne_orientation_id', $campagne->id)->where('nni', $request->nni)->exists()) {
            throw ValidationException::withMessages(['nni' => __('lang.orientation.already_registered')]);
        }

        $data = [
            'campagne_orientation_id' => $campagne->id,
            'type_orientation' => $campagne->type_orientation,
            'nni' => $request->nni,
            'nom_complet' => $request->nom_complet,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'moyenne_generale' => $request->moyenne_generale,
            'annee_obtention' => $request->annee_obtention,
            'statut' => 'brouillon',
            'ip_soumission' => $request->ip(),
        ];

        if ($type === 'bac-licence') {
            $data['type_bac_id'] = $request->type_bac_id;
        } else {
            $data['domaine_licence_id'] = $request->domaine_licence_id;
        }

        foreach (['cni' => 'cni_path', 'releve_notes' => 'releve_notes_path', 'diplome' => 'diplome_path'] as $field => $column) {
            if ($request->hasFile($field)) {
                $data[$column] = $request->file($field)->store('orientation-candidats', 'public');
            }
        }

        $candidat = CandidatOrientation::create($data)->fresh();

        $request->session()->put('orientation_candidat_id', $candidat->id);

        return redirect()->route('public.orientation.offres');
    }

    private function validerType(string $type): void
    {
        abort_unless(in_array($type, ['bac-licence', 'licence-master'], true), 404);
    }
}
