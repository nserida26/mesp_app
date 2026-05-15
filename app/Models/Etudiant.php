<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

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
        $this->attributes['numero_national'] = Crypt::encryptString($value);
    }

    // Accesseur pour décrypter
    public function getNumeroNationalAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
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

        if (!$etudiant || !$etudiant->inscriptionActive) {
            return null;
        }

        return [
            'status' => 'valide',
            'niveau' => $etudiant->inscriptionActive->filiere->niveau,
            'filiere' => $etudiant->inscriptionActive->filiere->nom,
            'institution' => $etudiant->inscriptionActive->filiere->institution->nom,
            'annee' => $etudiant->inscriptionActive->annee_universitaire,
            'semestre' => $etudiant->inscriptionActive->semestre_courant,
            'qr_code' => $etudiant->inscriptionActive->qr_code_verification
        ];
    }

    // Scope pour recherche sécurisée
    public function scopeSearch($query, $term)
    {
        return $query->where('nom', 'like', "%{$term}%")
            ->orWhere('prenom', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%");
    }
}
