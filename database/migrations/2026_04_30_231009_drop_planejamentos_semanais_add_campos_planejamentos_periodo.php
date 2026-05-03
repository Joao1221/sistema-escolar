<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('planejamentos_semanais');

        Schema::table('planejamentos_periodo', function (Blueprint $table) {
            if (! Schema::hasColumn('planejamentos_periodo', 'status')) {
                $table->string('status', 20)->default('rascunho')->after('diario_professor_id');
            }
            if (! Schema::hasColumn('planejamentos_periodo', 'tema_gerador')) {
                $table->string('tema_gerador')->nullable()->after('status');
            }
            if (! Schema::hasColumn('planejamentos_periodo', 'referencias')) {
                $table->text('referencias')->nullable()->after('adequacoes_inclusao');
            }
        });
    }

    public function down(): void
    {
        Schema::table('planejamentos_periodo', function (Blueprint $table) {
            $table->dropColumn(['status', 'tema_gerador', 'referencias']);
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
    }
};
