<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_alimentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('fornecedores_alimentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj', 18)->nullable()->unique();
            $table->string('contato_nome')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('alimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_alimento_id')->constrained('categorias_alimentos');
            $table->string('nome');
            $table->string('unidade_medida', 20);
            $table->decimal('estoque_minimo', 10, 3)->default(0);
            $table->boolean('controla_validade')->default(true);
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['categoria_alimento_id', 'nome']);
            $table->index(['ativo', 'nome']);
        });

        Schema::create('movimentacoes_alimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('alimento_id')->constrained('alimentos');
            $table->foreignId('fornecedor_alimento_id')->nullable()->constrained('fornecedores_alimentos');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->string('tipo', 10);
            $table->decimal('quantidade', 10, 3);
            $table->decimal('saldo_resultante', 10, 3);
            $table->date('data_movimentacao');
            $table->date('data_validade')->nullable();
            $table->string('lote')->nullable();
            $table->decimal('valor_unitario', 10, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['escola_id', 'alimento_id', 'data_movimentacao'], 'mov_alimento_escola_data_idx');
            $table->index(['escola_id', 'tipo'], 'mov_alimento_escola_tipo_idx');
            $table->index(['escola_id', 'data_validade'], 'mov_alimento_validade_idx');
        });

        Schema::create('cardapios_diarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->date('data_cardapio');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['escola_id', 'data_cardapio']);
            $table->index(['escola_id', 'data_cardapio']);
        });

        Schema::create('cardapio_diario_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cardapio_diario_id')->constrained('cardapios_diarios')->cascadeOnDelete();
            $table->foreignId('alimento_id')->constrained('alimentos');
            $table->string('refeicao', 30);
            $table->decimal('quantidade_prevista', 10, 3)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['cardapio_diario_id', 'refeicao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio_diario_itens');
        Schema::dropIfExists('cardapios_diarios');
        Schema::dropIfExists('movimentacoes_alimentos');
        Schema::dropIfExists('alimentos');
        Schema::dropIfExists('fornecedores_alimentos');
        Schema::dropIfExists('categorias_alimentos');
    }
};
