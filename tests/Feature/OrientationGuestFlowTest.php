<?php

namespace Tests\Feature;

use App\Models\CampagneOrientation;
use App\Models\Filiere;
use App\Models\Institution;
use App\Models\OffreOrientation;
use App\Models\TypeBac;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrientationGuestFlowTest extends TestCase
{
    use RefreshDatabase;

    private function seedTypesBac(): void
    {
        TypeBac::create(['code' => 'SN', 'libelle' => 'SN', 'ordre' => 1, 'statut' => 'actif']);
        TypeBac::create(['code' => 'LM', 'libelle' => 'LM', 'ordre' => 2, 'statut' => 'actif']);
    }

    private function campagneActive(): CampagneOrientation
    {
        return CampagneOrientation::create([
            'type_orientation' => 'bac_licence',
            'nom' => 'Orientation Licence 2026',
            'annee_universitaire' => 2026,
            'date_ouverture' => now()->subDay(),
            'date_fermeture' => now()->addMonth(),
            'nombre_max_choix' => 5,
            'statut' => 'active',
        ])->fresh();
    }

    private function offreCompatible(CampagneOrientation $campagne): OffreOrientation
    {
        $institution = Institution::create([
            'nom' => 'Institut X',
            'code_etablissement' => 'INST-' . uniqid(),
            'ville' => 'Nouakchott',
            'statut' => 'actif',
        ])->fresh();

        $filiere = Filiere::create([
            'institution_id' => $institution->id,
            'code_filiere' => 'FIL-' . uniqid(),
            'nom' => 'Licence Informatique',
            'niveau' => 'licence',
            'duree_semestres' => 6,
            'capacite_accueil' => 50,
            'statut' => 'active',
        ])->fresh();

        $offre = OffreOrientation::create([
            'campagne_orientation_id' => $campagne->id,
            'filiere_id' => $filiere->id,
            'capacite' => 10,
            'moyenne_minimale' => 10,
            'statut' => 'active',
        ])->fresh();

        $offre->typesBac()->sync(TypeBac::where('code', 'SN')->pluck('id'));

        return $offre;
    }

    public function test_parcours_complet_bac_vers_licence(): void
    {
        Storage::fake('public');
        $this->seedTypesBac();
        $campagne = $this->campagneActive();
        $offre = $this->offreCompatible($campagne);
        $typeBacSn = TypeBac::where('code', 'SN')->first();

        // 1. Formulaire accessible
        $this->get(route('public.orientation.formulaire', 'bac-licence'))->assertOk();

        // 2. Soumission du profil
        $response = $this->post(route('public.orientation.formulaire.store', 'bac-licence'), [
            'nni' => '12345678',
            'nom_complet' => 'Ahmed Test',
            'type_bac_id' => $typeBacSn->id,
            'moyenne_generale' => 14.5,
            'annee_obtention' => 2026,
            'telephone' => '+22212345678',
            'email' => 'ahmed@example.com',
            'cni' => UploadedFile::fake()->create('cni.pdf', 100, 'application/pdf'),
        ]);
        $response->assertRedirect(route('public.orientation.offres'));
        $this->assertNotNull(session('orientation_candidat_id'));

        // 3. La liste des offres montre l'offre compatible
        $this->get(route('public.orientation.offres'))
            ->assertOk()
            ->assertSee('Licence Informatique');

        // 4. Ajout au choix
        $this->post(route('public.orientation.offres.choisir', $offre->uuid))
            ->assertRedirect();

        // 5. Récapitulatif affiche le choix
        $this->get(route('public.orientation.recapitulatif'))
            ->assertOk()
            ->assertSee('Licence Informatique');

        // 6. Validation définitive
        $response = $this->post(route('public.orientation.valider'));
        $response->assertRedirect(route('public.orientation.confirmation'));

        $candidat = \App\Models\CandidatOrientation::where('nni', '12345678')->first();
        $this->assertEquals('soumise', $candidat->statut);
        $this->assertNotNull($candidat->code_suivi);

        // 7. Page de confirmation affiche le code
        $this->get(route('public.orientation.confirmation'))
            ->assertOk()
            ->assertSee($candidat->code_suivi);

        // 8. Suivi retrouve le dossier via code + NNI
        $this->post(route('public.orientation.suivi.consulter'), [
            'code_suivi' => $candidat->code_suivi,
            'nni' => '12345678',
        ])->assertOk()->assertSee('Ahmed Test');
    }

    public function test_offre_non_compatible_est_filtree(): void
    {
        $this->seedTypesBac();
        $campagne = $this->campagneActive();
        $this->offreCompatible($campagne); // n'accepte que SN

        $typeBacLm = TypeBac::where('code', 'LM')->first();

        $this->post(route('public.orientation.formulaire.store', 'bac-licence'), [
            'nni' => '87654321',
            'nom_complet' => 'Fatima Test',
            'type_bac_id' => $typeBacLm->id,
            'moyenne_generale' => 15,
            'annee_obtention' => 2026,
        ])->assertRedirect(route('public.orientation.offres'));

        $this->get(route('public.orientation.offres'))
            ->assertOk()
            ->assertSee(__('lang.orientation.offers_empty'));
    }
}
