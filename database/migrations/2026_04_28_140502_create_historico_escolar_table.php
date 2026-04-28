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
        Schema::create('historico_escolar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            
            // Escola de origem
            $table->string('escola_origem', 150)->nullable();
            $table->string('escola_inep', 8)->nullable();
            $table->enum('rede', ['municipal', 'publica', 'privada', 'outra'])->nullable();
            $table->string('cidade_uf', 50)->nullable();
            
            // dados do último ano cursado
            $table->string('serie_cursada', 30)->nullable();
            $table->year('ano_cursado')->nullable();
            $table->enum('situacao', ['nao_informado', 'transferido', 'concluiu', 'cursando', 'desistente'])->nullable();
            $table->date('data_transferencia')->nullable();
            
            // Série/ano pretendido na nova escola
            $table->string('serie_pretendida', 30)->nullable();
            $table->enum('turno', ['manha', 'tarde', 'noite', 'integral'])->nullable();
            
            // Pendências documentais
            $table->boolean('pendencias')->default(false);
            $table->text('obs_pendencias')->nullable();
            
            $table->timestamps();
            
            $table->index('aluno_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_escolar');
    }
};
