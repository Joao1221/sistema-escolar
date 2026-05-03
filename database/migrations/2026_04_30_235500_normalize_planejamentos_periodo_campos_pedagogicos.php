<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planejamentos_periodo', function (Blueprint $table) {
            if (! Schema::hasColumn('planejamentos_periodo', 'tema_gerador')) {
                $table->string('tema_gerador')->nullable()->after('status');
            }

            if (! Schema::hasColumn('planejamentos_periodo', 'referencias')) {
                $table->text('referencias')->nullable()->after('observacoes');
            }
        });
    }

    public function down(): void
    {
        // These columns may already exist from earlier schema adjustments.
    }
};
