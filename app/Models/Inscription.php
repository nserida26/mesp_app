<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Inscription extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'etudiant_id',
        'filiere_id',
        'numero_inscription',
        'date_inscription',
        'statut',
        'semestre_courant',
        'annee_universitaire',
        'moyenne_generale'
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inscription) {
            // Génération automatique du numéro d'inscription
            $inscription->numero_inscription = 'INS-' . date('Y') . '-' . Str::random(8);

            // Génération du QR code pour vérification
            $inscription->qr_code_verification = Str::random(32);
        });
    }

    // Relations
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id', 'id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }

    // Génération du QR Code
    public function generateQrCode()
    {
        $data = [
            'numero_inscription' => $this->numero_inscription,
            'qr_code' => $this->qr_code_verification,
            'verification_url' => route('public.verify.qr', ['code' => $this->qr_code_verification])
        ];

        return QrCode::size(300)->generate(json_encode($data));
    }

    // Scope pour les inscriptions actives
    public function scopeActives($query)
    {
        return $query->where('statut', 'actif');
    }

    // Vérification de la validité académique
    public function estValide()
    {
        $institution = $this->filiere->institution;

        // Vérifier l'accréditation
        if (!$institution->accreditationActive) {
            return false;
        }

        // Vérifier si la filière est active
        if ($this->filiere->statut !== 'active') {
            return false;
        }

        return true;
    }
}
