<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planejamentos_anuais', function (Blueprint $table) {
            $table->date('periodo_vigencia_inicio')->nullable()->after('diario_professor_id');
            $table->date('periodo_vigencia_fim')->nullable()->after('periodo_vigencia_inicio');
            $table->text('conteudos')->nullable()->after('competencias_habilidades');
            $table->text('metodologia')->nullable()->after('conteudos');
            $table->text('recursos_didaticos')->nullable()->after('metodologia');
            $table->text('estrategias_pedagogicas')->nullable()->after('recursos_didaticos');
            $table->text('instrumentos_avaliacao')->nullable()->after('estrategias_pedagogicas');
            $table->text('adequacoes_inclusao')->nullable()->after('instrumentos_avaliacao');
        });

        Schema::create('planejamentos_periodo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->string('tipo_planejamento', 20);
            $table->string('periodo_referencia', 120)->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->text('objetivos_aprendizagem');
            $table->text('habilidades_competencias')->nullable();
            $table->text('conteudos');
            $table->text('metodologia')->nullable();
            $table->text('recursos_didaticos')->nullable();
            $table->text('estrategias_pedagogicas')->nullable();
            $table->text('instrumentos_avaliacao')->nullable();
            $table->text('observacoes')->nullable();
            $table->text('adequacoes_inclusao')->nullable();
            $table->timestamps();

            $table->index(['diario_professor_id', 'tipo_planejamento'], 'planejamentos_periodo_diario_tipo_idx');
            $table->unique(
                ['diario_professor_id', 'tipo_planejamento', 'data_inicio'],
                'planejamentos_periodo_unico'
            );
        });

        Schema::create('lancamentos_avaliativos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diario_professor_id')->constrained('diarios_professor')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('usuario_registro_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('tipo_avaliacao', 20);
            $table->string('avaliacao_referencia', 80)->nullable();
            $table->decimal('valor_numerico', 5, 2)->nullable();
            $table->string('conceito', 50)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['diario_professor_id', 'matricula_id', 'avaliacao_referencia'], 'lancamentos_avaliativos_unico');
            $table->index(['diario_professor_id', 'tipo_avaliacao'], 'lancamentos_avaliativos_diario_tipo_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lancamentos_avaliativos');
        Schema::dropIfExists('planejamentos_periodo');

        Schema::table('planejamentos_anuais', function (Blueprint $table) {
            $table->dropColumn([
                'periodo_vigencia_inicio',
                'periodo_vigencia_fim',
                'conteudos',
                'metodologia',
                'recursos_didaticos',
                'estrategias_pedagogicas',
                'instrumentos_avaliacao',
                'adequacoes_inclusao',
            ]);
        });
    }
};
