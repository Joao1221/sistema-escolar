<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atendidos_externos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('aluno_id')->nullable()->constrained('alunos')->nullOnDelete();
            $table->string('nome');
            $table->string('tipo_vinculo', 30);
            $table->string('cpf', 20)->nullable();
            $table->string('telefone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->index(['escola_id', 'tipo_vinculo']);
        });

        Schema::create('atendimentos_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('usuario_registro_id')->constrained('usuarios');
            $table->foreignId('profissional_responsavel_id')->nullable()->constrained('funcionarios')->nullOnDelete();
            $table->morphs('atendivel');
            $table->string('tipo_publico', 20);
            $table->string('tipo_atendimento', 30);
            $table->string('natureza', 30);
            $table->string('status', 20);
            $table->dateTime('data_agendada');
            $table->dateTime('data_realizacao')->nullable();
            $table->string('local_atendimento')->nullable();
            $table->text('motivo_demanda');
            $table->text('resumo_sigiloso')->nullable();
            $table->text('observacoes_restritas')->nullable();
            $table->string('nivel_sigilo', 20)->default('muito_restrito');
            $table->boolean('requer_acompanhamento')->default(false);
            $table->timestamps();

            $table->index(['escola_id', 'status', 'data_agendada'], 'psicossocial_escola_status_data_idx');
            $table->index(['escola_id', 'tipo_publico'], 'psicossocial_escola_publico_idx');
        });

        Schema::create('planos_intervencao_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atendimento_psicossocial_id');
            $table->foreign('atendimento_psicossocial_id', 'plano_interv_psico_atend_fk')
                ->references('id')
                ->on('atendimentos_psicossociais')
                ->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->text('objetivo_geral');
            $table->text('objetivos_especificos')->nullable();
            $table->text('estrategias');
            $table->text('responsaveis_execucao')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->string('status', 20)->default('ativo');
            $table->text('observacoes_sigilosas')->nullable();
            $table->timestamps();
        });

        Schema::create('encaminhamentos_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atendimento_psicossocial_id');
            $table->foreign('atendimento_psicossocial_id', 'encam_psico_atend_fk')
                ->references('id')
                ->on('atendimentos_psicossociais')
                ->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->string('tipo', 20);
            $table->string('destino');
            $table->string('profissional_destino')->nullable();
            $table->string('instituicao_destino')->nullable();
            $table->text('motivo');
            $table->text('orientacoes_sigilosas')->nullable();
            $table->string('status', 20)->default('emitido');
            $table->date('data_encaminhamento');
            $table->date('retorno_previsto_em')->nullable();
            $table->timestamps();
        });

        Schema::create('casos_disciplinares_sigilosos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atendimento_psicossocial_id')->nullable();
            $table->foreign('atendimento_psicossocial_id', 'caso_sig_atend_fk')
                ->references('id')
                ->on('atendimentos_psicossociais')
                ->nullOnDelete();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('aluno_id')->nullable()->constrained('alunos')->nullOnDelete();
            $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->date('data_ocorrencia');
            $table->string('titulo');
            $table->text('descricao_sigilosa');
            $table->text('medidas_adotadas')->nullable();
            $table->string('status', 20)->default('aberto');
            $table->timestamps();

            $table->index(['escola_id', 'data_ocorrencia'], 'caso_sigiloso_escola_data_idx');
        });

        Schema::create('relatorios_tecnicos_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atendimento_psicossocial_id')->nullable();
            $table->foreign('atendimento_psicossocial_id', 'rel_tec_psico_atend_fk')
                ->references('id')
                ->on('atendimentos_psicossociais')
                ->nullOnDelete();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('usuario_emissor_id')->constrained('usuarios');
            $table->string('tipo_relatorio', 30);
            $table->string('titulo');
            $table->text('conteudo_sigiloso');
            $table->date('data_emissao');
            $table->text('observacoes_restritas')->nullable();
            $table->timestamps();

            $table->index(['escola_id', 'tipo_relatorio', 'data_emissao'], 'relatorio_psicossocial_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relatorios_tecnicos_psicossociais');
        Schema::dropIfExists('casos_disciplinares_sigilosos');
        Schema::dropIfExists('encaminhamentos_psicossociais');
        Schema::dropIfExists('planos_intervencao_psicossociais');
        Schema::dropIfExists('atendimentos_psicossociais');
        Schema::dropIfExists('atendidos_externos');
    }
};
