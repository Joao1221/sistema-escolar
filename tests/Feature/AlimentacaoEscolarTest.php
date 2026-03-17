<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Escola;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AlimentacaoEscolarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
            'consultar alimentacao escolar',
            'cadastrar alimentos',
            'editar alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
            'lancar cardapio diario',
            'consultar estoque de alimentos',
            'consultar movimentacoes de alimentos',
            'cadastrar categorias de alimentos',
            'cadastrar fornecedores de alimentos',
            'editar categorias de alimentos',
            'editar fornecedores de alimentos',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo([
            'consultar alimentacao escolar',
            'cadastrar alimentos',
            'editar alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
            'lancar cardapio diario',
            'consultar estoque de alimentos',
            'consultar movimentacoes de alimentos',
            'cadastrar categorias de alimentos',
            'cadastrar fornecedores de alimentos',
            'editar categorias de alimentos',
            'editar fornecedores de alimentos',
        ]);

        Role::findOrCreate('Professor', 'web');
    }

    public function test_secretaria_escolar_consegue_operar_fluxo_basico_da_alimentacao(): void
    {
        $escola = $this->criarEscola();
        $usuario = Usuario::factory()->create(['email' => 'secretaria.alimentacao@example.com']);
        $usuario->assignRole('Secretário Escolar');
        $usuario->escolas()->attach($escola->id);

        $this->actingAs($usuario)->post('/secretaria-escolar/alimentacao/categorias', [
            'nome' => 'Hortifruti',
            'descricao' => 'Legumes, verduras e frutas',
            'ativo' => '1',
        ])->assertRedirect();

        $this->actingAs($usuario)->post('/secretaria-escolar/alimentacao/fornecedores', [
            'nome' => 'Fornecedor Verde',
            'cnpj' => '12.345.678/0001-90',
            'contato_nome' => 'Jose Compras',
            'telefone' => '(85) 99999-1010',
            'email' => 'fornecedor@example.com',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => '1',
        ])->assertRedirect();

        $categoriaId = \App\Models\CategoriaAlimento::query()->value('id');

        $this->actingAs($usuario)->post('/secretaria-escolar/alimentacao/alimentos', [
            'categoria_alimento_id' => $categoriaId,
            'nome' => 'Banana prata',
            'unidade_medida' => 'kg',
            'estoque_minimo' => 2,
            'controla_validade' => '1',
            'ativo' => '1',
        ])->assertRedirect();

        $alimentoId = \App\Models\Alimento::query()->value('id');
        $fornecedorId = \App\Models\FornecedorAlimento::query()->value('id');

        $this->actingAs($usuario)->post('/secretaria-escolar/alimentacao/movimentacoes', [
            'escola_id' => $escola->id,
            'alimento_id' => $alimentoId,
            'fornecedor_alimento_id' => $fornecedorId,
            'tipo' => 'entrada',
            'quantidade' => 10,
            'data_movimentacao' => '2026-03-16',
            'data_validade' => '2026-03-25',
            'lote' => 'L-001',
            'valor_unitario' => 3.50,
        ])->assertRedirect();

        $this->actingAs($usuario)->post('/secretaria-escolar/alimentacao/movimentacoes', [
            'escola_id' => $escola->id,
            'alimento_id' => $alimentoId,
            'tipo' => 'saida',
            'quantidade' => 4,
            'data_movimentacao' => '2026-03-17',
            'observacoes' => 'Uso no lanche da manha',
        ])->assertRedirect();

        $this->actingAs($usuario)->post('/secretaria-escolar/alimentacao/cardapios', [
            'escola_id' => $escola->id,
            'data_cardapio' => '2026-03-17',
            'observacoes' => 'Cardapio de teste',
            'itens' => [
                [
                    'refeicao' => 'lanche_da_manha',
                    'alimento_id' => $alimentoId,
                    'quantidade_prevista' => 4,
                    'observacoes' => 'Servir por porcao',
                ],
            ],
        ])->assertRedirect();

        $responsePainel = $this->actingAs($usuario)->get('/secretaria-escolar/alimentacao');
        $responsePainel->assertOk();
        $responsePainel->assertSee('Banana prata');

        $this->assertDatabaseHas('movimentacoes_alimentos', [
            'escola_id' => $escola->id,
            'alimento_id' => $alimentoId,
            'tipo' => 'entrada',
            'saldo_resultante' => 10,
        ]);

        $this->assertDatabaseHas('movimentacoes_alimentos', [
            'escola_id' => $escola->id,
            'alimento_id' => $alimentoId,
            'tipo' => 'saida',
            'saldo_resultante' => 6,
        ]);

        $this->assertDatabaseHas('cardapios_diarios', [
            'escola_id' => $escola->id,
            'data_cardapio' => '2026-03-17',
        ]);
    }

    public function test_professor_nao_acessa_modulo_de_alimentacao_escolar(): void
    {
        $escola = $this->criarEscola();
        $usuario = Usuario::factory()->create(['email' => 'professor.alimentacao@example.com']);
        $usuario->assignRole('Professor');
        $usuario->escolas()->attach($escola->id);

        $this->actingAs($usuario)
            ->get('/secretaria-escolar/alimentacao')
            ->assertForbidden();
    }

    private function criarEscola(): Escola
    {
        return Escola::create([
            'nome' => 'Escola Alimentacao Teste',
            'cnpj' => '00.000.000/0001-11',
            'email' => 'escola.alimentacao@example.com',
            'telefone' => '(85) 3333-1111',
            'cep' => '60000-000',
            'endereco' => 'Rua da Merenda, 100',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);
    }
}
