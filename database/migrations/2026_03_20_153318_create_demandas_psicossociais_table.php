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
        Schema::create('demandas_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas')->onDelete('restrict');
            $table->foreignId('usuario_registro_id')->constrained('usuarios')->onDelete('restrict');
            $table->foreignId('profissional_responsavel_id')->nullable()->constrained('funcionarios')->onDelete('set null');
            $table->enum('tipo_atendimento', ['psicologia', 'psicopedagogia', 'psicossocial'])->default('psicologia');
            $table->enum('origem_demanda', [
                'coordenacao',
                'direcao',
                'professor',
                'familia',
                'triagem_interna',
                'demanda_espontanea',
                'outro'
            ]);
            $table->enum('tipo_publico', ['aluno', 'professor', 'funcionario', 'responsavel']);
            $table->foreignId('aluno_id')->nullable()->constrained('alunos')->onDelete('restrict');
            $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios')->onDelete('restrict');
            $table->string('responsavel_nome')->nullable();
            $table->string('responsavel_telefone')->nullable();
            $table->string('responsavel_vinculo')->nullable();
            $table->text('motivo_inicial')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media');
            $table->enum('status', [
                'aberta',
                'em_triagem',
                'em_atendimento',
                'encaminhada',
                'observacao',
                'encerrada'
            ])->default('aberta');
            $table->date('data_solicitacao');
            $table->text('observacoes')->nullable();
            $table->boolean('encaminhado_para_atendimento')->default(false);
            $table->foreignId('atendimento_id')->nullable()->constrained('atendimentos_psicossociais')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandas_psicossociais');
    }
};
