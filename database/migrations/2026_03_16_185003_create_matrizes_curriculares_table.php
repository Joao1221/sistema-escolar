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
        Schema::create('matrizes_curriculares', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('modalidade_id')->constrained('modalidades_ensino')->onDelete('cascade');
            $table->string('serie_etapa');
            $table->foreignId('escola_id')->nullable()->constrained('escolas')->onDelete('cascade');
            $table->integer('ano_vigencia');
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrizes_curriculares');
    }
};
