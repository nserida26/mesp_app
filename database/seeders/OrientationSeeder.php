<?php

namespace Database\Seeders;

use App\Models\DomaineLicence;
use App\Models\TypeBac;
use Illuminate\Database\Seeder;

class OrientationSeeder extends Seeder
{
    public function run(): void
    {
        $typesBac = ['LM', 'LO', 'M', 'SN', 'TMGM', 'TSE'];

        foreach ($typesBac as $index => $code) {
            TypeBac::firstOrCreate(
                ['code' => $code],
                ['libelle' => $code, 'ordre' => $index + 1, 'statut' => 'actif']
            );
        }

        $domaines = [
            'Informatique', 'Économie', 'Littérature', 'Chariaa', 'Droit',
            'Gestion', 'Mathématiques', 'Sciences', 'Physique', 'Chimie', 'Biologie',
        ];

        foreach ($domaines as $index => $nom) {
            DomaineLicence::firstOrCreate(
                ['nom' => $nom],
                ['ordre' => $index + 1, 'statut' => 'actif']
            );
        }
    }
}
