<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allow accreditations to optionally belong to a filiere
        Schema::table('accreditations', function (Blueprint $table) {
            $table->unsignedBigInteger('filiere_id')->nullable()->after('institution_id');
            $table->foreign('filiere_id')->references('id')->on('filieres')->nullOnDelete();
            $table->unsignedBigInteger('institution_id')->nullable()->change();
        });

        // Add accreditation_id FK to institutions (points to the primary/active accreditation)
        Schema::table('institutions', function (Blueprint $table) {
            $table->unsignedBigInteger('accreditation_id')->nullable()->after('logo_path');
            $table->foreign('accreditation_id')->references('id')->on('accreditations')->nullOnDelete();
        });

        // Add accreditation_id FK to filieres
        Schema::table('filieres', function (Blueprint $table) {
            $table->unsignedBigInteger('accreditation_id')->nullable()->after('statut');
            $table->foreign('accreditation_id')->references('id')->on('accreditations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            $table->dropForeign(['accreditation_id']);
            $table->dropColumn('accreditation_id');
        });

        Schema::table('institutions', function (Blueprint $table) {
            $table->dropForeign(['accreditation_id']);
            $table->dropColumn('accreditation_id');
        });

        Schema::table('accreditations', function (Blueprint $table) {
            $table->dropForeign(['filiere_id']);
            $table->dropColumn('filiere_id');
        });
    }
};
