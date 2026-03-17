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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos');
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('turma_id')->nullable()->constrained('turmas');
            $table->year('ano_letivo');
            $table->enum('tipo', ['regular', 'aee'])->default('regular');
            $table->enum('status', ['ativa', 'concluida', 'cancelada', 'transferida', 'rematriculada'])->default('ativa');
            $table->foreignId('matricula_regular_id')->nullable()->constrained('matriculas')->onDelete('cascade');
            $table->date('data_matricula');
            $table->date('data_encerramento')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
