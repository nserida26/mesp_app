<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nom');
            $table->string('code_etablissement')->unique();
            $table->text('adresse')->nullable();
            $table->string('ville')->index();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('statut')->default('actif')->index();
            $table->string('logo_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->string('code_filiere')->unique();
            $table->string('nom');
            $table->string('niveau')->index();
            $table->unsignedTinyInteger('duree_semestres')->default(6);
            $table->string('numero_arrete_autorisation')->nullable();
            $table->date('date_arrete_autorisation')->nullable();
            $table->unsignedInteger('capacite_accueil')->default(0);
            $table->string('statut')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nom')->index();
            $table->string('prenom')->index();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->text('numero_national')->nullable();
            $table->string('hash_numero_bac')->unique();
            $table->string('serie_bac')->nullable();
            $table->unsignedSmallInteger('annee_bac')->nullable();
            $table->string('mention_bac')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('telephone')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->foreignId('filiere_id')->constrained('filieres')->cascadeOnDelete();
            $table->string('numero_inscription')->unique();
            $table->date('date_inscription');
            $table->string('statut')->default('actif')->index();
            $table->unsignedTinyInteger('semestre_courant')->default(1);
            $table->unsignedSmallInteger('annee_universitaire')->index();
            $table->decimal('moyenne_generale', 5, 2)->nullable();
            $table->string('qr_code_verification')->unique();
            $table->timestamps();
        });

        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nom')->index();
            $table->string('prenom')->index();
            $table->text('numero_national')->nullable();
            $table->string('numero_accreditation')->unique();
            $table->string('grade');
            $table->string('specialite')->nullable()->index();
            $table->string('email')->nullable()->unique();
            $table->string('telephone')->nullable();
            $table->string('statut')->default('actif')->index();
            $table->timestamps();
        });

        Schema::create('accreditations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->string('numero_arrete')->unique();
            $table->date('date_arrete');
            $table->date('date_debut');
            $table->date('date_fin')->index();
            $table->string('type')->index();
            $table->string('statut')->default('active')->index();
            $table->string('fichier_arrete_path')->nullable();
            $table->timestamps();
        });

        Schema::create('affectation_enseignant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained('enseignants')->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('filiere_id')->constrained('filieres')->cascadeOnDelete();
            $table->unsignedInteger('volume_horaire')->default(0);
            $table->string('type_contrat')->default('vacataire')->index();
            $table->unsignedSmallInteger('annee_universitaire')->index();
            $table->timestamps();
            $table->unique(['enseignant_id', 'institution_id', 'filiere_id', 'annee_universitaire'], 'affectation_unique');
        });

        Schema::create('calendriers_academiques', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->unsignedSmallInteger('annee_universitaire')->index();
            $table->date('debut_semestre_1');
            $table->date('fin_semestre_1');
            $table->date('debut_examens_s1');
            $table->date('fin_examens_s1');
            $table->date('debut_vacances_hiver')->nullable();
            $table->date('fin_vacances_hiver')->nullable();
            $table->date('debut_semestre_2');
            $table->date('fin_semestre_2');
            $table->date('debut_examens_s2');
            $table->date('fin_examens_s2');
            $table->date('debut_vacances_ete')->nullable();
            $table->date('fin_vacances_ete')->nullable();
            $table->string('statut')->default('brouillon')->index();
            $table->timestamps();
            //$table->unique(['institution_id', 'annee_universitaire']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendriers_academiques');
        Schema::dropIfExists('affectation_enseignant');
        Schema::dropIfExists('accreditations');
        Schema::dropIfExists('enseignants');
        Schema::dropIfExists('inscriptions');
        Schema::dropIfExists('etudiants');
        Schema::dropIfExists('filieres');
        Schema::dropIfExists('institutions');
    }
};
