<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
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
            $inscription->numero_inscription = 'INS-' . date('Y') . '-' . Str::random(8);

            // Existing schema requires this unique code even though public QR verification is disabled.
            $inscription->qr_code_verification = Str::random(32);
        });
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id', 'id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }

    public function scopeActives($query)
    {
        return $query->where('statut', 'actif');
    }

    public function estValide()
    {
        $institution = $this->filiere->institution;

        if (!$institution->accreditationActive) {
            return false;
        }

        if ($this->filiere->statut !== 'active') {
            return false;
        }

        return true;
    }
}
