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
        Schema::create('devolutivas_psicossociais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atendimento_id')->constrained('atendimentos_psicossociais')->onDelete('cascade');
            $table->foreignId('usuario_responsavel_id')->constrained('usuarios')->onDelete('restrict');
            $table->enum('destinatario', [
                'familia',
                'professor',
                'coordenacao',
                'direcao',
                'funcionario',
                'outro'
            ]);
            $table->string('nome_destinatario')->nullable();
            $table->date('data_devolutiva');
            $table->text('resumo_devolutiva')->nullable();
            $table->text('orientacoes')->nullable();
            $table->text('encaminhamentos_combinados')->nullable();
            $table->boolean('necessita_acompanhamento')->default(false);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolutivas_psicossociais');
    }
};
