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
        Schema::create('modalidades_ensino', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: Educação Infantil
            $table->string('estrutura_avaliativa')->nullable(); // Ex: Bimestral, Trimestral
            $table->string('tipo_avaliacao')->nullable(); // Ex: Nota, Conceito, Parecer
            $table->integer('carga_horaria_minima')->default(800);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidades_ensino');
    }
};
