<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\AtendimentoPsicossocial;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PsicossocialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
            'acessar modulo psicossocial',
            'consultar agenda psicossocial',
            'registrar atendimentos psicossociais',
            'consultar historico psicossocial',
            'registrar planos de intervencao psicossociais',
            'registrar encaminhamentos psicossociais',
            'registrar casos disciplinares sigilosos',
            'emitir relatorios tecnicos psicossociais',
            'acessar dados sigilosos psicossociais',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Psicologia/Psicopedagogia', 'web')->givePermissionTo([
            'acessar modulo psicossocial',
            'consultar agenda psicossocial',
            'registrar atendimentos psicossociais',
            'consultar historico psicossocial',
            'registrar planos de intervencao psicossociais',
            'registrar encaminhamentos psicossociais',
            'registrar casos disciplinares sigilosos',
            'emitir relatorios tecnicos psicossociais',
            'acessar dados sigilosos psicossociais',
        ]);

        Role::findOrCreate('Secretário Escolar', 'web');
    }

    public function test_profissional_pode_registrar_atendimento_plano_encaminhamento_e_relatorio(): void
    {
        [$escola, $aluno] = $this->criarContextoBase();

        $usuario = Usuario::factory()->create(['email' => 'psico@example.com']);
        $usuario->assignRole('Psicologia/Psicopedagogia');
        $usuario->escolas()->attach($escola->id);

        $this->actingAs($usuario)->post('/secretaria-escolar/psicologia-psicopedagogia/atendimentos', [
            'escola_id' => $escola->id,
            'tipo_publico' => 'responsavel',
            'aluno_id' => $aluno->id,
            'responsavel_nome' => 'Maria Responsavel',
            'responsavel_tipo_vinculo' => 'mae',
            'responsavel_telefone' => '(85) 98888-0000',
            'tipo_atendimento' => 'psicopedagogia',
            'natureza' => 'agendado',
            'status' => 'realizado',
            'data_agendada' => '2026-03-17 08:00:00',
            'data_realizacao' => '2026-03-17 08:30:00',
            'local_atendimento' => 'Sala tecnica',
            'motivo_demanda' => 'Dificuldades recorrentes de aprendizagem.',
            'resumo_sigiloso' => 'Observacoes iniciais sigilosas.',
            'nivel_sigilo' => 'muito_restrito',
            'requer_acompanhamento' => '1',
        ])->assertRedirect();

        $atendimento = AtendimentoPsicossocial::query()->firstOrFail();

        $this->actingAs($usuario)->post("/secretaria-escolar/psicologia-psicopedagogia/atendimentos/{$atendimento->id}/planos-intervencao", [
            'objetivo_geral' => 'Fortalecer rotinas de estudo.',
            'estrategias' => 'Acompanhamento semanal com familia e escola.',
            'data_inicio' => '2026-03-18',
            'status' => 'ativo',
        ])->assertRedirect();

        $this->actingAs($usuario)->post("/secretaria-escolar/psicologia-psicopedagogia/atendimentos/{$atendimento->id}/encaminhamentos", [
            'tipo' => 'interno',
            'destino' => 'Coordenacao Pedagogica',
            'motivo' => 'Alinhar estrategias de acompanhamento.',
            'data_encaminhamento' => '2026-03-18',
            'status' => 'emitido',
        ])->assertRedirect();

        $this->actingAs($usuario)->post("/secretaria-escolar/psicologia-psicopedagogia/atendimentos/{$atendimento->id}/relatorios-tecnicos", [
            'tipo_relatorio' => 'parecer_inicial',
            'titulo' => 'Parecer tecnico inicial',
            'conteudo_sigiloso' => 'Conteudo tecnico sigiloso.',
            'data_emissao' => '2026-03-18',
        ])->assertRedirect();

        $agendaResponse = $this->actingAs($usuario)->get('/secretaria-escolar/psicologia-psicopedagogia/agenda');
        $agendaResponse->assertOk();
        $agendaResponse->assertSee('Maria Responsavel');

        $historicoResponse = $this->actingAs($usuario)->get('/secretaria-escolar/psicologia-psicopedagogia/historico');
        $historicoResponse->assertOk();
        $historicoResponse->assertSee('Maria Responsavel');

        $this->assertDatabaseHas('atendidos_externos', [
            'nome' => 'Maria Responsavel',
            'tipo_vinculo' => 'mae',
        ]);

        $this->assertDatabaseHas('planos_intervencao_psicossociais', [
            'atendimento_psicossocial_id' => $atendimento->id,
            'status' => 'ativo',
        ]);

        $this->assertDatabaseHas('encaminhamentos_psicossociais', [
            'atendimento_psicossocial_id' => $atendimento->id,
            'destino' => 'Coordenacao Pedagogica',
        ]);

        $this->assertDatabaseHas('relatorios_tecnicos_psicossociais', [
            'atendimento_psicossocial_id' => $atendimento->id,
            'titulo' => 'Parecer tecnico inicial',
        ]);
    }

    public function test_profissional_de_outra_escola_nao_acessa_registro_sigiloso(): void
    {
        [$escolaA, $aluno] = $this->criarContextoBase();
        $escolaB = $this->criarEscola('Escola B', '00.000.000/0001-42');

        $usuarioA = Usuario::factory()->create(['email' => 'psico.a@example.com']);
        $usuarioA->assignRole('Psicologia/Psicopedagogia');
        $usuarioA->escolas()->attach($escolaA->id);

        $usuarioB = Usuario::factory()->create(['email' => 'psico.b@example.com']);
        $usuarioB->assignRole('Psicologia/Psicopedagogia');
        $usuarioB->escolas()->attach($escolaB->id);

        $this->actingAs($usuarioA)->post('/secretaria-escolar/psicologia-psicopedagogia/atendimentos', [
            'escola_id' => $escolaA->id,
            'tipo_publico' => 'aluno',
            'aluno_id' => $aluno->id,
            'tipo_atendimento' => 'psicologia',
            'natureza' => 'agendado',
            'status' => 'agendado',
            'data_agendada' => '2026-03-18 10:00:00',
            'motivo_demanda' => 'Acolhimento inicial.',
            'nivel_sigilo' => 'muito_restrito',
        ])->assertRedirect();

        $atendimento = AtendimentoPsicossocial::query()->firstOrFail();

        $this->actingAs($usuarioB)
            ->get("/secretaria-escolar/psicologia-psicopedagogia/atendimentos/{$atendimento->id}")
            ->assertForbidden();
    }

    public function test_secretaria_escolar_sem_permissao_nao_acessa_modulo_sigiloso(): void
    {
        $escola = $this->criarEscola('Escola C', '00.000.000/0001-43');

        $usuario = Usuario::factory()->create(['email' => 'secretaria.sem.sigilo@example.com']);
        $usuario->assignRole('Secretário Escolar');
        $usuario->escolas()->attach($escola->id);

        $this->actingAs($usuario)
            ->get('/secretaria-escolar/psicologia-psicopedagogia')
            ->assertForbidden();
    }

    private function criarContextoBase(): array
    {
        $escola = $this->criarEscola('Escola Psicossocial', '00.000.000/0001-41');

        $aluno = Aluno::create([
            'rgm' => '20262020',
            'nome_completo' => 'Aluno Sigiloso',
            'data_nascimento' => '2015-03-10',
            'sexo' => 'M',
            'nome_mae' => 'Maria Sigilo',
            'responsavel_nome' => 'Maria Sigilo',
            'responsavel_cpf' => '123.456.789-00',
            'responsavel_telefone' => '(85) 99999-1111',
            'cep' => '60000-000',
            'logradouro' => 'Rua Central',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        Funcionario::create([
            'nome' => 'Profissional Psicossocial',
            'cpf' => '12345678901',
            'email' => 'psicossocial.func@example.com',
            'telefone' => '(85) 98888-2222',
            'cargo' => 'Psicopedagogo',
            'ativo' => true,
        ]);

        return [$escola, $aluno];
    }

    private function criarEscola(string $nome, string $cnpj): Escola
    {
        return Escola::create([
            'nome' => $nome,
            'cnpj' => $cnpj,
            'email' => fake()->unique()->safeEmail(),
            'telefone' => '(85) 3333-1010',
            'cep' => '60000-000',
            'endereco' => 'Rua da Escola, 1',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);
    }
}
