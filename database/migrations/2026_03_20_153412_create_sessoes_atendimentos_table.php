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
        Schema::create('sessoes_atendimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atendimento_id')->constrained('atendimentos_psicossociais')->onDelete('cascade');
            $table->foreignId('usuario_profissional_id')->constrained('usuarios')->onDelete('restrict');
            $table->foreignId('funcionario_profissional_id')->nullable()->constrained('funcionarios')->onDelete('set null');
            $table->date('data_sessao');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->enum('tipo_sessao', [
                'avaliacao',
                'intervencao',
                'retorno',
                'emergencial',
                'acolhimento',
                'devolutiva',
                'reavaliacao'
            ])->default('intervencao');
            $table->text('objetivo_sessao')->nullable();
            $table->text('relato_sessao')->nullable();
            $table->text('estrategias_utilizadas')->nullable();
            $table->text('comportamento_observado')->nullable();
            $table->text('evolucao_percebida')->nullable();
            $table->text('encaminhamentos_definidos')->nullable();
            $table->boolean('necessita_retorno')->default(true);
            $table->text('proximo_passo')->nullable();
            $table->text('observacoes')->nullable();
            $table->enum('status', ['realizado', 'remarcado', 'faltou', 'cancelado'])->default('realizado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessoes_atendimentos');
    }
};
