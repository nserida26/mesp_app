<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Accreditation extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'institution_id',
        'numero_arrete',
        'date_arrete',
        'date_debut',
        'date_fin',
        'type',
        'statut',
        'fichier_arrete_path'
    ];

    protected $casts = [
        'date_arrete' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('statut', 'active')
            ->where('date_fin', '>=', now());
    }

    public function scopeExpirees($query)
    {
        return $query->where('date_fin', '<', now());
    }

    public function scopeProcheExpiration($query, $jours = 90)
    {
        $dateLimite = Carbon::now()->addDays($jours);
        return $query->where('statut', 'active')
            ->whereBetween('date_fin', [now(), $dateLimite]);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accesseurs
    public function getDureeValiditeAttribute()
    {
        return $this->date_debut->diffInMonths($this->date_fin);
    }

    public function getJoursRestantsAttribute()
    {
        if ($this->statut !== 'active') {
            return 0;
        }

        return now()->diffInDays($this->date_fin, false);
    }

    public function getEstValideAttribute()
    {
        return $this->statut === 'active' &&
            $this->date_debut <= now() &&
            $this->date_fin >= now();
    }

    public function getStatutBadgeAttribute()
    {
        if (!$this->estValide && $this->statut === 'active') {
            return [
                'label' => 'Expirée',
                'class' => 'bg-red-100 text-red-800'
            ];
        }

        return match ($this->statut) {
            'active' => [
                'label' => 'Active',
                'class' => 'bg-green-100 text-green-800'
            ],
            'expiree' => [
                'label' => 'Expirée',
                'class' => 'bg-red-100 text-red-800'
            ],
            'suspendue' => [
                'label' => 'Suspendue',
                'class' => 'bg-yellow-100 text-yellow-800'
            ],
            default => [
                'label' => $this->statut,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function getTypeBadgeAttribute()
    {
        return match ($this->type) {
            'creation' => [
                'label' => 'Création',
                'class' => 'bg-blue-100 text-blue-800'
            ],
            'renouvellement' => [
                'label' => 'Renouvellement',
                'class' => 'bg-purple-100 text-purple-800'
            ],
            default => [
                'label' => $this->type,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    // Méthodes
    public function estValidePourDate($date)
    {
        return $this->date_debut <= $date && $this->date_fin >= $date;
    }

    public function renouveler($dateFin, $numeroArrete)
    {
        $this->update(['statut' => 'expiree']);

        return self::create([
            'institution_id' => $this->institution_id,
            'numero_arrete' => $numeroArrete,
            'date_arrete' => now(),
            'date_debut' => $this->date_fin->addDay(),
            'date_fin' => $dateFin,
            'type' => 'renouvellement',
            'statut' => 'active'
        ]);
    }

    public function suspendre()
    {
        $this->update(['statut' => 'suspendue']);

        // Logger l'action
        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->log('Accréditation suspendue');
    }

    // Boot
    protected static function booted()
    {
        static::creating(function ($accreditation) {
            if (!$accreditation->numero_arrete) {
                $accreditation->numero_arrete = 'ACC-' . date('Y') . '-' . strtoupper(uniqid());
            }
        });

        static::created(function ($accreditation) {
            // Vérifier et mettre à jour le statut de l'institution
            $institution = $accreditation->institution;
            if ($institution->accreditationActive) {
                $institution->update(['statut' => 'actif']);
            }
        });
    }
}
