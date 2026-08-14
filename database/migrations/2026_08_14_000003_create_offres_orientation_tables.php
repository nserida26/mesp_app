<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres_orientation', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('campagne_orientation_id')->constrained('campagnes_orientation')->cascadeOnDelete();
            $table->foreignId('filiere_id')->constrained('filieres')->cascadeOnDelete();
            $table->unsignedInteger('capacite');
            $table->decimal('moyenne_minimale', 5, 2);
            $table->string('statut')->default('active')->index();
            $table->timestamps();

            $table->unique(['campagne_orientation_id', 'filiere_id']);
        });

        Schema::create('offre_orientation_type_bac', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_orientation_id')->constrained('offres_orientation')->cascadeOnDelete();
            $table->foreignId('type_bac_id')->constrained('types_bac')->cascadeOnDelete();
            $table->unique(['offre_orientation_id', 'type_bac_id'], 'offre_type_bac_unique');
        });

        Schema::create('offre_orientation_domaine_licence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_orientation_id')->constrained('offres_orientation')->cascadeOnDelete();
            $table->foreignId('domaine_licence_id')->constrained('domaines_licence')->cascadeOnDelete();
            $table->unique(['offre_orientation_id', 'domaine_licence_id'], 'offre_domaine_licence_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offre_orientation_domaine_licence');
        Schema::dropIfExists('offre_orientation_type_bac');
        Schema::dropIfExists('offres_orientation');
    }
};
