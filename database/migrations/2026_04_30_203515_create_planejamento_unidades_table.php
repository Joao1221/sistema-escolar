<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('planejamentos_anuais', 'unidade')) {
            Schema::table('planejamentos_anuais', function (Blueprint $table) {
                $table->tinyInteger('unidade')->nullable()->after('diario_professor_id')->comment('Unidade/Bimestre (1-4)');
            });
        }

        DB::statement('UPDATE planejamentos_anuais SET unidade = 1 WHERE unidade IS NULL OR unidade = 0');

        Schema::table('planejamentos_anuais', function (Blueprint $table) {
            // FK usa o unique index como backing index; é preciso dropar a FK antes
            $table->dropForeign('planejamentos_anuais_diario_professor_id_foreign');
            $table->dropUnique('planejamentos_anuais_diario_professor_id_unique');

            $table->tinyInteger('unidade')->nullable(false)->default(1)->change();

            // Nova constraint composta: 1 linha por diário por unidade
            $table->unique(['diario_professor_id', 'unidade'], 'planejamentos_anuais_diario_unidade_unique');

            // Recoloca a FK (o composite unique acima serve de backing index)
            $table->foreign('diario_professor_id', 'planejamentos_anuais_diario_professor_id_foreign')
                ->references('id')
                ->on('diarios_professor')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('planejamentos_anuais', function (Blueprint $table) {
            $table->dropForeign('planejamentos_anuais_diario_professor_id_foreign');
            $table->dropUnique('planejamentos_anuais_diario_unidade_unique');
            $table->tinyInteger('unidade')->nullable()->default(null)->change();
            $table->unique('diario_professor_id', 'planejamentos_anuais_diario_professor_id_unique');
            $table->foreign('diario_professor_id', 'planejamentos_anuais_diario_professor_id_foreign')
                ->references('id')
                ->on('diarios_professor')
                ->cascadeOnDelete();
        });
    }
};
