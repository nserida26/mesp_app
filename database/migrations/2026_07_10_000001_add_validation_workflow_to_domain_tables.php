<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->after('uuid')->constrained('institutions')->nullOnDelete();
        });

        Schema::table('etudiants', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->after('uuid')->constrained('institutions')->nullOnDelete();
        });

        foreach (['enseignants', 'etudiants', 'filieres'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('statut_validation')->default('valide')->index();
                $table->foreignId('cree_par_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('valide_par_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('valide_le')->nullable();
                $table->text('motif_rejet')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['enseignants', 'etudiants', 'filieres'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropConstrainedForeignId('cree_par_id');
                $table->dropConstrainedForeignId('valide_par_id');
                $table->dropColumn(['statut_validation', 'valide_le', 'motif_rejet']);
            });
        }

        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('institution_id');
        });

        Schema::table('enseignants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('institution_id');
        });
    }
};
