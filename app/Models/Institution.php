<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Institution extends Model
{
    use SoftDeletes, HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nom',
        'code_etablissement',
        'adresse',
        'ville',
        'telephone',
        'email',
        'statut',
        'logo_path'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relations
    public function accreditations()
    {
        return $this->hasMany(Accreditation::class, 'institution_id', 'id');
    }

    public function accreditationActive()
    {
        return $this->hasOne(Accreditation::class, 'institution_id', 'id')
            ->where('statut', 'active')
            ->where('date_fin', '>=', now())
            ->latest('date_debut');
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'institution_id', 'id');
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'affectation_enseignant')
            ->withPivot(['filiere_id', 'volume_horaire', 'type_contrat', 'annee_universitaire'])
            ->withTimestamps();
    }

    public function calendriers()
    {
        return $this->hasMany(CalendrierAcademique::class, 'institution_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'institution_id', 'id');
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeByVille($query, $ville)
    {
        return $query->where('ville', 'like', "%{$ville}%");
    }

    // Accesseurs
    public function getTotalEtudiantsAttribute()
    {
        return Inscription::whereHas('filiere', function ($q) {
            $q->where('institution_id', $this->id);
        })->where('statut', 'actif')->count();
    }

    public function getTotalFilieresActivesAttribute()
    {
        return $this->filieres()->where('statut', 'active')->count();
    }
}
