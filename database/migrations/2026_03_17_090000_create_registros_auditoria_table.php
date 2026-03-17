<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->foreignId('escola_id')->nullable()->constrained('escolas')->nullOnDelete();
            $table->string('modulo', 50);
            $table->string('acao', 50);
            $table->string('tipo_registro', 120);
            $table->string('registro_type', 190)->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->string('nivel_sensibilidade', 20)->default('medio');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('valores_antes')->nullable();
            $table->json('valores_depois')->nullable();
            $table->json('contexto')->nullable();
            $table->timestamps();

            $table->index(['modulo', 'acao', 'created_at'], 'auditoria_modulo_acao_idx');
            $table->index(['escola_id', 'created_at'], 'auditoria_escola_data_idx');
            $table->index(['usuario_id', 'created_at'], 'auditoria_usuario_data_idx');
            $table->index(['nivel_sensibilidade', 'created_at'], 'auditoria_sensibilidade_data_idx');
            $table->index(['registro_type', 'registro_id'], 'auditoria_registro_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_auditoria');
    }
};
