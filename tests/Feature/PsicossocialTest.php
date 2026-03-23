<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\AtendimentoPsicossocial;
use App\Models\DemandaPsicossocial;
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

        [$usuario] = $this->criarUsuarioPsicossocial($escola, 'Profissional Psicologia 1', 'psico@example.com');

        $this->actingAs($usuario)->post('/psicologia-psicopedagogia/atendimentos', [
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

        $this->actingAs($usuario)->post('/psicologia-psicopedagogia/atendimentos', [
            'escola_id' => $escola->id,
            'tipo_publico' => 'responsavel',
            'aluno_id' => $aluno->id,
            'responsavel_nome' => 'Agenda Futura',
            'responsavel_tipo_vinculo' => 'mae',
            'responsavel_telefone' => '(85) 98888-3333',
            'tipo_atendimento' => 'psicopedagogia',
            'natureza' => 'agendado',
            'status' => 'agendado',
            'data_agendada' => '2026-03-19 09:00:00',
            'motivo_demanda' => 'Agendamento futuro. ',
            'resumo_sigiloso' => 'Registro agendado para fluxo tecnico.',
            'nivel_sigilo' => 'muito_restrito',
        ])->assertRedirect();

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

        $agendaResponse = $this->actingAs($usuario)->get('/psicologia-psicopedagogia/agenda');
        $agendaResponse->assertOk();
        $agendaResponse->assertSee('Agenda Futura');
        $agendaResponse->assertDontSee('Maria Responsavel');

        $atendimentosResponse = $this->actingAs($usuario)->followingRedirects()->get('/psicologia-psicopedagogia/atendimentos');
        $atendimentosResponse->assertOk();
        $atendimentosResponse->assertSee('Maria Responsavel');
        $atendimentosResponse->assertDontSee('Agenda Futura');

        $historicoResponse = $this->actingAs($usuario)->get('/psicologia-psicopedagogia/historico');
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

    public function test_profissional_nao_acessa_atendimento_de_outro_profissional_mesmo_na_mesma_escola(): void
    {
        [$escola, $aluno] = $this->criarContextoBase();

        [$usuarioA, $funcionarioA] = $this->criarUsuarioPsicossocial($escola, 'Psicologo A', 'psico.a@example.com');
        [$usuarioB] = $this->criarUsuarioPsicossocial($escola, 'Psicopedagoga B', 'psico.b@example.com');

        $this->actingAs($usuarioA)->post('/psicologia-psicopedagogia/atendimentos', [
            'escola_id' => $escola->id,
            'profissional_responsavel_id' => $funcionarioA->id,
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
            ->get("/psicologia-psicopedagogia/atendimentos/{$atendimento->id}")
            ->assertForbidden();

        $this->actingAs($usuarioB)
            ->get('/psicologia-psicopedagogia/agenda')
            ->assertOk()
            ->assertDontSee('Aluno Sigiloso');
    }

    public function test_secretaria_escolar_sem_permissao_nao_acessa_modulo_sigiloso(): void
    {
        $escola = $this->criarEscola('Escola C', '00.000.000/0001-43');

        $usuario = Usuario::factory()->create(['email' => 'secretaria.sem.sigilo@example.com']);
        $usuario->assignRole('Secretário Escolar');
        $usuario->escolas()->attach($escola->id);

        $this->actingAs($usuario)
            ->get('/psicologia-psicopedagogia')
            ->assertForbidden();
    }

    public function test_perfil_psicologia_visualiza_todas_as_escolas_no_seletor_do_portal(): void
    {
        $this->criarEscola('Escola Norte', '00.000.000/0001-44');
        $this->criarEscola('Escola Sul', '00.000.000/0001-45');

        $usuario = Usuario::factory()->create(['email' => 'psico.todas.escolas@example.com']);
        $usuario->assignRole('Psicologia/Psicopedagogia');

        $response = $this->actingAs($usuario)->get('/psicologia-psicopedagogia/atendimentos/criar');

        $response->assertOk();
        $response->assertSee('Escola Norte');
        $response->assertSee('Escola Sul');
    }

    public function test_encerrar_atendimento_sincroniza_status_da_demanda_vinculada(): void
    {
        [$escola, $aluno] = $this->criarContextoBase();
        [$usuario, $funcionario] = $this->criarUsuarioPsicossocial($escola, 'Psicologo Encerramento', 'psico.encerramento@example.com');

        $atendimento = AtendimentoPsicossocial::create([
            'escola_id' => $escola->id,
            'usuario_registro_id' => $usuario->id,
            'profissional_responsavel_id' => $funcionario->id,
            'atendivel_type' => Aluno::class,
            'atendivel_id' => $aluno->id,
            'tipo_publico' => 'aluno',
            'tipo_atendimento' => 'psicologia',
            'natureza' => 'agendado',
            'status' => 'em_acompanhamento',
            'data_agendada' => '2026-03-18 09:00:00',
            'data_realizacao' => '2026-03-18 09:30:00',
            'local_atendimento' => 'Sala tecnica',
            'motivo_demanda' => 'Acompanhamento emocional.',
            'resumo_sigiloso' => 'Resumo do caso.',
            'nivel_sigilo' => 'muito_restrito',
            'requer_acompanhamento' => true,
        ]);

        $demanda = DemandaPsicossocial::create([
            'escola_id' => $escola->id,
            'usuario_registro_id' => $usuario->id,
            'profissional_responsavel_id' => $funcionario->id,
            'tipo_atendimento' => 'psicologia',
            'origem_demanda' => 'familia',
            'tipo_publico' => 'aluno',
            'aluno_id' => $aluno->id,
            'motivo_inicial' => 'Solicitacao da familia.',
            'prioridade' => 'media',
            'status' => 'em_atendimento',
            'data_solicitacao' => '2026-03-17',
            'encaminhado_para_atendimento' => true,
            'atendimento_id' => $atendimento->id,
        ]);

        $this->actingAs($usuario)->post("/psicologia-psicopedagogia/atendimentos/{$atendimento->id}/encerrar", [
            'data_encerramento' => '2026-03-23',
            'motivo_encerramento' => 'Objetivos alcancados.',
            'resumo_encerramento' => 'Evolucao satisfatoria.',
            'orientacoes_finais' => 'Monitorar e retornar se necessario.',
        ])->assertRedirect();

        $this->assertDatabaseHas('atendimentos_psicossociais', [
            'id' => $atendimento->id,
            'status' => 'encerrado',
        ]);

        $this->assertDatabaseHas('demandas_psicossociais', [
            'id' => $demanda->id,
            'status' => 'encerrada',
        ]);

        $this->actingAs($usuario)
            ->get('/psicologia-psicopedagogia/historico')
            ->assertOk()
            ->assertSee('Aluno Sigiloso')
            ->assertSee('Encerrado');

        $this->actingAs($usuario)
            ->get('/psicologia-psicopedagogia/demandas')
            ->assertOk()
            ->assertSee('Aluno Sigiloso')
            ->assertSee('Encerrada');

        $this->actingAs($usuario)
            ->get("/psicologia-psicopedagogia/demandas/{$demanda->id}")
            ->assertOk()
            ->assertSee('Encerrada')
            ->assertDontSee('Ver atendimento');
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

    private function criarUsuarioPsicossocial(Escola $escola, string $nome, string $email): array
    {
        $funcionario = Funcionario::create([
            'nome' => $nome,
            'cpf' => fake()->unique()->numerify('###########'),
            'email' => $email,
            'telefone' => '(85) 98888-5555',
            'cargo' => 'Psicologia/Psicopedagogia',
            'ativo' => true,
        ]);
        $funcionario->escolas()->attach($escola->id);

        $usuario = Usuario::factory()->create([
            'email' => $email,
            'funcionario_id' => $funcionario->id,
        ]);
        $usuario->assignRole('Psicologia/Psicopedagogia');
        $usuario->escolas()->attach($escola->id);

        return [$usuario, $funcionario];
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
