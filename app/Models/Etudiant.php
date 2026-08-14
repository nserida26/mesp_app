<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Etudiant extends Model
{
    use SoftDeletes, HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'numero_national',
        'hash_numero_bac',
        'serie_bac',
        'annee_bac',
        'mention_bac',
        'email',
        'telephone',
        'institution_id',
        'statut_validation',
        'cree_par_id',
        'valide_par_id',
        'valide_le',
        'motif_rejet',
    ];

    protected $hidden = ['numero_national'];

    protected $casts = [
        'date_naissance' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'valide_le' => 'datetime',
    ];

    public function setNumeroNationalAttribute($value)
    {
        $this->attributes['numero_national'] = $value ? trim($value) : null;
    }

    // Mutator pour hasher le numéro de bac
    public function setHashNumeroBacAttribute($value)
    {
        $this->attributes['hash_numero_bac'] = hash('sha256', $value);
    }

    // Relations
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }

    public function creePar()
    {
        return $this->belongsTo(User::class, 'cree_par_id', 'id');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par_id', 'id');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut_validation', 'en_attente');
    }

    public function scopeValidees($query)
    {
        return $query->where('statut_validation', 'valide');
    }

    public function scopeRejetees($query)
    {
        return $query->where('statut_validation', 'rejete');
    }

    public function getStatutValidationBadgeAttribute()
    {
        return match ($this->statut_validation) {
            'valide' => [
                'label' => 'Valide',
                'class' => 'bg-green-100 text-green-800'
            ],
            'en_attente' => [
                'label' => 'En attente',
                'class' => 'bg-yellow-100 text-yellow-800'
            ],
            'rejete' => [
                'label' => 'Rejete',
                'class' => 'bg-red-100 text-red-800'
            ],
            default => [
                'label' => $this->statut_validation,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'etudiant_id', 'id');
    }

    public function inscriptionActive()
    {
        return $this->hasOne(Inscription::class, 'etudiant_id', 'id')
            ->where('statut', 'actif')
            ->latest('date_inscription');
    }

    // Méthode pour la vérification publique
    public static function verifyByNumeroBac($numeroBac)
    {
        $hash = hash('sha256', $numeroBac);

        $etudiant = self::where('hash_numero_bac', $hash)
            ->with(['inscriptionActive.filiere.institution'])
            ->first();

        return self::verificationResult($etudiant);
    }

    public static function verifyByNumeroNational($numeroNational)
    {
        $etudiant = self::where('numero_national', trim($numeroNational))
            ->with(['inscriptionActive.filiere.institution'])
            ->first();

        return self::verificationResult($etudiant);
    }

    public static function uuidsMatchingNumeroNational($numeroNational)
    {
        $numeroNational = trim($numeroNational);

        if ($numeroNational === '') {
            return collect();
        }

        return self::where('numero_national', $numeroNational)->pluck('uuid');
    }

    private static function verificationResult(?Etudiant $etudiant): ?array
    {
        if (!$etudiant || !$etudiant->inscriptionActive) {
            return null;
        }

        return [
            'status' => 'valide',
            'type' => 'student',
            'niveau' => $etudiant->inscriptionActive->filiere->niveau,
            'filiere' => $etudiant->inscriptionActive->filiere->nom,
            'institution' => $etudiant->inscriptionActive->filiere->institution->nom,
            'annee' => $etudiant->inscriptionActive->annee_universitaire,
            'semestre' => $etudiant->inscriptionActive->semestre_courant,
            'numero_inscription' => $etudiant->inscriptionActive->numero_inscription,
        ];
    }

    // Scope pour recherche sécurisée
    public function scopeSearch($query, $term)
    {
        $matchingUuids = self::uuidsMatchingNumeroNational($term);

        return $query->where(function ($q) use ($term, $matchingUuids) {
            $q->where('nom', 'like', "%{$term}%")
                ->orWhere('prenom', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%");

            if ($matchingUuids->isNotEmpty()) {
                $q->orWhereIn('uuid', $matchingUuids);
            }
        });
    }
}
