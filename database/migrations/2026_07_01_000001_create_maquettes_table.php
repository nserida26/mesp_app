<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maquettes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('filiere_id')->constrained('filieres')->cascadeOnDelete();
            $table->string('titre');
            $table->string('niveau')->index();
            $table->text('description')->nullable();
            $table->string('fichier_path')->nullable();
            $table->timestamps();

            $table->index(['filiere_id', 'niveau']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maquettes');
    }
};
