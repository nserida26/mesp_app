<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeBac extends Model
{
    protected $table = 'types_bac';

    protected $fillable = [
        'code',
        'libelle',
        'ordre',
        'statut',
    ];

    public function offres()
    {
        return $this->belongsToMany(OffreOrientation::class, 'offre_orientation_type_bac', 'type_bac_id', 'offre_orientation_id', 'id', 'id');
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif')->orderBy('ordre');
    }
}
