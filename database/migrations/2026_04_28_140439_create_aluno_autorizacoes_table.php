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
        Schema::create('aluno_autorizacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            
            // Autorizações LGPD
            $table->boolean('aut_uso_imagem')->default(true);
            $table->enum('aut_passeios', ['sim', 'nao', 'parcial'])->default('sim');
            $table->boolean('aut_tratamento_dados')->default(true);
            $table->boolean('aut_saida')->default(true);
            
            //第三方
            $table->string('aut_saida_nome', 100)->nullable();
            $table->string('aut_saida_parentesco', 30)->nullable();
            $table->string('aut_saida_fone', 20)->nullable();
            
            $table->timestamps();
            
            $table->index('aluno_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aluno_autorizacoes');
    }
};
