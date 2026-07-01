<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\EtudiantExport;
use App\Exports\InstitutionExport;
use App\Exports\FiliereExport;
use App\Exports\EnseignantExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    private array $allowed = [
        'etudiants'    => [EtudiantExport::class,    'etudiants_inscriptions'],
        'institutions' => [InstitutionExport::class,  'institutions'],
        'filieres'     => [FiliereExport::class,      'filieres'],
        'enseignants'  => [EnseignantExport::class,   'enseignants_affectations'],
    ];

    public function download(string $resource)
    {
        abort_unless(array_key_exists($resource, $this->allowed), 404);

        [$exportClass, $filename] = $this->allowed[$resource];

        $date = now()->format('Ymd_Hi');

        return Excel::download(new $exportClass, "{$filename}_{$date}.xlsx");
    }
}
