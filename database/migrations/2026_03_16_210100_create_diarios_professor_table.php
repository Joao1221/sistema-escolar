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
        Schema::create('diarios_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas')->cascadeOnDelete();
            $table->foreignId('turma_id')->constrained('turmas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
            $table->foreignId('professor_id')->constrained('funcionarios')->cascadeOnDelete();
            $table->unsignedSmallInteger('ano_letivo');
            $table->string('periodo_tipo', 30);
            $table->string('periodo_referencia', 30);
            $table->string('situacao', 30)->default('em_andamento');
            $table->text('observacoes_gerais')->nullable();
            $table->timestamps();

            $table->unique(
                ['turma_id', 'disciplina_id', 'professor_id', 'ano_letivo', 'periodo_tipo', 'periodo_referencia'],
                'diario_professor_unico'
            );
            $table->index(['escola_id', 'ano_letivo'], 'diario_professor_escola_ano_idx');
            $table->index(['professor_id', 'periodo_tipo', 'periodo_referencia'], 'diario_professor_prof_periodo_idx');
        });

        Schema::create('planejamentos_anuais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->string('tema_gerador')->nullable();
            $table->text('objetivos_gerais');
            $table->text('competencias_habilidades')->nullable();
            $table->text('estrategias_metodologicas')->nullable();
            $table->text('criterios_avaliacao')->nullable();
            $table->text('cronograma_previsto')->nullable();
            $table->text('referencias')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique('diario_professor_id');
        });

        Schema::create('planejamentos_semanais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->date('data_inicio_semana');
            $table->date('data_fim_semana');
            $table->text('objetivos_semana');
            $table->text('conteudos_previstos');
            $table->text('estrategias')->nullable();
            $table->text('avaliacao_prevista')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['diario_professor_id', 'data_inicio_semana'], 'planejamento_semanal_unico');
        });

        Schema::create('registros_aula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->foreignId('horario_aula_id')->nullable()->constrained('horario_aulas')->nullOnDelete();
            $table->foreignId('usuario_registro_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->date('data_aula');
            $table->string('titulo', 160);
            $table->text('conteudo_previsto')->nullable();
            $table->text('conteudo_ministrado');
            $table->text('metodologia')->nullable();
            $table->text('recursos_utilizados')->nullable();
            $table->unsignedTinyInteger('quantidade_aulas')->default(1);
            $table->boolean('aula_dada')->default(true);
            $table->timestamps();

            $table->index(['diario_professor_id', 'data_aula'], 'registro_aulas_diario_data_idx');
        });

        Schema::create('frequencias_aula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_aula_id')->constrained('registros_aula')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->string('situacao', 30)->default('presente');
            $table->text('justificativa')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->unique(['registro_aula_id', 'matricula_id'], 'frequencia_aula_unica');
            $table->index(['matricula_id', 'situacao'], 'frequencia_aula_matricula_situacao_idx');
        });

        Schema::create('observacoes_aluno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('usuario_registro_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->date('data_observacao');
            $table->string('categoria', 50)->default('pedagogica');
            $table->text('descricao');
            $table->text('encaminhamento')->nullable();
            $table->boolean('destaque')->default(false);
            $table->timestamps();

            $table->index(['matricula_id', 'data_observacao'], 'observacoes_aluno_matricula_data_idx');
        });

        Schema::create('ocorrencias_diario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->foreignId('matricula_id')->nullable()->constrained('matriculas')->nullOnDelete();
            $table->foreignId('usuario_registro_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->date('data_ocorrencia');
            $table->string('tipo', 50);
            $table->text('descricao');
            $table->text('providencias')->nullable();
            $table->string('status', 30)->default('aberta');
            $table->timestamps();

            $table->index(['diario_professor_id', 'data_ocorrencia'], 'ocorrencias_diario_data_idx');
        });

        Schema::create('pendencias_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->nullable()->constrained('diarios_professor')->nullOnDelete();
            $table->foreignId('professor_id')->constrained('funcionarios')->cascadeOnDelete();
            $table->foreignId('usuario_registro_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('origem', 50)->default('diario');
            $table->date('prazo')->nullable();
            $table->string('status', 30)->default('aberta');
            $table->timestamp('resolvida_em')->nullable();
            $table->timestamps();

            $table->index(['professor_id', 'status'], 'pendencias_professor_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendencias_professor');
        Schema::dropIfExists('ocorrencias_diario');
        Schema::dropIfExists('observacoes_aluno');
        Schema::dropIfExists('frequencias_aula');
        Schema::dropIfExists('registros_aula');
        Schema::dropIfExists('planejamentos_semanais');
        Schema::dropIfExists('planejamentos_anuais');
        Schema::dropIfExists('diarios_professor');
    }
};
