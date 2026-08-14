<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CampagneOrientation extends Model
{
    use HasUuids;

    protected $table = 'campagnes_orientation';

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'type_orientation',
        'nom',
        'annee_universitaire',
        'date_ouverture',
        'date_fermeture',
        'date_publication_resultats',
        'nombre_max_choix',
        'cni_requis',
        'releve_notes_requis',
        'diplome_requis',
        'statut',
        'cree_par_id',
    ];

    protected $casts = [
        'date_ouverture' => 'datetime',
        'date_fermeture' => 'datetime',
        'date_publication_resultats' => 'datetime',
        'cni_requis' => 'boolean',
        'releve_notes_requis' => 'boolean',
        'diplome_requis' => 'boolean',
    ];

    public function creePar()
    {
        return $this->belongsTo(User::class, 'cree_par_id', 'id');
    }

    public function offres()
    {
        return $this->hasMany(OffreOrientation::class, 'campagne_orientation_id', 'id');
    }

    public function candidats()
    {
        return $this->hasMany(CandidatOrientation::class, 'campagne_orientation_id', 'id');
    }

    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeParType($query, string $type)
    {
        return $query->where('type_orientation', $type);
    }

    public function estOuverte(): bool
    {
        return $this->statut === 'active'
            && now()->between($this->date_ouverture, $this->date_fermeture);
    }

    public function getStatutBadgeAttribute()
    {
        return match ($this->statut) {
            'active' => ['label' => 'Active', 'class' => 'bg-green-100 text-green-800'],
            'fermee' => ['label' => 'Fermee', 'class' => 'bg-gray-100 text-gray-800'],
            'resultats_publies' => ['label' => 'Resultats publies', 'class' => 'bg-blue-100 text-blue-800'],
            'archivee' => ['label' => 'Archivee', 'class' => 'bg-gray-100 text-gray-500'],
            default => ['label' => 'Brouillon', 'class' => 'bg-yellow-100 text-yellow-800'],
        };
    }
}
