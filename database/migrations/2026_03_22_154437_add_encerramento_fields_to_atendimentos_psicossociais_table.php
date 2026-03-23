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
            if (! Schema::hasColumn('atendimentos_psicossociais', 'data_encerramento')) {
                $table->dateTime('data_encerramento')->nullable()->after('requer_acompanhamento');
            }

            if (! Schema::hasColumn('atendimentos_psicossociais', 'motivo_encerramento')) {
                $table->text('motivo_encerramento')->nullable()->after('data_encerramento');
            }

            if (! Schema::hasColumn('atendimentos_psicossociais', 'resumo_encerramento')) {
                $table->text('resumo_encerramento')->nullable()->after('motivo_encerramento');
            }

            if (! Schema::hasColumn('atendimentos_psicossociais', 'orientacoes_finais')) {
                $table->text('orientacoes_finais')->nullable()->after('resumo_encerramento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos_psicossociais', function (Blueprint $table) {
            $colunas = collect([
                'data_encerramento',
                'motivo_encerramento',
                'resumo_encerramento',
                'orientacoes_finais',
            ])->filter(fn (string $coluna) => Schema::hasColumn('atendimentos_psicossociais', $coluna))->all();

            if ($colunas !== []) {
                $table->dropColumn($colunas);
            }
        });
    }
};
