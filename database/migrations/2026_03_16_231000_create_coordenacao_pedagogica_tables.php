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
        Schema::create('validacoes_pedagogicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->morphs('validavel');
            $table->foreignId('usuario_coordenador_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('status', 30)->default('pendente');
            $table->text('parecer');
            $table->text('observacoes_internas')->nullable();
            $table->timestamp('validado_em')->nullable();
            $table->timestamps();

            $table->unique(['validavel_type', 'validavel_id'], 'validacao_pedagogica_validavel_unica');
            $table->index(['diario_professor_id', 'status'], 'validacao_pedagogica_diario_status_idx');
        });

        Schema::create('acompanhamentos_pedagogicos_aluno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('usuario_coordenador_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('nivel_rendimento', 30)->default('em_atencao');
            $table->string('situacao_risco', 30)->default('baixo');
            $table->decimal('percentual_frequencia', 5, 2)->nullable();
            $table->text('indicativos_aprendizagem');
            $table->text('fatores_risco')->nullable();
            $table->text('encaminhamentos')->nullable();
            $table->boolean('precisa_intervencao')->default(false);
            $table->timestamps();

            $table->unique(['diario_professor_id', 'matricula_id'], 'acompanhamento_pedagogico_aluno_unico');
            $table->index(['situacao_risco', 'precisa_intervencao'], 'acompanhamento_pedagogico_risco_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acompanhamentos_pedagogicos_aluno');
        Schema::dropIfExists('validacoes_pedagogicas');
    }
};
