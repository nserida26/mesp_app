<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OffreOrientation extends Model
{
    use HasUuids;

    protected $table = 'offres_orientation';

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'campagne_orientation_id',
        'filiere_id',
        'capacite',
        'moyenne_minimale',
        'statut',
    ];

    protected $casts = [
        'moyenne_minimale' => 'decimal:2',
    ];

    public function campagne()
    {
        return $this->belongsTo(CampagneOrientation::class, 'campagne_orientation_id', 'id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }

    public function typesBac()
    {
        return $this->belongsToMany(TypeBac::class, 'offre_orientation_type_bac', 'offre_orientation_id', 'type_bac_id', 'id');
    }

    public function domainesLicence()
    {
        return $this->belongsToMany(DomaineLicence::class, 'offre_orientation_domaine_licence', 'offre_orientation_id', 'domaine_licence_id', 'id');
    }

    public function choix()
    {
        return $this->hasMany(ChoixOrientation::class, 'offre_orientation_id', 'id');
    }

    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }
}
