<?php

namespace Tests\Unit;

use App\Models\CampagneOrientation;
use App\Models\CandidatOrientation;
use App\Models\ChoixOrientation;
use App\Models\Filiere;
use App\Models\Institution;
use App\Models\OffreOrientation;
use App\Models\Orientation;
use App\Models\TypeBac;
use App\Services\OrientationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrientationServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrientationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrientationService();
    }

    private function campagneFermee(): CampagneOrientation
    {
        return CampagneOrientation::create([
            'type_orientation' => 'bac_licence',
            'nom' => 'Campagne test',
            'annee_universitaire' => 2026,
            'date_ouverture' => now()->subMonth(),
            'date_fermeture' => now()->subDay(),
            'nombre_max_choix' => 5,
            'statut' => 'fermee',
        ])->fresh();
    }

    private function offre(CampagneOrientation $campagne, int $capacite, string $suffix = 'A'): OffreOrientation
    {
        $institution = Institution::create([
            'nom' => "Institut $suffix",
            'code_etablissement' => "INST-$suffix-" . uniqid(),
            'ville' => 'Nouakchott',
            'statut' => 'actif',
        ])->fresh();

        $filiere = Filiere::create([
            'institution_id' => $institution->id,
            'code_filiere' => "FIL-$suffix-" . uniqid(),
            'nom' => "Licence $suffix",
            'niveau' => 'licence',
            'duree_semestres' => 6,
            'capacite_accueil' => $capacite,
            'statut' => 'active',
        ])->fresh();

        return OffreOrientation::create([
            'campagne_orientation_id' => $campagne->id,
            'filiere_id' => $filiere->id,
            'capacite' => $capacite,
            'moyenne_minimale' => 0,
            'statut' => 'active',
        ])->fresh();
    }

    private function candidat(CampagneOrientation $campagne, string $nni, float $moyenne): CandidatOrientation
    {
        return CandidatOrientation::create([
            'campagne_orientation_id' => $campagne->id,
            'type_orientation' => 'bac_licence',
            'nni' => $nni,
            'nom_complet' => "Candidat $nni",
            'moyenne_generale' => $moyenne,
            'annee_obtention' => 2026,
            'statut' => 'soumise',
            'code_suivi' => 'ORI-2026-' . strtoupper($nni),
            'soumise_le' => now(),
        ])->fresh();
    }

    private function choisir(CandidatOrientation $candidat, OffreOrientation $offre, int $ordre): void
    {
        ChoixOrientation::create([
            'candidat_orientation_id' => $candidat->id,
            'offre_orientation_id' => $offre->id,
            'ordre' => $ordre,
        ]);
    }

    public function test_capacite_limitee_retient_les_meilleures_moyennes(): void
    {
        $campagne = $this->campagneFermee();
        $offre = $this->offre($campagne, capacite: 1);

        $fort = $this->candidat($campagne, 'FORT001', 15.5);
        $moyen = $this->candidat($campagne, 'MOYEN01', 12.0);
        $faible = $this->candidat($campagne, 'FAIBLE1', 9.0);

        $this->choisir($fort, $offre, 1);
        $this->choisir($moyen, $offre, 1);
        $this->choisir($faible, $offre, 1);

        $this->service->lancerAffectation($campagne->fresh());

        $this->assertEquals('orientee', Orientation::where('candidat_orientation_id', $fort->id)->value('statut'));
        $this->assertEquals('non_orientee', Orientation::where('candidat_orientation_id', $moyen->id)->value('statut'));
        $this->assertEquals('non_orientee', Orientation::where('candidat_orientation_id', $faible->id)->value('statut'));
        $this->assertEquals(1, Orientation::where('campagne_orientation_id', $campagne->id)->where('statut', 'orientee')->count());
    }

    public function test_candidat_evince_bascule_sur_son_second_choix(): void
    {
        $campagne = $this->campagneFermee();
        $offreA = $this->offre($campagne, capacite: 1, suffix: 'A');
        $offreB = $this->offre($campagne, capacite: 1, suffix: 'B');

        $moyen = $this->candidat($campagne, 'MOYEN02', 12.0);
        $this->choisir($moyen, $offreA, 1);
        $this->choisir($moyen, $offreB, 2);

        $fort = $this->candidat($campagne, 'FORT002', 16.0);
        $this->choisir($fort, $offreA, 1);

        $this->service->lancerAffectation($campagne->fresh());

        $resultatMoyen = Orientation::where('candidat_orientation_id', $moyen->id)->first();
        $resultatFort = Orientation::where('candidat_orientation_id', $fort->id)->first();

        $this->assertEquals('orientee', $resultatFort->statut);
        $this->assertEquals($offreA->id, $resultatFort->offre_orientation_id);

        $this->assertEquals('orientee', $resultatMoyen->statut);
        $this->assertEquals($offreB->id, $resultatMoyen->offre_orientation_id);
        $this->assertEquals(2, $resultatMoyen->ordre_choix);
    }

    public function test_ex_aequo_departage_par_id_croissant(): void
    {
        $campagne = $this->campagneFermee();
        $offre = $this->offre($campagne, capacite: 1);

        $premier = $this->candidat($campagne, 'EXAEQ01', 14.0);
        $second = $this->candidat($campagne, 'EXAEQ02', 14.0);

        $this->choisir($premier, $offre, 1);
        $this->choisir($second, $offre, 1);

        $this->service->lancerAffectation($campagne->fresh());

        $this->assertEquals('orientee', Orientation::where('candidat_orientation_id', $premier->id)->value('statut'));
        $this->assertEquals('non_orientee', Orientation::where('candidat_orientation_id', $second->id)->value('statut'));
    }

    public function test_candidat_sans_voeu_satisfiable_reste_non_oriente(): void
    {
        $campagne = $this->campagneFermee();
        $offre = $this->offre($campagne, capacite: 0);

        $candidat = $this->candidat($campagne, 'SEUL0001', 18.0);
        $this->choisir($candidat, $offre, 1);

        $this->service->lancerAffectation($campagne->fresh());

        $resultat = Orientation::where('candidat_orientation_id', $candidat->id)->first();
        $this->assertEquals('non_orientee', $resultat->statut);
        $this->assertNull($resultat->offre_orientation_id);
    }

    public function test_relancer_affectation_est_idempotent(): void
    {
        $campagne = $this->campagneFermee();
        $offre = $this->offre($campagne, capacite: 1);
        $candidat = $this->candidat($campagne, 'IDEMP001', 12.0);
        $this->choisir($candidat, $offre, 1);

        $this->service->lancerAffectation($campagne->fresh());
        $this->service->lancerAffectation($campagne->fresh());

        $this->assertEquals(1, Orientation::where('campagne_orientation_id', $campagne->id)->count());
    }
}
