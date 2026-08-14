<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CandidatOrientation extends Model
{
    use HasUuids;

    protected $table = 'candidats_orientation';

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $hidden = [
        'nni',
    ];

    protected $fillable = [
        'campagne_orientation_id',
        'type_orientation',
        'nni',
        'nom_complet',
        'telephone',
        'email',
        'type_bac_id',
        'domaine_licence_id',
        'moyenne_generale',
        'annee_obtention',
        'cni_path',
        'releve_notes_path',
        'diplome_path',
        'statut',
        'code_suivi',
        'soumise_le',
        'ip_soumission',
    ];

    protected $casts = [
        'moyenne_generale' => 'decimal:2',
        'soumise_le' => 'datetime',
    ];

    public function campagne()
    {
        return $this->belongsTo(CampagneOrientation::class, 'campagne_orientation_id', 'id');
    }

    public function typeBac()
    {
        return $this->belongsTo(TypeBac::class, 'type_bac_id', 'id');
    }

    public function domaineLicence()
    {
        return $this->belongsTo(DomaineLicence::class, 'domaine_licence_id', 'id');
    }

    public function choix()
    {
        return $this->hasMany(ChoixOrientation::class, 'candidat_orientation_id', 'id')->orderBy('ordre');
    }

    public function orientation()
    {
        return $this->hasOne(Orientation::class, 'candidat_orientation_id', 'id');
    }

    public function scopeSoumis($query)
    {
        return $query->where('statut', 'soumise');
    }

    public function genererCodeSuivi(): string
    {
        $annee = $this->campagne?->annee_universitaire ?? date('Y');

        do {
            $code = 'ORI-' . $annee . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (self::where('code_suivi', $code)->exists());

        return $code;
    }
}
