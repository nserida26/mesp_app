<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.imports.index', [
            'tables' => ['institutions', 'filieres', 'etudiants', 'inscriptions', 'enseignants', 'affectation_enseignant', 'accreditations', 'calendriers_academiques'],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $path = $request->file('file')->store('imports');

        return back()->with('success', "Fichier importe dans la file de traitement: {$path}");
    }
}
