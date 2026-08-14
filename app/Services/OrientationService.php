<?php

namespace App\Services;

use App\Models\CampagneOrientation;
use App\Models\CandidatOrientation;
use App\Models\ChoixOrientation;
use App\Models\OffreOrientation;
use App\Models\Orientation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrientationService
{
    public function campagneActive(string $typeOrientation): ?CampagneOrientation
    {
        return CampagneOrientation::query()
            ->where('type_orientation', $typeOrientation)
            ->where('statut', 'active')
            ->where('date_ouverture', '<=', now())
            ->where('date_fermeture', '>=', now())
            ->first();
    }

    public function offresEligibles(CandidatOrientation $candidat): Collection
    {
        $query = OffreOrientation::query()
            ->where('campagne_orientation_id', $candidat->campagne_orientation_id)
            ->where('statut', 'active')
            ->where('moyenne_minimale', '<=', $candidat->moyenne_generale)
            ->with(['filiere.institution']);

        if ($candidat->type_orientation === 'bac_licence') {
            $query->whereHas('typesBac', fn ($q) => $q->where('types_bac.id', $candidat->type_bac_id));
        } else {
            $query->whereHas('domainesLicence', fn ($q) => $q->where('domaines_licence.id', $candidat->domaine_licence_id));
        }

        return $query->get();
    }

    public function ajouterChoix(CandidatOrientation $candidat, OffreOrientation $offre): void
    {
        if ($candidat->statut !== 'brouillon') {
            throw ValidationException::withMessages(['choix' => 'Candidature deja validee.']);
        }

        if (!$this->offresEligibles($candidat)->contains('id', $offre->id)) {
            throw ValidationException::withMessages(['choix' => __('lang.orientation.offers_empty')]);
        }

        if ($candidat->choix()->where('offre_orientation_id', $offre->id)->exists()) {
            return;
        }

        $nombreChoix = $candidat->choix()->count();

        if ($nombreChoix >= $candidat->campagne->nombre_max_choix) {
            throw ValidationException::withMessages(['choix' => __('lang.orientation.max_choices_reached')]);
        }

        ChoixOrientation::create([
            'candidat_orientation_id' => $candidat->id,
            'offre_orientation_id' => $offre->id,
            'ordre' => $nombreChoix + 1,
        ]);
    }

    public function retirerChoix(CandidatOrientation $candidat, OffreOrientation $offre): void
    {
        if ($candidat->statut !== 'brouillon') {
            throw ValidationException::withMessages(['choix' => 'Candidature deja validee.']);
        }

        DB::transaction(function () use ($candidat, $offre) {
            $candidat->choix()->where('offre_orientation_id', $offre->id)->delete();

            $restants = $candidat->choix()->orderBy('ordre')->get();
            foreach ($restants as $index => $choix) {
                $choix->update(['ordre' => $index + 1]);
            }
        });
    }

    public function reordonnerChoix(CandidatOrientation $candidat, array $offreIdsOrdonnes): void
    {
        if ($candidat->statut !== 'brouillon') {
            throw ValidationException::withMessages(['choix' => 'Candidature deja validee.']);
        }

        $offreIdsActuels = $candidat->choix()->pluck('offre_orientation_id')->sort()->values();
        $offreIdsOrdonnes = collect($offreIdsOrdonnes)->map(fn ($id) => (int) $id);

        if (!$offreIdsActuels->diff($offreIdsOrdonnes)->isEmpty() || $offreIdsActuels->count() !== $offreIdsOrdonnes->count()) {
            throw ValidationException::withMessages(['choix' => 'Liste de choix invalide.']);
        }

        DB::transaction(function () use ($candidat, $offreIdsOrdonnes) {
            // Passage par des valeurs temporaires hors plage (nombre_max_choix <= 20) pour
            // eviter les collisions avec la contrainte unique (candidat_orientation_id, ordre).
            foreach ($offreIdsOrdonnes->values() as $index => $offreId) {
                $candidat->choix()
                    ->where('offre_orientation_id', $offreId)
                    ->update(['ordre' => 200 + $index]);
            }

            foreach ($offreIdsOrdonnes->values() as $index => $offreId) {
                $candidat->choix()
                    ->where('offre_orientation_id', $offreId)
                    ->update(['ordre' => $index + 1]);
            }
        });
    }

    public function validerDefinitivement(CandidatOrientation $candidat): string
    {
        if ($candidat->statut !== 'brouillon') {
            throw ValidationException::withMessages(['candidat' => 'Candidature deja validee.']);
        }

        if ($candidat->choix()->count() === 0) {
            throw ValidationException::withMessages(['choix' => __('lang.orientation.no_choices_yet')]);
        }

        return DB::transaction(function () use ($candidat) {
            $code = $candidat->genererCodeSuivi();

            $candidat->update([
                'statut' => 'soumise',
                'code_suivi' => $code,
                'soumise_le' => now(),
            ]);

            return $code;
        });
    }

    public function lancerAffectation(CampagneOrientation $campagne): void
    {
        if ($campagne->statut !== 'fermee') {
            throw ValidationException::withMessages(['campagne' => __('lang.orientation.affectation_requires_closed_campaign')]);
        }

        DB::transaction(function () use ($campagne) {
            $campagne = CampagneOrientation::query()->whereKey($campagne->getKey())->lockForUpdate()->first();

            Orientation::where('campagne_orientation_id', $campagne->id)->delete();

            $candidats = CandidatOrientation::query()
                ->where('campagne_orientation_id', $campagne->id)
                ->where('statut', 'soumise')
                ->with(['choix' => fn ($q) => $q->orderBy('ordre')])
                ->get()
                ->keyBy('id');

            if ($candidats->isEmpty()) {
                return;
            }

            $offres = OffreOrientation::query()
                ->where('campagne_orientation_id', $campagne->id)
                ->get()
                ->keyBy('id');

            $placesRestantes = $offres->mapWithKeys(fn ($offre) => [$offre->id => $offre->capacite])->all();

            // index du prochain choix (0-based) que chaque candidat va tenter
            $indexChoixCourant = $candidats->mapWithKeys(fn ($c) => [$c->id => 0])->all();

            // affectation courante : offre_id tenue par le candidat, ou null
            $affectationCourante = $candidats->mapWithKeys(fn ($c) => [$c->id => null])->all();

            $file = $candidats->keys()->all();

            while (!empty($file)) {
                $candidatId = array_shift($file);
                $candidat = $candidats[$candidatId];
                $choixListe = $candidat->choix;

                if ($indexChoixCourant[$candidatId] >= $choixListe->count()) {
                    continue;
                }

                $choix = $choixListe[$indexChoixCourant[$candidatId]];
                $indexChoixCourant[$candidatId]++;
                $offreId = $choix->offre_orientation_id;

                if (!isset($offres[$offreId])) {
                    $file[] = $candidatId;
                    continue;
                }

                if ($placesRestantes[$offreId] > 0) {
                    $affectationCourante[$candidatId] = ['offre_id' => $offreId, 'ordre_choix' => $choix->ordre];
                    $placesRestantes[$offreId]--;
                    continue;
                }

                $pireCandidatId = $this->pireCandidatSurOffre($affectationCourante, $candidats, $offreId);

                if ($pireCandidatId !== null && $this->estMeilleur($candidat, $candidats[$pireCandidatId])) {
                    $affectationCourante[$pireCandidatId] = null;
                    $file[] = $pireCandidatId;
                    $affectationCourante[$candidatId] = ['offre_id' => $offreId, 'ordre_choix' => $choix->ordre];
                } else {
                    $file[] = $candidatId;
                }
            }

            $maintenant = now();

            foreach ($candidats as $candidatId => $candidat) {
                $affectation = $affectationCourante[$candidatId];

                Orientation::create([
                    'campagne_orientation_id' => $campagne->id,
                    'candidat_orientation_id' => $candidatId,
                    'offre_orientation_id' => $affectation['offre_id'] ?? null,
                    'ordre_choix' => $affectation['ordre_choix'] ?? null,
                    'moyenne' => $candidat->moyenne_generale,
                    'statut' => $affectation ? 'orientee' : 'non_orientee',
                    'date_orientation' => $maintenant,
                ]);
            }
        });
    }

    private function pireCandidatSurOffre(array $affectationCourante, Collection $candidats, int $offreId): ?int
    {
        $pireId = null;
        $pireMoyenne = null;

        foreach ($affectationCourante as $candidatId => $affectation) {
            if (!$affectation || $affectation['offre_id'] !== $offreId) {
                continue;
            }

            $moyenne = $candidats[$candidatId]->moyenne_generale;

            if ($pireMoyenne === null || $moyenne < $pireMoyenne || ($moyenne == $pireMoyenne && $candidatId > $pireId)) {
                $pireId = $candidatId;
                $pireMoyenne = $moyenne;
            }
        }

        return $pireId;
    }

    private function estMeilleur(CandidatOrientation $candidat, CandidatOrientation $autre): bool
    {
        if ((float) $candidat->moyenne_generale !== (float) $autre->moyenne_generale) {
            return (float) $candidat->moyenne_generale > (float) $autre->moyenne_generale;
        }

        return $candidat->id < $autre->id;
    }
}
