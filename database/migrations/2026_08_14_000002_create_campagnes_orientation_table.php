<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campagnes_orientation', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('type_orientation')->index();
            $table->string('nom');
            $table->unsignedSmallInteger('annee_universitaire')->index();
            $table->dateTime('date_ouverture');
            $table->dateTime('date_fermeture');
            $table->dateTime('date_publication_resultats')->nullable();
            $table->unsignedTinyInteger('nombre_max_choix')->default(5);
            $table->boolean('cni_requis')->default(false);
            $table->boolean('releve_notes_requis')->default(false);
            $table->boolean('diplome_requis')->default(false);
            $table->string('statut')->default('brouillon')->index();
            $table->foreignId('cree_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['type_orientation', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagnes_orientation');
    }
};
