<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Institution;
use App\Models\Inscription;
use App\Models\User;
use Database\Seeders\Support\RealStudentImporter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class DemoDataSeeder extends Seeder
{
    private const ANNEE_UNIVERSITAIRE = 2026;

    public function run(): void
    {
        $institutions = $this->seedInstitutions();
        $studentsByInstitution = (new RealStudentImporter())->all();

        $filieres = collect();
        foreach ($studentsByInstitution as $code => $records) {
            if (!isset($institutions[$code]) || empty($records)) continue;

            $filieres = $filieres->merge(
                $this->seedFilieresForInstitution($institutions[$code], $records)
            );
        }

        foreach ($studentsByInstitution as $code => $records) {
            if (!isset($institutions[$code])) continue;

            $this->seedStudents($code, $institutions[$code], $records, $filieres);
        }

        $institutions->each(fn (Institution $institution) => $this->seedAccreditationEtCalendrier($institution));

        $enseignants = $this->seedEnseignants();
        $this->seedAffectations($enseignants, $filieres);

        $this->seedUsers($institutions);
        $this->seedActivityLog();
        $this->seedPersonalAccessToken();

        $this->command?->info(sprintf(
            'Demo data seeded: %d institutions, %d filieres, %d etudiants.',
            $institutions->count(),
            $filieres->count(),
            Etudiant::count()
        ));
    }

    /**
     * @return \Illuminate\Support\Collection<string, Institution> keyed by code_etablissement
     */
    private function seedInstitutions(): \Illuminate\Support\Collection
    {
        return collect([
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
                'nom' => "GEU l'Academie",
                'code_etablissement' => 'GEU',
                'adresse' => null,
                'ville' => 'Nouakchott',
                'telephone' => null,
                'email' => 'contact@geu.mr',
                'statut' => 'actif',
            ],
            [
                'nom' => "Institut Superieur d'Informatique",
                'code_etablissement' => 'ISI',
                'adresse' => null,
                'ville' => 'Nouakchott',
                'telephone' => null,
                'email' => 'contact@isi.mr',
                'statut' => 'actif',
            ],
            [
                'nom' => 'International School of Business',
                'code_etablissement' => 'ISB',
                'adresse' => null,
                'ville' => 'Nouakchott',
                'telephone' => null,
                'email' => 'contact@isb.mr',
                'statut' => 'actif',
            ],
            [
                'nom' => 'Student Edge Mauritania',
                'code_etablissement' => 'EDGE',
                'adresse' => null,
                'ville' => 'Nouakchott',
                'telephone' => null,
                'email' => 'contact@edge.mr',
                'statut' => 'actif',
            ],
        ])->mapWithKeys(function (array $data) {
            Institution::updateOrCreate(['code_etablissement' => $data['code_etablissement']], $data);

            return [$data['code_etablissement'] => Institution::where('code_etablissement', $data['code_etablissement'])->firstOrFail()];
        });
    }

    /**
     * @param array<int, array> $records
     * @return \Illuminate\Support\Collection<int, Filiere>
     */
    private function seedFilieresForInstitution(Institution $institution, array $records): \Illuminate\Support\Collection
    {
        $groups = collect($records)->groupBy('filiere_nom');
        $usedCodes = [];

        return $groups->map(function ($groupRecords, string $filiereNom) use ($institution, &$usedCodes) {
            $niveau = $groupRecords->first()['filiere_niveau'];
            $effectif = $groupRecords->count();

            $baseCode = strtoupper($institution->code_etablissement) . '-'
                . ($niveau === 'master' ? 'MAS' : 'LIC') . '-'
                . strtoupper(Str::slug($filiereNom, ''));
            $baseCode = substr($baseCode, 0, 45);

            $code = $baseCode;
            $suffix = 2;
            while (in_array($code, $usedCodes, true)) {
                $code = substr($baseCode, 0, 42) . $suffix;
                $suffix++;
            }
            $usedCodes[] = $code;

            Filiere::updateOrCreate(
                ['code_filiere' => $code],
                [
                    'institution_id' => $institution->id,
                    'nom' => $filiereNom,
                    'niveau' => $niveau,
                    'duree_semestres' => $niveau === 'master' ? 4 : 6,
                    'numero_arrete_autorisation' => 'AUT-' . date('Y') . '-' . $code,
                    'date_arrete_autorisation' => '2025-09-01',
                    'capacite_accueil' => (int) ceil($effectif * 1.3),
                    'statut' => 'active',
                ]
            );

            return Filiere::where('code_filiere', $code)->firstOrFail();
        })->values();
    }

    /**
     * @param array<int, array> $records
     * @param \Illuminate\Support\Collection<int, Filiere> $filieres
     */
    private function seedStudents(string $institutionCode, Institution $institution, array $records, \Illuminate\Support\Collection $filieres): void
    {
        $filieresByNom = $filieres->where('institution_id', $institution->id)->keyBy('nom');

        foreach ($records as $index => $record) {
            $filiere = $filieresByNom->get($record['filiere_nom']);
            if (!$filiere) continue;

            $bacKey = $institutionCode . '|' . ($record['numero_bac'] ?: $record['numero_national'] ?: "ROW{$index}");

            $etudiant = Etudiant::updateOrCreate(
                ['hash_numero_bac' => hash('sha256', $bacKey)],
                [
                    'nom' => $record['nom'],
                    'prenom' => $record['prenom'],
                    'date_naissance' => $record['date_naissance'],
                    'lieu_naissance' => null,
                    'numero_national' => $record['numero_national'],
                    'hash_numero_bac' => $bacKey,
                    'serie_bac' => $record['serie_bac'],
                    'annee_bac' => $record['annee_bac'],
                    'mention_bac' => null,
                    'email' => null,
                    'telephone' => null,
                ]
            );

            // Etudiant is keyed by non-incrementing uuid, so updateOrCreate() never
            // fetches the auto-increment `id` back for newly-created rows; reload it.
            $etudiant->refresh();

            Inscription::where('etudiant_id', $etudiant->id)
                ->where('annee_universitaire', self::ANNEE_UNIVERSITAIRE)
                ->where('filiere_id', '!=', $filiere->id)
                ->update(['statut' => 'inactif']);

            Inscription::updateOrCreate(
                ['etudiant_id' => $etudiant->id, 'filiere_id' => $filiere->id],
                [
                    'date_inscription' => '2025-09-20',
                    'statut' => 'actif',
                    'semestre_courant' => $record['semestre_courant'],
                    'annee_universitaire' => self::ANNEE_UNIVERSITAIRE,
                    'moyenne_generale' => null,
                ]
            );
        }
    }

    private function seedAccreditationEtCalendrier(Institution $institution): void
    {
        DB::table('accreditations')->updateOrInsert(
            ['numero_arrete' => 'ACC-2025-' . $institution->code_etablissement],
            [
                'uuid' => (string) Str::uuid(),
                'institution_id' => $institution->id,
                'date_arrete' => '2025-07-15',
                'date_debut' => '2025-09-01',
                'date_fin' => '2030-08-31',
                'type' => 'creation',
                'statut' => 'active',
                'fichier_arrete_path' => 'arretes/' . strtolower($institution->code_etablissement) . '-2025.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('calendriers_academiques')->updateOrInsert(
            ['institution_id' => $institution->id, 'annee_universitaire' => self::ANNEE_UNIVERSITAIRE],
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
    }

    /**
     * @return \Illuminate\Support\Collection<int, Enseignant>
     */
    private function seedEnseignants(): \Illuminate\Support\Collection
    {
        return collect([
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
    }

    /**
     * @param \Illuminate\Support\Collection<int, Enseignant> $enseignants
     * @param \Illuminate\Support\Collection<int, Filiere> $filieres
     */
    private function seedAffectations(\Illuminate\Support\Collection $enseignants, \Illuminate\Support\Collection $filieres): void
    {
        DB::table('affectation_enseignant')
            ->whereIn('enseignant_id', $enseignants->pluck('id'))
            ->where('annee_universitaire', self::ANNEE_UNIVERSITAIRE)
            ->delete();

        $filieres->values()->each(function (Filiere $filiere, int $index) use ($enseignants) {
            $enseignant = $enseignants[$index % $enseignants->count()];

            DB::table('affectation_enseignant')->updateOrInsert(
                [
                    'enseignant_id' => $enseignant->id,
                    'institution_id' => $filiere->institution_id,
                    'filiere_id' => $filiere->id,
                    'annee_universitaire' => self::ANNEE_UNIVERSITAIRE,
                ],
                [
                    'volume_horaire' => 96 + ($index % 6) * 12,
                    'type_contrat' => $index % 2 === 0 ? 'permanent' : 'vacataire',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });
    }

    private function seedUsers(\Illuminate\Support\Collection $institutions): void
    {
        $users = [
            ['name' => 'Agent Ministere', 'email' => 'ministere@mesrs.gov.mr', 'role' => 'ministere', 'institution_id' => null],
            ['name' => 'Gestionnaire LIU', 'email' => 'gestionnaire.liu@mesrs.gov.mr', 'role' => 'institution', 'institution_id' => $institutions['LIU']->id],
            ['name' => 'Gestionnaire SUPM', 'email' => 'gestionnaire.supm@mesrs.gov.mr', 'role' => 'institution', 'institution_id' => $institutions['SUPM']->id],
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
