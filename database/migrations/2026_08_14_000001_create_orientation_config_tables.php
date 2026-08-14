<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_bac', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('libelle', 150);
            $table->unsignedSmallInteger('ordre')->nullable();
            $table->string('statut')->default('actif')->index();
            $table->timestamps();
        });

        Schema::create('domaines_licence', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150)->unique();
            $table->unsignedSmallInteger('ordre')->nullable();
            $table->string('statut')->default('actif')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domaines_licence');
        Schema::dropIfExists('types_bac');
    }
};
