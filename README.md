# 🇲🇷 MESRS — Plateforme Enseignement Supérieur
## Kit Laravel + Blade — Setup complet

---

## Prérequis
- PHP 8.2+
- Laravel 11.x
- Node.js 20+ (pour Vite)
- MySQL / MariaDB

---

## 1. Installation des packages

```bash
composer require mcamara/laravel-localization spatie/laravel-permission

# Optionnel : Laravel Breeze pour les controlleurs auth
composer require laravel/breeze --dev
php artisan breeze:install blade
```

---

## 2. Publier les configs

```bash
# mcamara/laravel-localization
php artisan vendor:publish \
  --provider="Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider"

# spatie/laravel-permission
php artisan vendor:publish \
  --provider="Spatie\Permission\PermissionServiceProvider"

php artisan migrate
```

---

## 3. Configuration app.php

```php
// config/app.php
'locale'   => 'fr',
'fallback_locale' => 'fr',
'available_locales' => ['fr', 'ar'],
'contact_email' => env('CONTACT_EMAIL', 'orientation@mesrs.gov.mr'),
'website'  => env('CONTACT_WEBSITE', 'www.mesrs.gov.mr'),
'show_nav_classements' => true,
```

---

## 4. Enregistrer le middleware SetLocale

```php
// bootstrap/app.php (Laravel 11)
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        \App\Http\Middleware\SetLocale::class,
    ]);
})
```

---

## 5. Ajouter HasRoles au modèle User

```php
// app/Models/User.php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    // ...
    protected $fillable = ['name', 'email', 'password', 'institution_id'];
}
```

---

## 6. Seeder — Rôles & permissions

```bash
# Dans database/seeders/DatabaseSeeder.php, ajouter :
$this->call(RolesAndPermissionsSeeder::class);

# Puis :
php artisan db:seed --class=RolesAndPermissionsSeeder
```

**Rôles créés :**
| Rôle         | Description                          |
|--------------|--------------------------------------|
| `admin`      | Accès total, gestion du système      |
| `ministere`  | Lecture/export + gestion accréditations |
| `institution`| Gestion de sa propre institution     |
| `enseignant` | Consultation profil + calendrier     |
| `etudiant`   | Consultation + vérification          |
| `public`     | Vérification publique uniquement     |

---

## 7. Routes — mcamara

```php
// routes/web.php — wrapper mcamara (optionnel si URLs préfixées)
Route::group([
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect'],
], function () {
    // vos routes ici
});
```

---

## 8. CSS (Vite)

```bash
npm install
npm run dev    # développement
npm run build  # production
```

Le fichier `resources/css/app.css` est le CSS générique complet.
Importez-le dans `resources/js/app.js` :

```js
import '../css/app.css';
```

---

## 9. Structure des fichiers

```
resources/
├── css/
│   └── app.css                    ← CSS générique complet
├── views/
│   ├── layouts/
│   │   └── app.blade.php          ← Layout principal (nav + footer)
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   ├── forgot-password.blade.php
│   │   └── reset-password.blade.php
│   ├── pages/
│   │   ├── home.blade.php
│   │   └── dashboard.blade.php
│   └── admin/
│       └── users/
│           ├── index.blade.php
│           └── form.blade.php

lang/
├── fr/
│   ├── app.php, auth.php, nav.php, home.php, roles.php, lang.php
└── ar/
    ├── app.php, auth.php, nav.php, home.php, roles.php, lang.php

app/Http/
├── Controllers/
│   ├── LanguageController.php
│   ├── HomeController.php
│   ├── DashboardController.php
│   └── Admin/UserController.php
└── Middleware/
    └── SetLocale.php

database/seeders/
└── RolesAndPermissionsSeeder.php

routes/
└── web.php

config/
└── laravellocalization.php
```

---

## 10. Compte admin par défaut

```
Email    : admin@mesrs.gov.mr
Password : Admin@MESRS2026!
```
⚠️ Changez ce mot de passe immédiatement après la première connexion.

---

## Variables d'environnement recommandées

```env
APP_NAME="Plateforme MESRS"
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_URL=https://plateforme.mesrs.gov.mr
CONTACT_EMAIL=orientation@mesrs.gov.mr
CONTACT_WEBSITE=www.mesrs.gov.mr
```
