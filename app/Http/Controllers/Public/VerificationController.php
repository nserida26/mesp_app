<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        return view('public.verification.index', [
            'captcha' => $this->refreshCaptcha($request),
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:student,teacher,institution,formation',
            'captcha' => 'required|string',
        ]);

        if (!hash_equals((string) $request->session()->get('verification_captcha'), $request->captcha)) {
            $this->refreshCaptcha($request);

            return back()->withInput()->with('error', 'Code de securite incorrect.');
        }

        $request->session()->forget('verification_captcha');

        $resultat = match ($request->input('type', 'student')) {
            'institution' => $this->verifyInstitution($request),
            'formation' => $this->verifyFormation($request),
            'teacher' => $this->verifyTeacher($request),
            default => $this->verifyStudent($request),
        };

        if (!$resultat) {
            return back()->withInput()->with('error', 'Aucun resultat valide trouve pour cette verification.');
        }

        return view('public.verification.resultat', compact('resultat'));
    }

    private function verifyStudent(Request $request): ?array
    {
        $request->validate(['numero_national' => 'required|string|min:5']);

        return Etudiant::verifyByNumeroNational($request->numero_national);
    }

    private function verifyInstitution(Request $request): ?array
    {
        $request->validate(['code_etablissement' => 'required|string|min:2']);

        $term = $request->code_etablissement;
        $institution = Institution::query()
            ->with('accreditationActive')
            ->where('code_etablissement', $term)
            ->orWhere('nom', 'like', "%{$term}%")
            ->first();

        if (!$institution) {
            return null;
        }

        return [
            'status' => $institution->accreditationActive ? 'valide' : 'invalide',
            'type' => 'institution',
            'institution' => $institution->nom,
            'ville' => $institution->ville,
            'statut' => $institution->statut,
            'date_fin' => $institution->accreditationActive?->date_fin?->format('d/m/Y'),
        ];
    }

    private function verifyFormation(Request $request): ?array
    {
        $request->validate(['code_filiere' => 'required|string|min:2']);

        $term = $request->code_filiere;
        $filiere = Filiere::query()
            ->with('institution.accreditationActive')
            ->where('code_filiere', $term)
            ->orWhere('nom', 'like', "%{$term}%")
            ->first();

        if (!$filiere) {
            return null;
        }

        return [
            'status' => $filiere->estValide() ? 'valide' : 'invalide',
            'type' => 'formation',
            'niveau' => $filiere->niveau,
            'filiere' => $filiere->nom,
            'institution' => $filiere->institution->nom,
            'statut' => $filiere->statut,
        ];
    }

    private function verifyTeacher(Request $request): ?array
    {
        $request->validate(['numero_national' => 'required|string|min:5']);

        return Enseignant::verifyByNumeroNational($request->numero_national);
    }

    private function refreshCaptcha(Request $request): string
    {
        $captcha = Str::upper(Str::random(6));
        $request->session()->put('verification_captcha', $captcha);

        return $captcha;
    }
}
