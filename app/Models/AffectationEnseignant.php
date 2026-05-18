<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AffectationEnseignant extends Pivot
{
    protected $table = 'affectation_enseignant';

    protected $fillable = [
        'enseignant_id',
        'institution_id',
        'filiere_id',
        'volume_horaire',
        'type_contrat',
        'annee_universitaire'
    ];

    protected $casts = [
        'volume_horaire' => 'integer',
        'annee_universitaire' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'enseignant_id', 'id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }

    // Accesseurs
    public function getTypeContratBadgeAttribute()
    {
        return match ($this->type_contrat) {
            'permanent' => [
                'label' => 'Permanent',
                'class' => 'bg-green-100 text-green-800'
            ],
            'vacataire' => [
                'label' => 'Vacataire',
                'class' => 'bg-orange-100 text-orange-800'
            ],
            default => [
                'label' => $this->type_contrat,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    // Scopes
    public function scopeParAnnee($query, $annee)
    {
        return $query->where('annee_universitaire', $annee);
    }

    public function scopePermanents($query)
    {
        return $query->where('type_contrat', 'permanent');
    }

    public function scopeVacataires($query)
    {
        return $query->where('type_contrat', 'vacataire');
    }
}
