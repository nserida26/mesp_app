<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendrierAcademique extends Model
{
    use HasUuids;

    protected $table = 'calendriers_academiques';

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'institution_id',
        'annee_universitaire',
        'debut_semestre_1',
        'fin_semestre_1',
        'debut_examens_s1',
        'fin_examens_s1',
        'debut_vacances_hiver',
        'fin_vacances_hiver',
        'debut_semestre_2',
        'fin_semestre_2',
        'debut_examens_s2',
        'fin_examens_s2',
        'debut_vacances_ete',
        'fin_vacances_ete',
        'statut'
    ];

    protected $casts = [
        'annee_universitaire' => 'integer',
        'debut_semestre_1' => 'date',
        'fin_semestre_1' => 'date',
        'debut_examens_s1' => 'date',
        'fin_examens_s1' => 'date',
        'debut_vacances_hiver' => 'date',
        'fin_vacances_hiver' => 'date',
        'debut_semestre_2' => 'date',
        'fin_semestre_2' => 'date',
        'debut_examens_s2' => 'date',
        'fin_examens_s2' => 'date',
        'debut_vacances_ete' => 'date',
        'fin_vacances_ete' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }

    // Scopes
    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopePublies($query)
    {
        return $query->where('statut', 'publie');
    }

    public function scopeParAnnee($query, $annee)
    {
        return $query->where('annee_universitaire', $annee);
    }

    public function scopeActuel($query)
    {
        $anneeActuelle = date('Y');
        return $query->where('annee_universitaire', $anneeActuelle);
    }

    // Accesseurs
    public function getDureeSemestre1Attribute()
    {
        return $this->debut_semestre_1->diffInWeeks($this->fin_semestre_1);
    }

    public function getDureeSemestre2Attribute()
    {
        return $this->debut_semestre_2->diffInWeeks($this->fin_semestre_2);
    }

    public function getPeriodeExamensS1Attribute()
    {
        return $this->debut_examens_s1->format('d/m/Y') . ' - ' .
            $this->fin_examens_s1->format('d/m/Y');
    }

    public function getPeriodeExamensS2Attribute()
    {
        return $this->debut_examens_s2->format('d/m/Y') . ' - ' .
            $this->fin_examens_s2->format('d/m/Y');
    }

    public function getStatutBadgeAttribute()
    {
        return match ($this->statut) {
            'brouillon' => [
                'label' => 'Brouillon',
                'class' => 'bg-gray-100 text-gray-800'
            ],
            'valide' => [
                'label' => 'Validé',
                'class' => 'bg-blue-100 text-blue-800'
            ],
            'publie' => [
                'label' => 'Publié',
                'class' => 'bg-green-100 text-green-800'
            ],
            default => [
                'label' => $this->statut,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function getSemestreActuelAttribute()
    {
        $aujourdhui = Carbon::now();

        if ($aujourdhui->between($this->debut_semestre_1, $this->fin_semestre_1)) {
            return [
                'semestre' => 1,
                'type' => 'Cours',
                'debut' => $this->debut_semestre_1,
                'fin' => $this->fin_semestre_1
            ];
        }

        if ($aujourdhui->between($this->debut_examens_s1, $this->fin_examens_s1)) {
            return [
                'semestre' => 1,
                'type' => 'Examens',
                'debut' => $this->debut_examens_s1,
                'fin' => $this->fin_examens_s1
            ];
        }

        if ($aujourdhui->between($this->debut_semestre_2, $this->fin_semestre_2)) {
            return [
                'semestre' => 2,
                'type' => 'Cours',
                'debut' => $this->debut_semestre_2,
                'fin' => $this->fin_semestre_2
            ];
        }

        if ($aujourdhui->between($this->debut_examens_s2, $this->fin_examens_s2)) {
            return [
                'semestre' => 2,
                'type' => 'Examens',
                'debut' => $this->debut_examens_s2,
                'fin' => $this->fin_examens_s2
            ];
        }

        return null;
    }

    public function getJoursRestantsSemestreAttribute()
    {
        $semestre = $this->semestre_actuel;

        if (!$semestre) {
            return 0;
        }

        return Carbon::now()->diffInDays($semestre['fin'], false);
    }

    // Méthodes de validation
    public function estPeriodeCours($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();

        return $date->between($this->debut_semestre_1, $this->fin_semestre_1) ||
            $date->between($this->debut_semestre_2, $this->fin_semestre_2);
    }

    public function estPeriodeExamens($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();

        return $date->between($this->debut_examens_s1, $this->fin_examens_s1) ||
            $date->between($this->debut_examens_s2, $this->fin_examens_s2);
    }

    public function estPeriodeVacances($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();

        if ($this->debut_vacances_hiver && $this->fin_vacances_hiver) {
            if ($date->between($this->debut_vacances_hiver, $this->fin_vacances_hiver)) {
                return 'Hiver';
            }
        }

        if ($this->debut_vacances_ete && $this->fin_vacances_ete) {
            if ($date->between($this->debut_vacances_ete, $this->fin_vacances_ete)) {
                return 'Été';
            }
        }

        return false;
    }

    public function valider()
    {
        $this->update(['statut' => 'valide']);
    }

    public function publier()
    {
        $this->update(['statut' => 'publie']);
    }

    public function estConformeCalendrierNational($calendrierNational)
    {
        // Vérifier que les dates respectent les périodes minimales nationales
        $conformites = [];

        // Semestre 1 doit avoir au moins 14 semaines de cours
        if ($this->duree_semestre_1 < 14) {
            $conformites[] = "Semestre 1 trop court (min: 14 semaines)";
        }

        // Semestre 2 doit avoir au moins 14 semaines de cours
        if ($this->duree_semestre_2 < 14) {
            $conformites[] = "Semestre 2 trop court (min: 14 semaines)";
        }

        // Période d'examens S1 minimum 1 semaine
        $dureeExamensS1 = $this->debut_examens_s1->diffInWeeks($this->fin_examens_s1);
        if ($dureeExamensS1 < 1) {
            $conformites[] = "Période d'examens S1 trop courte (min: 1 semaine)";
        }

        return [
            'conforme' => empty($conformites),
            'anomalies' => $conformites
        ];
    }

    public function genererEvenements()
    {
        $evenements = [];

        // Semestre 1
        $evenements[] = [
            'titre' => 'Début Semestre 1',
            'date' => $this->debut_semestre_1,
            'type' => 'rentree'
        ];

        $evenements[] = [
            'titre' => 'Examens Semestre 1',
            'date_debut' => $this->debut_examens_s1,
            'date_fin' => $this->fin_examens_s1,
            'type' => 'examens'
        ];

        // Vacances d'hiver
        if ($this->debut_vacances_hiver) {
            $evenements[] = [
                'titre' => 'Vacances d\'hiver',
                'date_debut' => $this->debut_vacances_hiver,
                'date_fin' => $this->fin_vacances_hiver,
                'type' => 'vacances'
            ];
        }

        // Semestre 2
        $evenements[] = [
            'titre' => 'Début Semestre 2',
            'date' => $this->debut_semestre_2,
            'type' => 'rentree'
        ];

        $evenements[] = [
            'titre' => 'Examens Semestre 2',
            'date_debut' => $this->debut_examens_s2,
            'date_fin' => $this->fin_examens_s2,
            'type' => 'examens'
        ];

        // Vacances d'été
        if ($this->debut_vacances_ete) {
            $evenements[] = [
                'titre' => 'Vacances d\'été',
                'date_debut' => $this->debut_vacances_ete,
                'date_fin' => $this->fin_vacances_ete,
                'type' => 'vacances'
            ];
        }

        return $evenements;
    }

    // Boot
    protected static function booted()
    {
        static::saving(function ($calendrier) {
            // Validation des dates
            if ($calendrier->debut_semestre_1->gte($calendrier->fin_semestre_1)) {
                throw new \Exception('La date de fin du semestre 1 doit être postérieure à la date de début');
            }

            if ($calendrier->debut_examens_s1->lt($calendrier->fin_semestre_1)) {
                throw new \Exception('Les examens S1 doivent commencer après la fin des cours S1');
            }

            if ($calendrier->debut_semestre_2->lte($calendrier->fin_examens_s1)) {
                throw new \Exception('Le semestre 2 doit commencer après la fin des examens S1');
            }
        });
    }
}
