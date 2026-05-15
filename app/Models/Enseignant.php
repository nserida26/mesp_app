<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Enseignant extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nom',
        'prenom',
        'numero_accreditation',
        'grade',
        'specialite',
        'email',
        'telephone',
        'statut'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'affectation_enseignant')
            ->withPivot(['filiere_id', 'volume_horaire', 'type_contrat', 'annee_universitaire'])
            ->withTimestamps();
    }

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'affectation_enseignant')
            ->withPivot(['institution_id', 'volume_horaire', 'type_contrat', 'annee_universitaire'])
            ->withTimestamps();
    }

    public function affectationsActuelles()
    {
        $anneeActuelle = date('Y');

        return $this->hasMany(AffectationEnseignant::class)
            ->where('annee_universitaire', $anneeActuelle);
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }

    public function scopeParSpecialite($query, $specialite)
    {
        return $query->where('specialite', $specialite);
    }

    public function scopeVacataires($query)
    {
        return $query->whereHas('affectationsActuelles', function ($q) {
            $q->where('type_contrat', 'vacataire');
        });
    }

    public function scopePermanents($query)
    {
        return $query->whereHas('affectationsActuelles', function ($q) {
            $q->where('type_contrat', 'permanent');
        });
    }

    // Accesseurs
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getGradeCompletAttribute()
    {
        return match ($this->grade) {
            'assistant' => 'Assistant',
            'maitre_assistant' => 'Maître Assistant',
            'maitre_conference' => 'Maître de Conférences',
            'professeur' => 'Professeur',
            default => ucfirst(str_replace('_', ' ', $this->grade))
        };
    }

    public function getGradeBadgeAttribute()
    {
        return match ($this->grade) {
            'professeur' => [
                'label' => $this->grade_complet,
                'class' => 'bg-purple-100 text-purple-800'
            ],
            'maitre_conference' => [
                'label' => $this->grade_complet,
                'class' => 'bg-blue-100 text-blue-800'
            ],
            'maitre_assistant' => [
                'label' => $this->grade_complet,
                'class' => 'bg-green-100 text-green-800'
            ],
            'assistant' => [
                'label' => $this->grade_complet,
                'class' => 'bg-yellow-100 text-yellow-800'
            ],
            default => [
                'label' => $this->grade_complet,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function getStatutBadgeAttribute()
    {
        return match ($this->statut) {
            'actif' => [
                'label' => 'Actif',
                'class' => 'bg-green-100 text-green-800'
            ],
            'inactif' => [
                'label' => 'Inactif',
                'class' => 'bg-red-100 text-red-800'
            ],
            default => [
                'label' => $this->statut,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function getVolumeHoraireTotalAttribute()
    {
        return $this->affectationsActuelles()->sum('volume_horaire');
    }

    public function getNombreInstitutionsAttribute()
    {
        return $this->affectationsActuelles()
            ->distinct('institution_id')
            ->count('institution_id');
    }

    public function getNombreFilieresAttribute()
    {
        return $this->affectationsActuelles()
            ->distinct('filiere_id')
            ->count('filiere_id');
    }

    // Méthodes
    public function estSurcharge($seuilHeures = 200)
    {
        return $this->volume_horaire_total > $seuilHeures;
    }

    public function affecterInstitution($institutionId, $filiereId, $donnees = [])
    {
        $donnees = array_merge([
            'annee_universitaire' => date('Y'),
            'type_contrat' => 'vacataire',
            'volume_horaire' => 0
        ], $donnees);

        return $this->institutions()->attach($institutionId, array_merge($donnees, [
            'filiere_id' => $filiereId
        ]));
    }

    public function retirerAffectation($institutionId, $filiereId, $annee = null)
    {
        $annee = $annee ?? date('Y');

        return $this->institutions()
            ->wherePivot('filiere_id', $filiereId)
            ->wherePivot('annee_universitaire', $annee)
            ->detach($institutionId);
    }

    public function getAffectationsParAnnee($annee)
    {
        return $this->affectationsActuelles()
            ->where('annee_universitaire', $annee)
            ->with(['institution', 'filiere'])
            ->get();
    }

    public function estDisponible($date)
    {
        // Vérifier si l'enseignant a atteint son quota d'heures
        if ($this->estSurcharge()) {
            return false;
        }

        // Vérifier le nombre d'institutions (max 3 pour les vacataires)
        $affectations = $this->affectationsActuelles()
            ->where('type_contrat', 'vacataire')
            ->get();

        if ($affectations->count() >= 3) {
            return false;
        }

        return true;
    }

    // Boot
    protected static function booted()
    {
        static::creating(function ($enseignant) {
            if (!$enseignant->numero_accreditation) {
                $enseignant->numero_accreditation = 'ENS-' . date('Y') . '-' . strtoupper(uniqid());
            }
        });
    }
}
