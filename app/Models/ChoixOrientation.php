<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChoixOrientation extends Model
{
    protected $table = 'choix_orientation';

    protected $fillable = [
        'candidat_orientation_id',
        'offre_orientation_id',
        'ordre',
    ];

    public function candidat()
    {
        return $this->belongsTo(CandidatOrientation::class, 'candidat_orientation_id', 'id');
    }

    public function offre()
    {
        return $this->belongsTo(OffreOrientation::class, 'offre_orientation_id', 'id');
    }
}
