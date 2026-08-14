<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('choix_orientation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_orientation_id')->constrained('candidats_orientation')->cascadeOnDelete();
            $table->foreignId('offre_orientation_id')->constrained('offres_orientation')->cascadeOnDelete();
            $table->unsignedTinyInteger('ordre');
            $table->timestamps();

            $table->unique(['candidat_orientation_id', 'offre_orientation_id'], 'choix_orientation_candidat_offre_unique');
            $table->unique(['candidat_orientation_id', 'ordre'], 'choix_orientation_candidat_ordre_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choix_orientation');
    }
};
