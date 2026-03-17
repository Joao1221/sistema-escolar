<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\RegistroAuditoria;
use App\Models\Usuario;
use App\Services\AuditoriaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuditoriaPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
            'consultar auditoria da rede',
            'consultar auditoria escolar',
            'consultar auditoria pedagogica',
            'consultar auditoria da direcao escolar',
            'consultar auditoria do proprio trabalho docente',
            'consultar auditoria da alimentacao escolar',
            'consultar auditoria psicossocial sigilosa',
            'visualizar dados sensiveis de auditoria',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Administrador da Rede', 'web')->givePermissionTo(['consultar auditoria da rede', 'visualizar dados sensiveis de auditoria']);
        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo(['consultar auditoria escolar']);
        Role::findOrCreate('Professor', 'web')->givePermissionTo(['consultar auditoria do proprio trabalho docente']);
        Role::findOrCreate('Nutricionista', 'web')->givePermissionTo(['consultar auditoria da alimentacao escolar']);
        Role::findOrCreate('Psicologia/Psicopedagogia', 'web')->givePermissionTo(['consultar auditoria psicossocial sigilosa', 'visualizar dados sensiveis de auditoria']);
    }

    public function test_alteracao_de_aluno_gera_log_com_antes_e_depois(): void
    {
        $admin = Usuario::factory()->create(['email' => 'auditoria.admin@example.com']);
        $admin->assignRole('Administrador da Rede');

        $aluno = Aluno::create([
            'rgm' => 'RGM-1001',
            'nome_completo' => 'Aluno Auditoria',
            'data_nascimento' => '2015-01-02',
            'sexo' => 'M',
            'nome_mae' => 'Responsavel A',
            'responsavel_nome' => 'Responsavel A',
            'responsavel_cpf' => '123.456.789-00',
            'responsavel_telefone' => '(85) 99999-0001',
            'cep' => '60000-000',
            'logradouro' => 'Rua Auditoria',
            'numero' => '100',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        $this->actingAs($admin);
        $aluno->update([
            'nome_completo' => 'Aluno Auditoria Atualizado',
            'responsavel_nome' => 'Responsavel B',
        ]);

        $registro = RegistroAuditoria::query()
            ->where('modulo', 'alunos')
            ->where('acao', 'alteracao')
            ->latest('id')
            ->first();

        $this->assertNotNull($registro);
        $this->assertSame($admin->id, $registro->usuario_id);
        $this->assertSame('Aluno Auditoria', $registro->valores_antes['nome_completo']);
        $this->assertSame('Aluno Auditoria Atualizado', $registro->valores_depois['nome_completo']);
    }

    public function test_secretaria_escolar_ve_apenas_auditoria_da_sua_escola(): void
    {
        $admin = Usuario::factory()->create(['email' => 'auditoria.rede@example.com']);
        $admin->assignRole('Administrador da Rede');

        $escolaA = $this->criarEscola('Escola A Auditoria');
        $escolaB = $this->criarEscola('Escola B Auditoria');

        $alunoA = $this->criarAluno('Aluno Escola A');
        $alunoB = $this->criarAluno('Aluno Escola B');

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => $admin->id,
            'escola_id' => $escolaA->id,
            'modulo' => 'alunos',
            'acao' => 'alteracao',
            'tipo_registro' => 'Aluno',
            'nivel_sensibilidade' => 'alto',
            'valores_antes' => ['nome_completo' => 'Aluno Escola A'],
            'valores_depois' => ['nome_completo' => 'Aluno Escola A Atualizado'],
            'contexto' => ['aluno_id' => $alunoA->id],
        ]);

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => $admin->id,
            'escola_id' => $escolaB->id,
            'modulo' => 'alunos',
            'acao' => 'alteracao',
            'tipo_registro' => 'Aluno',
            'nivel_sensibilidade' => 'alto',
            'valores_antes' => ['nome_completo' => 'Aluno Escola B'],
            'valores_depois' => ['nome_completo' => 'Aluno Escola B Atualizado'],
            'contexto' => ['aluno_id' => $alunoB->id],
        ]);

        $secretaria = Usuario::factory()->create(['email' => 'auditoria.secretaria@example.com']);
        $secretaria->assignRole('Secretário Escolar');
        $secretaria->escolas()->attach($escolaA->id);

        $response = $this->actingAs($secretaria)->get('/secretaria-escolar/auditoria');

        $response->assertOk();
        $response->assertSee('Aluno Escola A Atualizado');
        $response->assertDontSee('Aluno Escola B Atualizado');
    }

    public function test_professor_ve_apenas_rastros_proprios_e_validacoes_relacionadas(): void
    {
        $escola = $this->criarEscola('Escola Professor Auditoria');
        $professor = Funcionario::create([
            'nome' => 'Professor Auditoria',
            'cpf' => '12345678901',
            'email' => 'prof.auditoria@example.com',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);

        $usuarioProfessor = Usuario::factory()->create([
            'email' => 'prof.auditoria@example.com',
            'funcionario_id' => $professor->id,
        ]);
        $usuarioProfessor->assignRole('Professor');
        $usuarioProfessor->escolas()->attach($escola->id);

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => $usuarioProfessor->id,
            'escola_id' => $escola->id,
            'modulo' => 'planejamentos',
            'acao' => 'alteracao',
            'tipo_registro' => 'Planejamento Semanal',
            'nivel_sensibilidade' => 'medio',
            'contexto' => ['professor_id' => $professor->id],
            'valores_depois' => ['conteudos' => 'Planejamento do professor'],
        ]);

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => null,
            'escola_id' => $escola->id,
            'modulo' => 'planejamentos',
            'acao' => 'validacao_pedagogica',
            'tipo_registro' => 'Validacao Pedagogica',
            'nivel_sensibilidade' => 'medio',
            'contexto' => ['professor_id' => $professor->id, 'status' => 'validado'],
            'valores_depois' => ['status' => 'validado'],
        ]);

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => null,
            'escola_id' => $escola->id,
            'modulo' => 'planejamentos',
            'acao' => 'validacao_pedagogica',
            'tipo_registro' => 'Validacao Pedagogica',
            'nivel_sensibilidade' => 'medio',
            'contexto' => ['professor_id' => 9999, 'status' => 'validado'],
            'valores_depois' => ['status' => 'validado'],
        ]);

        $response = $this->actingAs($usuarioProfessor)->get('/professor/auditoria');

        $response->assertOk();
        $response->assertSee('Planejamento do professor');
        $response->assertSee('validado');
    }

    public function test_nutricionista_ve_apenas_auditoria_da_alimentacao(): void
    {
        $nutri = Usuario::factory()->create(['email' => 'nutri.auditoria@example.com']);
        $nutri->assignRole('Nutricionista');

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => $nutri->id,
            'modulo' => 'alimentacao',
            'acao' => 'alteracao',
            'tipo_registro' => 'Cardapio Diario',
            'nivel_sensibilidade' => 'medio',
            'valores_depois' => ['observacoes' => 'Cardapio auditado'],
        ]);

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => $nutri->id,
            'modulo' => 'alunos',
            'acao' => 'alteracao',
            'tipo_registro' => 'Aluno',
            'nivel_sensibilidade' => 'alto',
            'valores_depois' => ['nome_completo' => 'Nao deve aparecer'],
        ]);

        $response = $this->actingAs($nutri)->get('/nutricionista/auditoria');

        $response->assertOk();
        $response->assertSee('Cardapio auditado');
        $response->assertDontSee('Nao deve aparecer');
    }

    public function test_psicossocial_tem_auditoria_restrita_e_secretaria_nao_acessa(): void
    {
        $escola = $this->criarEscola('Escola Psicossocial Auditoria');
        $psico = Usuario::factory()->create(['email' => 'psico.auditoria@example.com']);
        $psico->assignRole('Psicologia/Psicopedagogia');
        $psico->escolas()->attach($escola->id);

        app(AuditoriaService::class)->registrarEvento([
            'usuario_id' => $psico->id,
            'escola_id' => $escola->id,
            'modulo' => 'psicossocial',
            'acao' => 'visualizacao_sensivel',
            'tipo_registro' => 'Atendimento Psicossocial',
            'nivel_sensibilidade' => 'sigiloso',
            'valores_depois' => ['nivel_sigilo' => 'alto'],
        ]);

        $response = $this->actingAs($psico)->get('/secretaria-escolar/psicologia-psicopedagogia/auditoria');
        $response->assertOk();
        $response->assertSee('Atendimento Psicossocial');

        $secretaria = Usuario::factory()->create(['email' => 'sem.psico.auditoria@example.com']);
        $secretaria->assignRole('Secretário Escolar');

        $this->actingAs($secretaria)
            ->get('/secretaria-escolar/psicologia-psicopedagogia/auditoria')
            ->assertForbidden();
    }

    private function criarEscola(string $nome): Escola
    {
        return Escola::create([
            'nome' => $nome,
            'cnpj' => fake()->unique()->numerify('##############'),
            'email' => str($nome)->slug('.') . '@example.com',
            'telefone' => '(85) 3333-1111',
            'cep' => '60000-000',
            'endereco' => 'Rua Escolar, 100',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'nome_gestor' => 'Gestor Escolar',
            'ativo' => true,
        ]);
    }

    private function criarAluno(string $nome): Aluno
    {
        return Aluno::create([
            'rgm' => 'RGM-' . fake()->unique()->numerify('####'),
            'nome_completo' => $nome,
            'data_nascimento' => '2015-03-10',
            'sexo' => 'F',
            'nome_mae' => 'Responsavel Teste',
            'responsavel_nome' => 'Responsavel Teste',
            'responsavel_cpf' => '123.456.789-00',
            'responsavel_telefone' => '(85) 99999-0000',
            'cep' => '60000-000',
            'logradouro' => 'Rua do Aluno',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);
    }
}
