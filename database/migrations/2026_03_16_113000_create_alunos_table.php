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
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('rgm')->unique()->comment('Registro Geral de Matrícula');
            
            // Dados Pessoais
            $table->string('nome_completo');
            $table->date('data_nascimento');
            $table->enum('sexo', ['M', 'F', 'O'])->default('O');
            $table->string('cpf', 14)->nullable()->unique();
            $table->string('nis', 15)->nullable();
            
            // Filiação / Responsável
            $table->string('nome_mae');
            $table->string('nome_pai')->nullable();
            $table->string('responsavel_nome');
            $table->string('responsavel_cpf', 14);
            $table->string('responsavel_telefone', 15);
            
            // Endereço
            $table->string('cep', 9);
            $table->string('logradouro');
            $table->string('numero', 10);
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cidade');
            $table->char('uf', 2);
            
            // Documentos
            $table->string('certidao_nascimento')->nullable();
            $table->string('rg_numero')->nullable();
            $table->string('rg_orgao')->nullable();
            
            // Saúde
            $table->text('alergias')->nullable();
            $table->text('medicamentos')->nullable();
            $table->text('restricoes_alimentares')->nullable();
            $table->text('obs_saude')->nullable();
            
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
