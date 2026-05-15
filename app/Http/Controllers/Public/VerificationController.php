<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Inscription;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        return view('public.verification.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'numero_bac' => 'required|string|min:5',
            'captcha' => 'required|string'
        ]);

        // Validation simple du captcha (version démo)
        if ($request->captcha !== $request->captcha_expected) {
            return back()
                ->withInput()
                ->with('error', 'Code de sécurité incorrect.');
        }

        $resultat = Etudiant::verifyByNumeroBac($request->numero_bac);

        if (!$resultat) {
            return back()
                ->withInput()
                ->with('error', 'Aucun étudiant trouvé avec ce numéro de baccalauréat.');
        }

        return view('public.verification.resultat', compact('resultat'));
    }

    public function qrVerify($code)
    {
        $inscription = Inscription::where('qr_code_verification', $code)
            ->with(['etudiant', 'filiere.institution'])
            ->first();

        if (!$inscription) {
            return view('public.verification.index')
                ->with('error', 'Code QR invalide ou expiré.');
        }

        if (!$inscription->estValide()) {
            return view('public.verification.resultat', [
                'resultat' => ['status' => 'invalide']
            ]);
        }

        $resultat = [
            'status' => 'valide',
            'niveau' => $inscription->filiere->niveau,
            'filiere' => $inscription->filiere->nom,
            'institution' => $inscription->filiere->institution->nom,
            'annee' => $inscription->annee_universitaire,
            'semestre' => $inscription->semestre_courant,
            'numero_inscription' => $inscription->numero_inscription,
            'qr_code_verification' => $inscription->qr_code_verification
        ];

        return view('public.verification.resultat', compact('resultat'));
    }
}
