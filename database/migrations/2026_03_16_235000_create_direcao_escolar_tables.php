<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validacoes_direcao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->morphs('validavel');
            $table->foreignId('usuario_direcao_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('status', 30);
            $table->text('parecer');
            $table->text('observacoes_internas')->nullable();
            $table->timestamp('validado_em');
            $table->timestamps();

            $table->unique(['validavel_type', 'validavel_id'], 'validacoes_direcao_validavel_unique');
            $table->index(['diario_professor_id', 'status'], 'validacoes_direcao_diario_status_idx');
        });

        Schema::create('justificativas_falta_aluno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('frequencia_aula_id')->constrained('frequencias_aula')->cascadeOnDelete();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('usuario_direcao_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('situacao_anterior', 30);
            $table->string('situacao_atual', 30)->default('falta_justificada');
            $table->text('motivo');
            $table->string('documento_comprobatorio')->nullable();
            $table->timestamp('deferida_em');
            $table->timestamps();

            $table->unique('frequencia_aula_id');
            $table->index(['diario_professor_id', 'matricula_id'], 'justificativas_falta_aluno_diario_matricula_idx');
        });

        Schema::create('liberacoes_prazo_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->foreignId('professor_id')->constrained('funcionarios')->cascadeOnDelete();
            $table->foreignId('usuario_direcao_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('tipo_lancamento', 40);
            $table->date('data_limite');
            $table->string('status', 20)->default('ativa');
            $table->string('motivo', 180);
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['diario_professor_id', 'tipo_lancamento'], 'liberacoes_prazo_professor_diario_tipo_idx');
            $table->index(['professor_id', 'status'], 'liberacoes_prazo_professor_professor_status_idx');
        });

        Schema::create('faltas_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas')->cascadeOnDelete();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->cascadeOnDelete();
            $table->foreignId('usuario_registro_id')->constrained('usuarios')->cascadeOnDelete();
            $table->date('data_falta');
            $table->string('turno', 20);
            $table->string('tipo_falta', 30);
            $table->boolean('justificada')->default(false);
            $table->string('motivo', 180);
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['escola_id', 'data_falta'], 'faltas_funcionarios_escola_data_idx');
            $table->index(['funcionario_id', 'data_falta'], 'faltas_funcionarios_funcionario_data_idx');
        });

        Schema::create('fechamentos_letivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas')->cascadeOnDelete();
            $table->unsignedSmallInteger('ano_letivo');
            $table->foreignId('usuario_direcao_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('status', 20);
            $table->text('resumo')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamp('iniciado_em')->nullable();
            $table->timestamp('concluido_em')->nullable();
            $table->timestamps();

            $table->unique(['escola_id', 'ano_letivo']);
            $table->index(['ano_letivo', 'status'], 'fechamentos_letivos_ano_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechamentos_letivos');
        Schema::dropIfExists('faltas_funcionarios');
        Schema::dropIfExists('liberacoes_prazo_professor');
        Schema::dropIfExists('justificativas_falta_aluno');
        Schema::dropIfExists('validacoes_direcao');
    }
};
