<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\AccreditationController;
use App\Http\Controllers\CalendrierController;
use App\Http\Controllers\VerificationController;

/*
|--------------------------------------------------------------------------
| Language Switcher
| Works with mcamara/laravel-localization session-based locale
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/pub');

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang.switch')
    ->where('locale', '[a-z]{2}');

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

// Espace Public
Route::prefix('pub')->name('public.')->group(function () {

    // Page d'accueil du portail public
    Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');

    // Vérification d'un étudiant
    Route::get('/verify', [App\Http\Controllers\Public\VerificationController::class, 'index'])->name('verify');
    Route::post('/verify', [App\Http\Controllers\Public\VerificationController::class, 'check'])->name('verify.check');
    // Liste des institutions accréditées (publique)
    Route::get('/institutions', [App\Http\Controllers\Public\InstitutionController::class, 'index'])->name('institutions');
    Route::get('/institutions/{uuid}', [App\Http\Controllers\Public\InstitutionController::class, 'show'])->name('institutions.show');
    Route::get('/etudiants', [App\Http\Controllers\Public\EtudiantController::class, 'index'])->name('etudiants');
    Route::get('/enseignants', [App\Http\Controllers\Public\EnseignantController::class, 'index'])->name('enseignants');

    // Liste des filières autorisées
    Route::get('/filieres', [App\Http\Controllers\Public\FiliereController::class, 'index'])->name('filieres');
    Route::get('/filieres/{uuid}', [App\Http\Controllers\Public\FiliereController::class, 'show'])->name('filieres.show');

    // Statistiques publiques
    Route::get('/statistiques', [App\Http\Controllers\Public\StatistiqueController::class, 'index'])->name('statistiques');
});
/*
|--------------------------------------------------------------------------
| Authentication routes (Laravel Breeze / custom)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [ResetPasswordController::class, 'reset'])->name('password.store');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated routes — require login
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (any authenticated user with access-dashboard permission)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('can:access-dashboard')
        ->name('dashboard');

    // Profile
    Route::get('/profile',   [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    /*
    |----------------------------------------------------------------------
    | Institutions
    |----------------------------------------------------------------------
    */
    Route::middleware('can:view institutions')->group(function () {
        Route::resource('institutions', InstitutionController::class)->except(['destroy']);
        Route::delete('/institutions/{institution}', [InstitutionController::class, 'destroy'])
            ->middleware('can:delete institutions')
            ->name('institutions.destroy');
        Route::get('/institutions/export', [InstitutionController::class, 'export'])
            ->middleware('can:export institutions')
            ->name('institutions.export');
    });

    /*
    |----------------------------------------------------------------------
    | Filières
    |----------------------------------------------------------------------
    */
    Route::middleware('can:view filieres')->group(function () {
        Route::resource('filieres', FiliereController::class)->except(['destroy']);
        Route::delete('/filieres/{filiere}', [FiliereController::class, 'destroy'])
            ->middleware('can:delete filieres')
            ->name('filieres.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Étudiants
    |----------------------------------------------------------------------
    */
    Route::middleware('can:view etudiants')->group(function () {
        Route::resource('etudiants', EtudiantController::class)->except(['destroy']);
        Route::delete('/etudiants/{etudiant}', [EtudiantController::class, 'destroy'])
            ->middleware('can:delete etudiants')
            ->name('etudiants.destroy');
        Route::get('/etudiants/export', [EtudiantController::class, 'export'])
            ->middleware('can:export etudiants')
            ->name('etudiants.export');
    });

    /*
    |----------------------------------------------------------------------
    | Enseignants
    |----------------------------------------------------------------------
    */
    Route::middleware('can:view enseignants')->group(function () {
        Route::resource('enseignants', EnseignantController::class)->except(['destroy']);
        Route::delete('/enseignants/{enseignant}', [EnseignantController::class, 'destroy'])
            ->middleware('can:delete enseignants')
            ->name('enseignants.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Accréditations
    |----------------------------------------------------------------------
    */
    Route::middleware('can:view accreditations')->group(function () {
        Route::resource('accreditations', AccreditationController::class)->except(['destroy']);
        Route::delete('/accreditations/{accreditation}', [AccreditationController::class, 'destroy'])
            ->middleware('can:delete accreditations')
            ->name('accreditations.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Calendrier Académique
    |----------------------------------------------------------------------
    */
    Route::middleware('can:view calendrier')->group(function () {
        Route::resource('calendrier', CalendrierController::class)->except(['destroy']);
        Route::delete('/calendrier/{calendrier}', [CalendrierController::class, 'destroy'])
            ->middleware('can:delete calendrier')
            ->name('calendrier.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Admin only — Users & Roles management
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/imports',      [\App\Http\Controllers\Admin\ImportController::class, 'index'])->name('imports.index');
        Route::post('/imports',     [\App\Http\Controllers\Admin\ImportController::class, 'store'])->name('imports.store');
        Route::resource('users',       \App\Http\Controllers\Admin\UserController::class);
        Route::resource('roles',       \App\Http\Controllers\Admin\RoleController::class);
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
        Route::get('/audit-logs',      [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])
            ->middleware('can:view audit-logs')
            ->name('audit-logs');
        Route::get('/{resource}', [\App\Http\Controllers\Admin\ResourceController::class, 'index'])->name('resources.index');
        Route::get('/{resource}/create', [\App\Http\Controllers\Admin\ResourceController::class, 'create'])->name('resources.create');
        Route::post('/{resource}', [\App\Http\Controllers\Admin\ResourceController::class, 'store'])->name('resources.store');
        Route::get('/{resource}/{id}', [\App\Http\Controllers\Admin\ResourceController::class, 'show'])->name('resources.show');
        Route::get('/{resource}/{id}/edit', [\App\Http\Controllers\Admin\ResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/{resource}/{id}', [\App\Http\Controllers\Admin\ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/{resource}/{id}', [\App\Http\Controllers\Admin\ResourceController::class, 'destroy'])->name('resources.destroy');
    });
});
