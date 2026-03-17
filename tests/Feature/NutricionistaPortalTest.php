<?php

namespace Tests\Feature;

use App\Models\Alimento;
use App\Models\CardapioDiario;
use App\Models\CategoriaAlimento;
use App\Models\Escola;
use App\Models\MovimentacaoAlimento;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NutricionistaPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
            'acessar portal da nutricionista',
            'consultar alimentos da nutricionista',
            'consultar categorias da nutricionista',
            'consultar fornecedores da nutricionista',
            'consultar cardapios da nutricionista',
            'consultar estoque da nutricionista',
            'consultar validade da nutricionista',
            'consultar movimentacoes da nutricionista',
            'consultar comparativo de alimentacao entre escolas',
            'consultar relatorios gerenciais da alimentacao',
            'consultar alimentacao escolar',
            'consultar estoque de alimentos',
            'consultar movimentacoes de alimentos',
            'lancar cardapio diario',
            'cadastrar categorias de alimentos',
            'cadastrar alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Nutricionista', 'web')->givePermissionTo([
            'acessar portal da nutricionista',
            'consultar alimentos da nutricionista',
            'consultar categorias da nutricionista',
            'consultar fornecedores da nutricionista',
            'consultar cardapios da nutricionista',
            'consultar estoque da nutricionista',
            'consultar validade da nutricionista',
            'consultar movimentacoes da nutricionista',
            'consultar comparativo de alimentacao entre escolas',
            'consultar relatorios gerenciais da alimentacao',
        ]);

        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo([
            'consultar alimentacao escolar',
            'consultar estoque de alimentos',
            'consultar movimentacoes de alimentos',
            'lancar cardapio diario',
            'cadastrar categorias de alimentos',
            'cadastrar alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
        ]);
    }

    public function test_nutricionista_pode_acessar_portal_com_visao_gerencial(): void
    {
        [$escolaA, $escolaB, $alimento] = $this->criarContextoAlimentacao();

        $nutricionista = Usuario::factory()->create(['email' => 'nutri@example.com']);
        $nutricionista->assignRole('Nutricionista');

        $hubResponse = $this->actingAs($nutricionista)->get('/');
        $hubResponse->assertOk();
        $hubResponse->assertSee('Portal da Nutricionista');

        $dashboardResponse = $this->actingAs($nutricionista)->get('/nutricionista/dashboard');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Portal da Nutricionista');
        $dashboardResponse->assertSee($escolaA->nome);
        $dashboardResponse->assertSee($escolaB->nome);

        $estoqueResponse = $this->actingAs($nutricionista)->get('/nutricionista/estoque');
        $estoqueResponse->assertOk();
        $estoqueResponse->assertSee($alimento->nome);
        $estoqueResponse->assertSee($escolaA->nome);

        $relatoriosResponse = $this->actingAs($nutricionista)->get('/nutricionista/relatorios');
        $relatoriosResponse->assertOk();
        $relatoriosResponse->assertSee('Comparativo gerencial por escola');
    }

    public function test_secretaria_escolar_continua_operando_alimentacao_no_contexto_da_escola(): void
    {
        [$escola] = $this->criarContextoAlimentacao();

        $secretaria = Usuario::factory()->create(['email' => 'secretaria.nutri@example.com']);
        $secretaria->assignRole('Secretário Escolar');
        $secretaria->escolas()->attach($escola->id);

        $response = $this->actingAs($secretaria)->get('/secretaria-escolar/alimentacao');

        $response->assertOk();
        $response->assertSee('Operacao diaria da merenda escolar');
    }

    public function test_usuario_sem_permissao_nao_acessa_portal_da_nutricionista(): void
    {
        $usuario = Usuario::factory()->create(['email' => 'sem.portal.nutri@example.com']);

        $this->actingAs($usuario)
            ->get('/nutricionista/dashboard')
            ->assertForbidden();
    }

    private function criarContextoAlimentacao(): array
    {
        $escolaA = Escola::create([
            'nome' => 'Escola Nutri A',
            'cnpj' => '00.000.000/0001-31',
            'email' => 'a@example.com',
            'telefone' => '(85) 3333-1111',
            'cep' => '60000-000',
            'endereco' => 'Rua A, 10',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        $escolaB = Escola::create([
            'nome' => 'Escola Nutri B',
            'cnpj' => '00.000.000/0001-32',
            'email' => 'b@example.com',
            'telefone' => '(85) 3333-2222',
            'cep' => '60000-000',
            'endereco' => 'Rua B, 20',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        $categoria = CategoriaAlimento::create([
            'nome' => 'Frutas',
            'descricao' => 'Grupo de frutas',
            'ativo' => true,
        ]);

        $alimento = Alimento::create([
            'categoria_alimento_id' => $categoria->id,
            'nome' => 'Maca',
            'unidade_medida' => 'kg',
            'estoque_minimo' => 3,
            'controla_validade' => true,
            'ativo' => true,
        ]);

        $usuarioOperador = Usuario::factory()->create(['email' => 'operador.nutri@example.com']);

        MovimentacaoAlimento::create([
            'escola_id' => $escolaA->id,
            'alimento_id' => $alimento->id,
            'usuario_id' => $usuarioOperador->id,
            'tipo' => 'entrada',
            'quantidade' => 12,
            'saldo_resultante' => 12,
            'data_movimentacao' => '2026-03-17',
            'data_validade' => '2026-03-28',
        ]);

        MovimentacaoAlimento::create([
            'escola_id' => $escolaB->id,
            'alimento_id' => $alimento->id,
            'usuario_id' => $usuarioOperador->id,
            'tipo' => 'entrada',
            'quantidade' => 7,
            'saldo_resultante' => 7,
            'data_movimentacao' => '2026-03-17',
            'data_validade' => '2026-03-24',
        ]);

        CardapioDiario::create([
            'escola_id' => $escolaA->id,
            'usuario_id' => $usuarioOperador->id,
            'data_cardapio' => '2026-03-18',
            'observacoes' => 'Cardapio tecnico de referencia.',
        ]);

        return [$escolaA, $escolaB, $alimento];
    }
}
