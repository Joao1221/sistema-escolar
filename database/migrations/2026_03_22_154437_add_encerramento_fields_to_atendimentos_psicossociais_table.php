<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('atendimentos_psicossociais', function (Blueprint $table) {
            $table->dateTime('data_encerramento')->nullable()->after('requer_acompanhamento');
            $table->text('motivo_encerramento')->nullable()->after('data_encerramento');
            $table->text('resumo_encerramento')->nullable()->after('motivo_encerramento');
            $table->text('orientacoes_finais')->nullable()->after('resumo_encerramento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos_psicossociais', function (Blueprint $table) {
            $table->dropColumn(['data_encerramento', 'motivo_encerramento', 'resumo_encerramento', 'orientacoes_finais']);
        });
    }
};
