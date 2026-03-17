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
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_prefeitura')->nullable();
            $table->string('cnpj_prefeitura')->nullable();
            $table->string('nome_prefeito')->nullable();
            $table->string('nome_secretaria')->nullable();
            $table->string('sigla_secretaria')->nullable();
            $table->string('nome_secretario')->nullable();
            $table->string('endereco')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('municipio')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('cep')->nullable();
            $table->string('brasao_path')->nullable();
            $table->string('logo_prefeitura_path')->nullable();
            $table->string('logo_secretaria_path')->nullable();
            $table->text('textos_institucionais')->nullable();
            $table->text('assinaturas_cargos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituicoes');
    }
};
