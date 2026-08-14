<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidats_orientation', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('campagne_orientation_id')->constrained('campagnes_orientation')->cascadeOnDelete();
            $table->string('type_orientation')->index();

            $table->string('nni');
            $table->string('nom_complet');
            $table->string('telephone', 20)->nullable();
            $table->string('email')->nullable();

            $table->foreignId('type_bac_id')->nullable()->constrained('types_bac')->nullOnDelete();
            $table->foreignId('domaine_licence_id')->nullable()->constrained('domaines_licence')->nullOnDelete();

            $table->decimal('moyenne_generale', 5, 2)->nullable();
            $table->unsignedSmallInteger('annee_obtention')->nullable();

            $table->string('cni_path')->nullable();
            $table->string('releve_notes_path')->nullable();
            $table->string('diplome_path')->nullable();

            $table->string('statut')->default('brouillon')->index();
            $table->string('code_suivi')->nullable()->unique();
            $table->timestamp('soumise_le')->nullable();
            $table->string('ip_soumission', 45)->nullable();

            $table->timestamps();

            $table->unique(['campagne_orientation_id', 'nni'], 'candidat_orientation_campagne_nni_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidats_orientation');
    }
};
