<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CandidatOrientation;
use Illuminate\Http\Request;

class OrientationSuiviController extends Controller
{
    public function index()
    {
        return view('public.orientation.suivi.index');
    }

    public function consulter(Request $request)
    {
        $request->validate([
            'code_suivi' => 'required|string',
            'nni' => 'required|string',
        ]);

        $candidat = CandidatOrientation::query()
            ->where('code_suivi', $request->code_suivi)
            ->where('nni', $request->nni)
            ->with(['campagne', 'orientation.offre.filiere.institution', 'choix.offre.filiere.institution'])
            ->first();

        if (!$candidat) {
            return back()->withInput()->with('error', __('lang.orientation.track_not_found'));
        }

        $resultatsPublies = $candidat->campagne->date_publication_resultats
            && now()->greaterThanOrEqualTo($candidat->campagne->date_publication_resultats);

        return view('public.orientation.suivi.resultat', compact('candidat', 'resultatsPublies'));
    }
}
