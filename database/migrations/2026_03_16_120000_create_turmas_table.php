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
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('modalidade_id')->constrained('modalidades_ensino');
            
            $table->string('serie_etapa');
            $table->string('nome'); // Ex: Turma A, 1º Ano B
            $table->enum('turno', ['Matutino', 'Vespertino', 'Noturno', 'Integral']);
            $table->year('ano_letivo');
            $table->integer('vagas')->default(0);
            $table->boolean('is_multisseriada')->default(false);
            $table->boolean('ativa')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
