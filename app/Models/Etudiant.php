<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

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
        'telephone'
    ];

    protected $hidden = ['numero_national'];

    protected $casts = [
        'date_naissance' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Mutator pour crypter automatiquement le numéro national
    public function setNumeroNationalAttribute($value)
    {
        $this->attributes['numero_national'] = $value ? Crypt::encryptString(trim($value)) : null;
    }

    // Accesseur pour décrypter
    public function getNumeroNationalAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }

    // Mutator pour hasher le numéro de bac
    public function setHashNumeroBacAttribute($value)
    {
        $this->attributes['hash_numero_bac'] = hash('sha256', $value);
    }

    // Relations
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
        $numeroNational = trim($numeroNational);

        $etudiant = self::whereNotNull('numero_national')
            ->with(['inscriptionActive.filiere.institution'])
            ->get()
            ->first(fn (Etudiant $etudiant) => hash_equals($numeroNational, trim((string) $etudiant->numero_national)));

        return self::verificationResult($etudiant);
    }

    public static function uuidsMatchingNumeroNational($numeroNational)
    {
        $numeroNational = trim($numeroNational);

        if ($numeroNational === '') {
            return collect();
        }

        return self::whereNotNull('numero_national')
            ->get(['uuid', 'numero_national'])
            ->filter(fn (Etudiant $etudiant) => hash_equals($numeroNational, trim((string) $etudiant->numero_national)))
            ->pluck('uuid');
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
