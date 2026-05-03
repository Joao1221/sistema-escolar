<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('alunos', 'escola_id')) {
            Schema::table('alunos', function (Blueprint $table) {
                $table->foreignId('escola_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('escolas')
                    ->nullOnDelete();
            });
        }

        DB::statement("
            UPDATE alunos a
            JOIN (
                SELECT aluno_id, MAX(id) AS matricula_id
                FROM matriculas
                GROUP BY aluno_id
            ) m ON m.aluno_id = a.id
            JOIN matriculas mt ON mt.id = m.matricula_id
            SET a.escola_id = mt.escola_id
            WHERE a.escola_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('escola_id');
        });
    }
};
