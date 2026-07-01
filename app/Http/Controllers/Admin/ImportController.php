<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\EtudiantImport;
use App\Imports\InstitutionImport;
use App\Imports\FiliereImport;
use App\Imports\EnseignantImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    private array $tables = [
        'etudiants',
        'institutions',
        'filieres',
        'enseignants',
        'accreditations',
        'calendriers_academiques',
    ];

    private array $importMap = [
        'etudiants'    => EtudiantImport::class,
        'institutions' => InstitutionImport::class,
        'filieres'     => FiliereImport::class,
        'enseignants'  => EnseignantImport::class,
    ];

    public function index()
    {
        return view('admin.imports.index', [
            'tables' => $this->tables,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table' => ['required', 'string', 'in:' . implode(',', $this->tables)],
            'file'  => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
        ]);

        $table = $request->input('table');

        if (!isset($this->importMap[$table])) {
            return back()->with('error', "L'import pour la table « {$table} » n'est pas encore disponible.");
        }

        try {
            Excel::import(new $this->importMap[$table], $request->file('file'));

            return back()->with('success', "Import de « {$table} » effectué avec succès.");
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())->map(fn ($f) =>
                "Ligne {$f->row()} — " . implode(', ', $f->errors())
            )->implode('<br>');

            return back()->withErrors(['import' => $failures]);
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }
}
