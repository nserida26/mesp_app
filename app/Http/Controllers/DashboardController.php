<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Enseignant;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Stats visible selon le rôle / les permissions
        $stats = [];

        if ($user->can('view institutions')) {
            $stats['institutions'] = Institution::count();
        }
        if ($user->can('view etudiants')) {
            // Institution users see only their own students
            $stats['etudiants'] = $user->hasRole('institution')
                ? Etudiant::where('institution_id', $user->institution_id)->count()
                : Etudiant::count();
        }
        if ($user->can('view filieres')) {
            $stats['filieres'] = Filiere::count();
        }
        if ($user->can('view enseignants')) {
            $stats['enseignants'] = Enseignant::count();
        }

        if ($user->hasRole('institution')) {
            $instId = $user->institution_id;
            $stats['en_attente'] = Filiere::where('institution_id', $instId)->where('statut_validation', 'en_attente')->count()
                + Etudiant::where('institution_id', $instId)->where('statut_validation', 'en_attente')->count()
                + Enseignant::where('institution_id', $instId)->where('statut_validation', 'en_attente')->count();
        }

        if ($user->can('validate filieres') || $user->can('validate etudiants') || $user->can('validate enseignants')) {
            $stats['a_valider'] = Filiere::where('statut_validation', 'en_attente')->count()
                + Etudiant::where('statut_validation', 'en_attente')->count()
                + Enseignant::where('statut_validation', 'en_attente')->count();
        }

        // Recent audit logs — only for admin / ministere
        $recentLogs = collect();
        if ($user->can('view audit-logs')) {
            // $recentLogs = AuditLog::with('user')->latest()->limit(10)->get();
        }

        return view('pages.dashboard', compact('stats', 'recentLogs'));
    }
}
