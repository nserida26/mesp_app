<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'institution_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    // Méthodes de vérification des rôles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMinistere()
    {
        return $this->role === 'ministere';
    }

    public function isInstitution()
    {
        return $this->role === 'institution';
    }

    public function isPublic()
    {
        return $this->role === 'public';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    // Accesseurs
    public function getRoleBadgeAttribute()
    {
        return match ($this->role) {
            'admin' => [
                'label' => 'Administrateur',
                'class' => 'bg-red-100 text-red-800'
            ],
            'ministere' => [
                'label' => 'Ministère',
                'class' => 'bg-blue-100 text-blue-800'
            ],
            'institution' => [
                'label' => 'Institution',
                'class' => 'bg-green-100 text-green-800'
            ],
            'public' => [
                'label' => 'Public',
                'class' => 'bg-gray-100 text-gray-800'
            ],
            default => [
                'label' => $this->role,
                'class' => 'bg-gray-100 text-gray-800'
            ]
        };
    }

    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            'admin' => 'Administrateur',
            'ministere' => 'Agent Ministériel',
            'institution' => 'Établissement',
            'public' => 'Utilisateur Public',
            default => $this->role
        };
    }

    // Permissions
    public function canAccessDashboard()
    {
        return in_array($this->role, ['admin', 'ministere', 'institution']);
    }

    public function canManageInstitutions()
    {
        return in_array($this->role, ['admin', 'ministere']);
    }

    public function canManageAccreditations()
    {
        return in_array($this->role, ['admin', 'ministere']);
    }

    public function canManageUsers()
    {
        return $this->role === 'admin';
    }

    public function canViewStats()
    {
        return in_array($this->role, ['admin', 'ministere', 'institution']);
    }

    public function canExportData()
    {
        return in_array($this->role, ['admin', 'ministere']);
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeMinistere($query)
    {
        return $query->where('role', 'ministere');
    }

    public function scopeInstitutions($query)
    {
        return $query->where('role', 'institution');
    }

    public function scopePublic($query)
    {
        return $query->where('role', 'public');
    }

    // Boot
    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->role) {
                $user->role = 'public';
            }
        });
    }
}
