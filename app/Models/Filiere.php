<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Filiere extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'institution_id',
        'code_filiere',
        'nom',
        'niveau',
        'duree_semestres',
        'numero_arrete_autorisation',
        'date_arrete_autorisation',
        'capacite_accueil',
        'statut',
        'accreditation_id',
    ];

    protected $casts = [
        'date_arrete_autorisation' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class, 'accreditation_id', 'id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'filiere_id', 'id');
    }

    public function inscriptionsActives()
    {
        return $this->hasMany(Inscription::class, 'filiere_id', 'id')->where('statut', 'actif');
    }

    public function maquettes()
    {
        return $this->hasMany(Maquette::class, 'filiere_id', 'id');
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'affectation_enseignant', 'filiere_id', 'enseignant_id', 'id', 'id')
            ->withPivot(['institution_id', 'volume_horaire', 'type_contrat', 'annee_universitaire'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeParNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    public function scopeParInstitution($query, $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    // Accesseurs
    public function getNiveauCompletAttribute()
    {
        return match ($this->niveau) {
            'licence' => 'Licence (Bac+3)',
            'master' => 'Master (Bac+5)',
            'doctorat' => 'Doctorat (Bac+8)',
            default => ucfirst($this->niveau)
        };
    }

    public function getNiveauBadgeAttribute()
    {
        return match ($this->niveau) {
            'licence' => [
                'label' => 'Licence',
                'class' => 'bg-blue-100 text-blue-800'
            ],
            'master' => [
                'label' => 'Master',
                'class' => 'bg-purple-100 text-purple-800'
            ],
            'doctorat' => [
                'label' => 'Doctorat',
                'class' => 'bg-red-100 text-red-800'
            ],
            default => [
                'label' => $this->niveau,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function getStatutBadgeAttribute()
    {
        return match ($this->statut) {
            'active' => [
                'label' => 'Active',
                'class' => 'bg-green-100 text-green-800'
            ],
            'inactive' => [
                'label' => 'Inactive',
                'class' => 'bg-red-100 text-red-800'
            ],
            default => [
                'label' => $this->statut,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function getTauxRemplissageAttribute()
    {
        $inscrits = $this->inscriptionsActives()->count();

        if ($this->capacite_accueil == 0) {
            return 0;
        }

        return round(($inscrits / $this->capacite_accueil) * 100, 1);
    }

    public function getPlacesDisponiblesAttribute()
    {
        $inscrits = $this->inscriptionsActives()->count();
        return max(0, $this->capacite_accueil - $inscrits);
    }

    public function getDureeAnneesAttribute()
    {
        return ceil($this->duree_semestres / 2);
    }

    // Méthodes
    public function estValide()
    {
        return $this->statut === 'active' &&
            $this->institution->statut === 'actif' &&
            $this->institution->accreditationActive !== null;
    }

    public function peutInscrire()
    {
        return $this->estValide() &&
            $this->places_disponibles > 0;
    }

    public function getEtudiantsParSemestre($semestre)
    {
        return $this->inscriptionsActives()
            ->where('semestre_courant', $semestre)
            ->with('etudiant')
            ->get();
    }

    // Boot
    protected static function booted()
    {
        static::creating(function ($filiere) {
            if (!$filiere->code_filiere) {
                $filiere->code_filiere = 'FIL-' . date('Y') . '-' . strtoupper(uniqid());
            }
        });
    }
}
