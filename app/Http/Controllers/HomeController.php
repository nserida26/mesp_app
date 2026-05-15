<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Enseignant;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'institutions' => Institution::count(),
            'etudiants'    => Etudiant::count(),
            'filieres'     => Filiere::count(),
            'enseignants'  => Enseignant::count(),
        ];

        return view('public.home', compact('stats'));
    }
}
