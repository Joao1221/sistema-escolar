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
        Schema::create('reavaliacoes_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atendimento_id')->constrained('atendimentos_psicossociais')->onDelete('cascade');
            $table->foreignId('usuario_responsavel_id')->constrained('usuarios')->onDelete('restrict');
            $table->date('data_reavaliacao');
            $table->text('progresso_observado')->nullable();
            $table->text('dificuldades_persistentes')->nullable();
            $table->text('ajuste_plano')->nullable();
            $table->enum('frequencia_nova', ['semanal', 'quinzenal', 'mensal', 'outra'])->nullable();
            $table->enum('decisao', [
                'manter_plano',
                'ajustar_plano',
                'suspender',
                'encaminhar',
                'encerrar'
            ]);
            $table->text('justificativa')->nullable();
            $table->date('proxima_reavaliacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reavaliacoes_psicossociais');
    }
};
