<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomaineLicence extends Model
{
    protected $table = 'domaines_licence';

    protected $fillable = [
        'nom',
        'ordre',
        'statut',
    ];

    public function offres()
    {
        return $this->belongsToMany(OffreOrientation::class, 'offre_orientation_domaine_licence', 'domaine_licence_id', 'offre_orientation_id', 'id', 'id');
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif')->orderBy('ordre');
    }
}
