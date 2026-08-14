<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orientations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_orientation_id')->constrained('campagnes_orientation')->cascadeOnDelete();
            $table->foreignId('candidat_orientation_id')->constrained('candidats_orientation')->cascadeOnDelete();
            $table->foreignId('offre_orientation_id')->nullable()->constrained('offres_orientation')->nullOnDelete();
            $table->unsignedTinyInteger('ordre_choix')->nullable();
            $table->decimal('moyenne', 5, 2);
            $table->string('statut')->default('orientee')->index();
            $table->timestamp('date_orientation');
            $table->timestamps();

            $table->unique('candidat_orientation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orientations');
    }
};
