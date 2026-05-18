<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Institution;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = collect([
            [
                'nom' => 'Universite Libanaise Internationale en Mauritanie',
                'code_etablissement' => 'LIU',
                'adresse' => 'Ilot C, Tevragh Zeina',
                'ville' => 'Nouakchott',
                'telephone' => '+222 45 29 40 40',
                'email' => 'contact@liu.mr',
                'statut' => 'actif',
            ],
            [
                'nom' => "Universite Sup'Management Mauritanie",
                'code_etablissement' => 'SUPM',
                'adresse' => 'Avenue Mokhtar Ould Daddah',
                'ville' => 'Nouakchott',
                'telephone' => '+222 45 24 30 30',
                'email' => 'contact@supmanagement.mr',
                'statut' => 'actif',
            ],
            [
                'nom' => 'Universite Chinguetti Moderne',
                'code_etablissement' => 'UCM',
                'adresse' => 'Ksar',
                'ville' => 'Nouakchott',
                'telephone' => '+222 45 25 60 60',
                'email' => 'contact@ucm.mr',
                'statut' => 'actif',
            ],
        ])->map(function (array $data) {
            Institution::updateOrCreate(
                ['code_etablissement' => $data['code_etablissement']],
                $data
            );

            return Institution::where('code_etablissement', $data['code_etablissement'])->firstOrFail();
        });

        Institution::whereIn('code_etablissement', ['UNA', 'ISN', 'ENSN'])
            ->update(['statut' => 'inactif']);

        $filieres = collect([
            ['institution' => 'LIU', 'code_filiere' => 'LIU-LIC-GEST', 'nom' => 'Licence en Gestion des Entreprises', 'niveau' => 'licence', 'duree_semestres' => 6, 'capacite_accueil' => 90],
            ['institution' => 'LIU', 'code_filiere' => 'LIU-LIC-INFO', 'nom' => 'Licence en Informatique de Gestion', 'niveau' => 'licence', 'duree_semestres' => 6, 'capacite_accueil' => 80],
            ['institution' => 'SUPM', 'code_filiere' => 'SUPM-LIC-MGT', 'nom' => 'Licence en Management', 'niveau' => 'licence', 'duree_semestres' => 6, 'capacite_accueil' => 100],
            ['institution' => 'SUPM', 'code_filiere' => 'SUPM-MAS-FIN', 'nom' => 'Master Finance et Controle de Gestion', 'niveau' => 'master', 'duree_semestres' => 4, 'capacite_accueil' => 45],
            ['institution' => 'UCM', 'code_filiere' => 'UCM-LIC-DROIT', 'nom' => 'Licence en Droit des Affaires', 'niveau' => 'licence', 'duree_semestres' => 6, 'capacite_accueil' => 70],
        ])->map(function (array $data) {
            $institution = Institution::where('code_etablissement', $data['institution'])->firstOrFail();
            unset($data['institution']);

            Filiere::updateOrCreate(
                ['code_filiere' => $data['code_filiere']],
                $data + [
                    'institution_id' => $institution->id,
                    'numero_arrete_autorisation' => 'AUT-' . date('Y') . '-' . $data['code_filiere'],
                    'date_arrete_autorisation' => '2025-09-01',
                    'statut' => 'active',
                ]
            );

            return Filiere::where('code_filiere', $data['code_filiere'])->firstOrFail();
        });

        Filiere::whereIn('code_filiere', [
            'UNA-LIC-DROIT',
            'UNA-MAS-ECO',
            'ISN-LIC-GL',
            'ISN-MAS-CYB',
            'ENSN-LIC-MATH',
        ])->update(['statut' => 'inactive']);

        $institutions->each(function (Institution $institution, int $index) {
            DB::table('accreditations')->updateOrInsert(
                ['numero_arrete' => 'ACC-2025-' . $institution->code_etablissement],
                [
                    'uuid' => (string) Str::uuid(),
                    'institution_id' => $institution->id,
                    'date_arrete' => '2025-07-15',
                    'date_debut' => '2025-09-01',
                    'date_fin' => '2030-08-31',
                    'type' => $index === 0 ? 'renouvellement' : 'creation',
                    'statut' => 'active',
                    'fichier_arrete_path' => 'arretes/' . strtolower($institution->code_etablissement) . '-2025.pdf',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            DB::table('calendriers_academiques')->updateOrInsert(
                ['institution_id' => $institution->id, 'annee_universitaire' => 2026],
                [
                    'uuid' => (string) Str::uuid(),
                    'debut_semestre_1' => '2026-09-15',
                    'fin_semestre_1' => '2026-12-31',
                    'debut_examens_s1' => '2027-01-05',
                    'fin_examens_s1' => '2027-01-20',
                    'debut_vacances_hiver' => '2027-01-21',
                    'fin_vacances_hiver' => '2027-02-07',
                    'debut_semestre_2' => '2027-02-08',
                    'fin_semestre_2' => '2027-05-28',
                    'debut_examens_s2' => '2027-06-01',
                    'fin_examens_s2' => '2027-06-18',
                    'debut_vacances_ete' => '2027-07-01',
                    'fin_vacances_ete' => '2027-08-31',
                    'statut' => 'publie',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });

        $enseignants = collect([
            ['nom' => 'Ahmed', 'prenom' => 'Mariam', 'numero_national' => '2000000001', 'numero_accreditation' => 'ENS-2026-0001', 'grade' => 'professeur', 'specialite' => 'Droit public', 'email' => 'mariam.ahmed@mesrs.mr', 'telephone' => '+222 22 10 10 01'],
            ['nom' => 'Sidi', 'prenom' => 'Mohamed', 'numero_national' => '2000000002', 'numero_accreditation' => 'ENS-2026-0002', 'grade' => 'maitre_conference', 'specialite' => 'Economie', 'email' => 'mohamed.sidi@mesrs.mr', 'telephone' => '+222 22 10 10 02'],
            ['nom' => 'Diallo', 'prenom' => 'Aminata', 'numero_national' => '2000000003', 'numero_accreditation' => 'ENS-2026-0003', 'grade' => 'maitre_assistant', 'specialite' => 'Genie logiciel', 'email' => 'aminata.diallo@mesrs.mr', 'telephone' => '+222 22 10 10 03'],
            ['nom' => 'Fall', 'prenom' => 'Oumar', 'numero_national' => '2000000004', 'numero_accreditation' => 'ENS-2026-0004', 'grade' => 'assistant', 'specialite' => 'Mathematiques', 'email' => 'oumar.fall@mesrs.mr', 'telephone' => '+222 22 10 10 04'],
        ])->map(function (array $data) {
            Enseignant::updateOrCreate(
                ['numero_accreditation' => $data['numero_accreditation']],
                $data + ['statut' => 'actif']
            );

            return Enseignant::where('numero_accreditation', $data['numero_accreditation'])->firstOrFail();
        });

        DB::table('affectation_enseignant')
            ->whereIn('enseignant_id', $enseignants->pluck('id'))
            ->where('annee_universitaire', 2026)
            ->delete();

        $filieres->values()->each(function (Filiere $filiere, int $index) use ($enseignants) {
            $enseignant = $enseignants[$index % $enseignants->count()];

            DB::table('affectation_enseignant')->updateOrInsert(
                [
                    'enseignant_id' => $enseignant->id,
                    'institution_id' => $filiere->institution_id,
                    'filiere_id' => $filiere->id,
                    'annee_universitaire' => 2026,
                ],
                [
                    'volume_horaire' => 96 + ($index * 12),
                    'type_contrat' => $index % 2 === 0 ? 'permanent' : 'vacataire',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });

        $students = collect([
            ['nom' => 'Ould Salem', 'prenom' => 'Khaled', 'date_naissance' => '2004-03-12', 'lieu_naissance' => 'Nouakchott', 'numero_national' => '1000000001', 'hash_numero_bac' => 'BAC-2023-0001', 'serie_bac' => 'C', 'annee_bac' => 2023, 'mention_bac' => 'Bien', 'email' => 'khaled.salem@example.com', 'telephone' => '+222 36 00 00 01', 'filiere' => 'LIU-LIC-INFO', 'semestre_courant' => 5, 'moyenne_generale' => 14.75],
            ['nom' => 'Mint Ely', 'prenom' => 'Fatimetou', 'date_naissance' => '2005-07-24', 'lieu_naissance' => 'Atar', 'numero_national' => '1000000002', 'hash_numero_bac' => 'BAC-2024-0002', 'serie_bac' => 'D', 'annee_bac' => 2024, 'mention_bac' => 'Assez bien', 'email' => 'fatimetou.ely@example.com', 'telephone' => '+222 36 00 00 02', 'filiere' => 'LIU-LIC-GEST', 'semestre_courant' => 3, 'moyenne_generale' => 13.2],
            ['nom' => 'Ba', 'prenom' => 'Moussa', 'date_naissance' => '2003-11-02', 'lieu_naissance' => 'Kaedi', 'numero_national' => '1000000003', 'hash_numero_bac' => 'BAC-2022-0003', 'serie_bac' => 'C', 'annee_bac' => 2022, 'mention_bac' => 'Tres bien', 'email' => 'moussa.ba@example.com', 'telephone' => '+222 36 00 00 03', 'filiere' => 'SUPM-MAS-FIN', 'semestre_courant' => 2, 'moyenne_generale' => 16.1],
            ['nom' => 'Cheikh', 'prenom' => 'Aicha', 'date_naissance' => '2004-09-18', 'lieu_naissance' => 'Rosso', 'numero_national' => '1000000004', 'hash_numero_bac' => 'BAC-2023-0004', 'serie_bac' => 'LM', 'annee_bac' => 2023, 'mention_bac' => 'Bien', 'email' => 'aicha.cheikh@example.com', 'telephone' => '+222 36 00 00 04', 'filiere' => 'UCM-LIC-DROIT', 'semestre_courant' => 5, 'moyenne_generale' => 15.4],
            ['nom' => 'Hamed', 'prenom' => 'Leila', 'date_naissance' => '2002-01-29', 'lieu_naissance' => 'Nouadhibou', 'numero_national' => '1000000005', 'hash_numero_bac' => 'BAC-2021-0005', 'serie_bac' => 'ES', 'annee_bac' => 2021, 'mention_bac' => 'Assez bien', 'email' => 'leila.hamed@example.com', 'telephone' => '+222 36 00 00 05', 'filiere' => 'SUPM-LIC-MGT', 'semestre_courant' => 4, 'moyenne_generale' => 12.85],
        ])->map(function (array $data) {
            $filiereCode = $data['filiere'];
            $semestreCourant = $data['semestre_courant'];
            $moyenneGenerale = $data['moyenne_generale'];
            unset($data['filiere'], $data['semestre_courant'], $data['moyenne_generale']);

            Etudiant::updateOrCreate(['email' => $data['email']], $data);

            $etudiant = Etudiant::where('email', $data['email'])->firstOrFail();
            $filiere = Filiere::where('code_filiere', $filiereCode)->firstOrFail();

            Inscription::where('etudiant_id', $etudiant->id)
                ->where('annee_universitaire', 2026)
                ->update(['statut' => 'inactif']);

            Inscription::updateOrCreate(
                ['etudiant_id' => $etudiant->id, 'filiere_id' => $filiere->id],
                [
                    'date_inscription' => '2026-09-20',
                    'statut' => 'actif',
                    'semestre_courant' => $semestreCourant,
                    'annee_universitaire' => 2026,
                    'moyenne_generale' => $moyenneGenerale,
                ]
            );

            return $etudiant;
        });

        $this->seedUsers($institutions);
        $this->seedActivityLog();
        $this->seedPersonalAccessToken();

        $this->command?->info('Demo data seeded for institutions, filieres, students, teachers, accreditations, calendars, assignments, users, activity log and tokens.');
    }

    private function seedUsers($institutions): void
    {
        $users = [
            ['name' => 'Agent Ministere', 'email' => 'ministere@mesrs.gov.mr', 'role' => 'ministere', 'institution_id' => null],
            ['name' => 'Gestionnaire LIU', 'email' => 'gestionnaire.liu@mesrs.gov.mr', 'role' => 'institution', 'institution_id' => $institutions->firstWhere('code_etablissement', 'LIU')->id],
            ['name' => 'Gestionnaire SUPM', 'email' => 'gestionnaire.supm@mesrs.gov.mr', 'role' => 'institution', 'institution_id' => $institutions->firstWhere('code_etablissement', 'SUPM')->id],
            ['name' => 'Utilisateur Verification', 'email' => 'verification@example.com', 'role' => 'public', 'institution_id' => null],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData + ['password' => 'password']
            );

            $user->syncRoles([$userData['role']]);
        }
    }

    private function seedActivityLog(): void
    {
        DB::table('activity_log')->updateOrInsert(
            ['description' => 'Donnees de demonstration chargees'],
            [
                'log_name' => 'seed',
                'subject_type' => null,
                'subject_id' => null,
                'causer_type' => null,
                'causer_id' => null,
                'properties' => json_encode(['source' => self::class]),
                'batch_uuid' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function seedPersonalAccessToken(): void
    {
        $user = User::where('email', 'ministere@mesrs.gov.mr')->first();

        if (!$user || PersonalAccessToken::where('name', 'demo-token')->where('tokenable_id', $user->id)->exists()) {
            return;
        }

        $user->createToken('demo-token', ['*']);
    }
}
