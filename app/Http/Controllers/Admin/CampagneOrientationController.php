<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampagneOrientationRequest;
use App\Models\CampagneOrientation;
use App\Models\Orientation;
use App\Services\OrientationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CampagneOrientationController extends Controller
{
    public function __construct(private readonly OrientationService $orientationService)
    {
    }

    public function index()
    {
        $campagnes = CampagneOrientation::query()
            ->withCount(['offres', 'candidats'])
            ->latest()
            ->paginate(15);

        return view('admin.orientation.campagnes.index', compact('campagnes'));
    }

    public function create()
    {
        return view('admin.orientation.campagnes.form', ['campagne' => null]);
    }

    public function store(CampagneOrientationRequest $request)
    {
        CampagneOrientation::create($request->validated() + ['cree_par_id' => $request->user()->id]);

        return redirect()->route('admin.orientation.campagnes.index')
            ->with('success', __('lang.crud.created', ['resource' => __('lang.resources.campagne_orientation')]));
    }

    public function show(CampagneOrientation $campagne)
    {
        $campagne->load(['offres.filiere.institution', 'creePar']);

        return view('admin.orientation.campagnes.show', compact('campagne'));
    }

    public function edit(CampagneOrientation $campagne)
    {
        return view('admin.orientation.campagnes.form', compact('campagne'));
    }

    public function update(CampagneOrientationRequest $request, CampagneOrientation $campagne)
    {
        $campagne->update($request->validated());

        return redirect()->route('admin.orientation.campagnes.index')
            ->with('success', __('lang.crud.updated', ['resource' => __('lang.resources.campagne_orientation')]));
    }

    public function activer(CampagneOrientation $campagne)
    {
        DB::transaction(function () use ($campagne) {
            CampagneOrientation::query()
                ->where('type_orientation', $campagne->type_orientation)
                ->where('statut', 'active')
                ->where('id', '!=', $campagne->id)
                ->lockForUpdate()
                ->update(['statut' => 'fermee']);

            $campagne->update(['statut' => 'active']);
        });

        return back()->with('success', __('lang.orientation.campaign_activated'));
    }

    public function fermer(CampagneOrientation $campagne)
    {
        $campagne->update(['statut' => 'fermee']);

        return back()->with('success', __('lang.orientation.campaign_closed'));
    }

    public function lancerAffectation(CampagneOrientation $campagne)
    {
        try {
            $this->orientationService->lancerAffectation($campagne);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('admin.orientation.campagnes.resultats', $campagne)
            ->with('success', __('lang.orientation.affectation_launched'));
    }

    public function resultats(CampagneOrientation $campagne)
    {
        $orientations = Orientation::query()
            ->where('campagne_orientation_id', $campagne->id)
            ->with(['candidat', 'offre.filiere.institution'])
            ->orderByDesc('statut')
            ->orderByDesc('moyenne')
            ->paginate(30);

        $stats = [
            'total' => Orientation::where('campagne_orientation_id', $campagne->id)->count(),
            'orientees' => Orientation::where('campagne_orientation_id', $campagne->id)->where('statut', 'orientee')->count(),
            'non_orientees' => Orientation::where('campagne_orientation_id', $campagne->id)->where('statut', 'non_orientee')->count(),
        ];

        return view('admin.orientation.campagnes.resultats', compact('campagne', 'orientations', 'stats'));
    }

    public function exportResultats(CampagneOrientation $campagne)
    {
        $orientations = Orientation::query()
            ->where('campagne_orientation_id', $campagne->id)
            ->with(['candidat', 'offre.filiere.institution'])
            ->get();

        return response()->streamDownload(function () use ($orientations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NNI', 'Nom complet', 'Moyenne', 'Statut', 'Formation', 'Etablissement', 'Ordre du choix']);
            foreach ($orientations as $orientation) {
                fputcsv($file, [
                    $orientation->candidat->nni,
                    $orientation->candidat->nom_complet,
                    $orientation->moyenne,
                    $orientation->statut,
                    $orientation->offre?->filiere?->nom,
                    $orientation->offre?->filiere?->institution?->nom,
                    $orientation->ordre_choix,
                ]);
            }
            fclose($file);
        }, 'resultats-orientation-' . $campagne->uuid . '.csv', ['Content-Type' => 'text/csv']);
    }
}
