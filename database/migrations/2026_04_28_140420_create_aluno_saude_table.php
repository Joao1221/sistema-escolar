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
        Schema::create('aluno_saude', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            
            // Deficiências (JSON array)
            $table->json('deficiencias')->nullable();
            
            // Transtornos (JSON array)
            $table->json('transtornos')->nullable();
            
            // Altas habilidades
            $table->boolean('altas_habilidades')->default(false);
            
            // Informações médicas
            $table->string('tipo_sanguineo', 10)->nullable();
            $table->string('alergias', 255)->nullable();
            $table->string('restricoes_alimentares', 255)->nullable();
            $table->boolean('medicacao_continua')->default(false);
            $table->string('medicacao_desc', 255)->nullable();
            $table->text('obs_saude')->nullable();
            
            // Contato de emergência
            $table->string('emergencia_nome', 100)->nullable();
            $table->string('emergencia_parentesco', 30)->nullable();
            $table->string('emergencia_fone', 20)->nullable();
            
            $table->timestamps();
            
            $table->index('aluno_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aluno_saude');
    }
};
