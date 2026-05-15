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

        // Recent audit logs — only for admin / ministere
        $recentLogs = collect();
        if ($user->can('view audit-logs')) {
            // $recentLogs = AuditLog::with('user')->latest()->limit(10)->get();
        }

        return view('pages.dashboard', compact('stats', 'recentLogs'));
    }
}
