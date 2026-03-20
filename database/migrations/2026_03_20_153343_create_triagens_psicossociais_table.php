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
        Schema::create('triagens_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas_psicossociais')->onDelete('cascade');
            $table->foreignId('usuario_triador_id')->constrained('usuarios')->onDelete('restrict');
            $table->text('resumo_caso')->nullable();
            $table->text('sinais_observados')->nullable();
            $table->text('historico_breve')->nullable();
            $table->enum('urgencia', ['baixa', 'media', 'alta', 'critica'])->default('media');
            $table->boolean('risco_identificado')->default(false);
            $table->text('descricao_risco')->nullable();
            $table->enum('nivel_sigilo', ['normal', 'reforcado'])->default('normal');
            $table->enum('decisao', [
                'iniciar_atendimento',
                'observar',
                'encaminhar_externo',
                'devolver_pedagogico',
                'encerrar_sem_atendimento'
            ]);
            $table->text('justificativa_decisao')->nullable();
            $table->text('observacoes')->nullable();
            $table->date('data_triagem');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triagens_psicossociais');
    }
};
