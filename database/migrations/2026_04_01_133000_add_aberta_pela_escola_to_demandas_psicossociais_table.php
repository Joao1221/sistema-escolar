<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandas_psicossociais', function (Blueprint $table) {
            if (! Schema::hasColumn('demandas_psicossociais', 'aberta_pela_escola')) {
                $table->boolean('aberta_pela_escola')->default(false)->after('atendimento_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('demandas_psicossociais', function (Blueprint $table) {
            if (Schema::hasColumn('demandas_psicossociais', 'aberta_pela_escola')) {
                $table->dropColumn('aberta_pela_escola');
            }
        });
    }
};
