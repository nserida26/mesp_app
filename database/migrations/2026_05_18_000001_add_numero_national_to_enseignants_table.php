<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            if (!Schema::hasColumn('enseignants', 'numero_national')) {
                $table->text('numero_national')->nullable()->after('prenom');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            if (Schema::hasColumn('enseignants', 'numero_national')) {
                $table->dropColumn('numero_national');
            }
        });
    }
};
