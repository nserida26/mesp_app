<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CandidatOrientation;
use App\Models\OffreOrientation;
use App\Services\OrientationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrientationChoixController extends Controller
{
    public function __construct(private readonly OrientationService $orientationService)
    {
    }

    public function index(Request $request)
    {
        $candidat = $this->candidatCourant($request);

        $offres = $this->orientationService->offresEligibles($candidat);
        $choix = $candidat->choix()->with('offre.filiere.institution')->get();
        $offreIdsChoisies = $choix->pluck('offre_orientation_id')->all();

        return view('public.orientation.offres.index', compact('candidat', 'offres', 'choix', 'offreIdsChoisies'));
    }

    public function ajouter(Request $request, OffreOrientation $offre)
    {
        $candidat = $this->candidatCourant($request);

        try {
            $this->orientationService->ajouterChoix($candidat, $offre);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', __('lang.orientation.select_offer'));
    }

    public function retirer(Request $request, OffreOrientation $offre)
    {
        $candidat = $this->candidatCourant($request);
        $this->orientationService->retirerChoix($candidat, $offre);

        return back();
    }

    public function reordonner(Request $request)
    {
        $candidat = $this->candidatCourant($request);
        $data = $request->validate(['offres' => 'required|array']);

        $this->orientationService->reordonnerChoix($candidat, $data['offres']);

        return back();
    }

    public function recapitulatif(Request $request)
    {
        $candidat = $this->candidatCourant($request);
        $choix = $candidat->choix()->with('offre.filiere.institution')->get();

        return view('public.orientation.offres.recapitulatif', compact('candidat', 'choix'));
    }

    public function valider(Request $request)
    {
        $candidat = $this->candidatCourant($request);

        try {
            $code = $this->orientationService->validerDefinitivement($candidat);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        $request->session()->put('orientation_code_suivi', $code);
        $request->session()->forget('orientation_candidat_id');

        return redirect()->route('public.orientation.confirmation');
    }

    public function confirmation(Request $request)
    {
        $code = $request->session()->get('orientation_code_suivi');
        abort_unless($code, 404);

        return view('public.orientation.confirmation', ['code' => $code]);
    }

    private function candidatCourant(Request $request): CandidatOrientation
    {
        $id = $request->session()->get('orientation_candidat_id');
        abort_unless($id, 404);

        $candidat = CandidatOrientation::where('id', $id)->where('statut', 'brouillon')->first();
        abort_unless($candidat, 404);

        return $candidat;
    }
}
