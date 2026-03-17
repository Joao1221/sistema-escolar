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
        Schema::create('parametros_rede', function (Blueprint $table) {
            $table->id();
            $table->integer('ano_letivo_vigente')->nullable();
            $table->integer('dias_letivos_minimos')->default(200);
            $table->decimal('media_minima', 5, 2)->default(6.00);
            $table->integer('frequencia_minima')->default(75);
            $table->text('parametros_documentos')->nullable();
            $table->text('parametros_upload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_rede');
    }
};
