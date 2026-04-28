<?php

namespace Tests\Feature;

use App\Models\Instituicao;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GestaoRedeBasicaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'visualizar instituicao',
            'editar instituicao',
            'visualizar escolas',
            'criar escola',
            'ativar inativar escola',
            'visualizar funcionarios',
            'criar funcionario',
            'ativar inativar funcionario',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Administrador da Rede', 'web')->givePermissionTo([
            'visualizar instituicao',
            'editar instituicao',
            'visualizar escolas',
            'criar escola',
            'ativar inativar escola',
            'visualizar funcionarios',
            'criar funcionario',
            'ativar inativar funcionario',
        ]);
    }

    public function test_administrador_da_rede_pode_atualizar_dados_institucionais(): void
    {
        $admin = Usuario::factory()->create(['email' => 'rede.basica@example.com']);
        $admin->assignRole('Administrador da Rede');

        Instituicao::create([
            'nome_prefeitura' => 'Prefeitura Inicial',
            'nome_secretaria' => 'Secretaria Inicial',
        ]);

        $this->actingAs($admin)
            ->put(route('secretaria.instituicao.update'), [
                'nome_prefeitura' => 'Prefeitura Municipal de Teste',
                'nome_secretaria' => 'Secretaria Municipal de Educacao',
                'sigla_secretaria' => 'SME',
                'municipio' => 'Fortaleza',
                'uf' => 'CE',
            ])
            ->assertRedirect(route('secretaria.instituicao.show'));

        $this->assertDatabaseHas('instituicoes', [
            'nome_prefeitura' => 'Prefeitura Municipal de Teste',
            'nome_secretaria' => 'Secretaria Municipal de Educacao',
        ]);
    }

    public function test_administrador_da_rede_pode_cadastrar_escola_e_funcionario(): void
    {
        $admin = Usuario::factory()->create(['email' => 'rede.gestao@example.com']);
        $admin->assignRole('Administrador da Rede');

        $this->actingAs($admin)
            ->post(route('secretaria.escolas.store'), [
                'nome' => 'Escola Rede Teste',
                'inep' => '12345678',
                'cnpj' => '22.222.222/0001-22',
                'email' => 'escola.rede@example.com',
                'telefone' => '(85) 3333-2222',
                'cep' => '60000-000',
                'endereco' => 'Rua da Rede, 200',
                'bairro' => 'Centro',
                'cidade' => 'Fortaleza',
                'uf' => 'CE',
                'nome_gestor' => 'Gestor Rede',
                'ativo' => 1,
            ])
            ->assertRedirect(route('secretaria.escolas.index'));

        $escolaId = \App\Models\Escola::query()->where('nome', 'Escola Rede Teste')->value('id');

        $this->actingAs($admin)
            ->post(route('secretaria.funcionarios.store'), [
                'nome' => 'Funcionario Rede Teste',
                'cpf' => '123.456.789-10',
                'email' => 'funcionario.rede@example.com',
                'telefone' => '(85) 99999-3030',
                'cargo' => 'Assistente Administrativo',
                'escolas' => [$escolaId],
                'ativo' => 1,
            ])
            ->assertRedirect(route('secretaria.funcionarios.index'));

        $this->assertDatabaseHas('escolas', [
            'nome' => 'Escola Rede Teste',
        ]);

        $this->assertDatabaseHas('funcionarios', [
            'nome' => 'Funcionario Rede Teste',
        ]);
    }
}
