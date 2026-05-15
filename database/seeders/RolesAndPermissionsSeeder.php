<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Define all permissions ──────────────────────────────────────
        $permissions = [

            // Institutions
            'view institutions',
            'create institutions',
            'edit institutions',
            'delete institutions',
            'export institutions',

            // Filières
            'view filieres',
            'create filieres',
            'edit filieres',
            'delete filieres',

            // Étudiants
            'view etudiants',
            'create etudiants',
            'edit etudiants',
            'delete etudiants',
            'export etudiants',
            'verify etudiants',         // public verification endpoint

            // Enseignants
            'view enseignants',
            'create enseignants',
            'edit enseignants',
            'delete enseignants',

            // Accréditations
            'view accreditations',
            'create accreditations',
            'edit accreditations',
            'delete accreditations',

            // Calendrier académique
            'view calendrier',
            'create calendrier',
            'edit calendrier',
            'delete calendrier',

            // Rapports & statistiques
            'view statistics',
            'export statistics',

            // Administration système
            'manage users',
            'manage roles',
            'access-dashboard',
            'view audit-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ── Define roles & assign permissions ───────────────────────────

        // 1. Super Admin — full access (bypass via Spatie's gate)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->syncPermissions(Permission::all());

        // 2. Ministère — read/export everything, manage accreditations
        $roleMinistere = Role::firstOrCreate(['name' => 'ministere', 'guard_name' => 'web']);
        $roleMinistere->syncPermissions([
            'view institutions', 'export institutions',
            'view filieres',
            'view etudiants', 'export etudiants', 'verify etudiants',
            'view enseignants',
            'view accreditations', 'create accreditations', 'edit accreditations',
            'view calendrier', 'edit calendrier',
            'view statistics', 'export statistics',
            'access-dashboard',
            'view audit-logs',
        ]);

        // 3. Institution — manages only its own data (enforced in policies)
        $roleInstitution = Role::firstOrCreate(['name' => 'institution', 'guard_name' => 'web']);
        $roleInstitution->syncPermissions([
            'view institutions', 'edit institutions',
            'view filieres', 'create filieres', 'edit filieres',
            'view etudiants', 'create etudiants', 'edit etudiants',
            'view enseignants', 'create enseignants', 'edit enseignants',
            'view calendrier', 'create calendrier', 'edit calendrier',
            'access-dashboard',
        ]);

        // 4. Enseignant — read-only profile + calendar
        $roleEnseignant = Role::firstOrCreate(['name' => 'enseignant', 'guard_name' => 'web']);
        $roleEnseignant->syncPermissions([
            'view institutions',
            'view filieres',
            'view calendrier',
            'access-dashboard',
        ]);

        // 5. Étudiant — view own record + public verification
        $roleEtudiant = Role::firstOrCreate(['name' => 'etudiant', 'guard_name' => 'web']);
        $roleEtudiant->syncPermissions([
            'view institutions',
            'view filieres',
            'verify etudiants',
            'access-dashboard',
        ]);

        // 6. Public — public verification only (unauthenticated via controller logic)
        $rolePublic = Role::firstOrCreate(['name' => 'public', 'guard_name' => 'web']);
        $rolePublic->syncPermissions([
            'verify etudiants',
        ]);

        // ── Create default admin user ───────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@mesrs.gov.mr'],
            [
                'name'     => 'Administrateur MESRS',
                'password' => bcrypt('Admin@MESRS2026!'),
            ]
        );
        $admin->assignRole('admin');

        $this->command->info('✅ Roles, permissions and default admin created.');
        $this->command->table(
            ['Role', 'Permissions count'],
            [
                ['admin',       Permission::count()],
                ['ministere',   $roleMinistere->permissions()->count()],
                ['institution', $roleInstitution->permissions()->count()],
                ['enseignant',  $roleEnseignant->permissions()->count()],
                ['etudiant',    $roleEtudiant->permissions()->count()],
                ['public',      $rolePublic->permissions()->count()],
            ]
        );
    }
}
