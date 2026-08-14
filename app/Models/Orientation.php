<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orientation extends Model
{
    protected $table = 'orientations';

    protected $fillable = [
        'campagne_orientation_id',
        'candidat_orientation_id',
        'offre_orientation_id',
        'ordre_choix',
        'moyenne',
        'statut',
        'date_orientation',
    ];

    protected $casts = [
        'moyenne' => 'decimal:2',
        'date_orientation' => 'datetime',
    ];

    public function campagne()
    {
        return $this->belongsTo(CampagneOrientation::class, 'campagne_orientation_id', 'id');
    }

    public function candidat()
    {
        return $this->belongsTo(CandidatOrientation::class, 'candidat_orientation_id', 'id');
    }

    public function offre()
    {
        return $this->belongsTo(OffreOrientation::class, 'offre_orientation_id', 'id');
    }

    public function scopeOrientees($query)
    {
        return $query->where('statut', 'orientee');
    }
}
