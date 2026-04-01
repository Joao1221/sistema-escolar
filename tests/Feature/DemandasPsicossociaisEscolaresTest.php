<?php

namespace Tests\Feature;

use App\Models\AtendidoExterno;
use App\Models\AtendimentoPsicossocial;
use App\Models\DemandaPsicossocial;
use App\Models\DevolutivaPsicossocial;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DemandasPsicossociaisEscolaresTest extends TestCase
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
            'consultar relatorios tecnicos do psicossocial',
            'acessar dados sigilosos psicossociais',
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
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
            'consultar relatorios tecnicos do psicossocial',
            'acessar dados sigilosos psicossociais',
        ]);

        Role::findOrCreate("Coordenador Pedag\u{00F3}gico", 'web')->givePermissionTo([
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
        ]);

        Role::findOrCreate('Diretor Escolar', 'web')->givePermissionTo([
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
        ]);

        Role::findOrCreate("Secret\u{00E1}rio Escolar", 'web');
    }

    public function test_coordenacao_pode_registrar_demanda_escolar_e_fluxo_entra_na_fila_psicossocial(): void
    {
        $escola = $this->criarEscola('Escola Demanda Escolar', '00.000.000/0001-51');
        $coordenador = $this->criarUsuarioEscolar($escola, "Coordenador Pedag\u{00F3}gico", 'coord.psicossocial@example.com');
        [$usuarioPsicossocial] = $this->criarUsuarioPsicossocial($escola, 'Psicologa Escolar', 'psico.demanda@example.com');

        $this->actingAs($coordenador)
            ->post('/secretaria-escolar/psicologia-psicopedagogia/demandas', [
                'escola_id' => $escola->id,
                'tipo_atendimento' => 'psicologia',
                'tipo_publico' => 'coletivo',
                'prioridade' => 'alta',
                'data_solicitacao' => '2026-04-01',
                'motivo_inicial' => 'Solicitacao de roda de conversa sobre convivencia escolar.',
                'observacoes' => 'Atividade prevista para duas turmas do fundamental.',
            ])
            ->assertRedirect();

        $demanda = DemandaPsicossocial::query()->latest('id')->firstOrFail();

        $this->assertDatabaseHas('demandas_psicossociais', [
            'id' => $demanda->id,
            'origem_demanda' => 'coordenacao',
            'tipo_publico' => 'coletivo',
            'aberta_pela_escola' => true,
            'status' => 'aberta',
        ]);

        $this->actingAs($usuarioPsicossocial)
            ->get('/psicologia-psicopedagogia/demandas')
            ->assertOk()
            ->assertSee('Atendimento coletivo')
            ->assertSee('Coordenacao');
    }

    public function test_devolutivas_escolares_sao_filtradas_por_destinatario_do_perfil(): void
    {
        $escola = $this->criarEscola('Escola Devolutiva Escolar', '00.000.000/0001-61');
        $coordenador = $this->criarUsuarioEscolar($escola, "Coordenador Pedag\u{00F3}gico", 'coord.devolutiva@example.com');
        $diretor = $this->criarUsuarioEscolar($escola, 'Diretor Escolar', 'diretor.devolutiva@example.com');
        [$usuarioPsicossocial, $funcionarioPsicossocial] = $this->criarUsuarioPsicossocial($escola, 'Psicologo Responsavel', 'psico.devolutiva@example.com');

        $atendidoColetivo = AtendidoExterno::create([
            'escola_id' => $escola->id,
            'aluno_id' => null,
            'nome' => 'Atendimento coletivo',
            'tipo_vinculo' => 'coletivo',
            'ativo' => false,
        ]);

        $atendimento = AtendimentoPsicossocial::create([
            'escola_id' => $escola->id,
            'usuario_registro_id' => $usuarioPsicossocial->id,
            'profissional_responsavel_id' => $funcionarioPsicossocial->id,
            'atendivel_type' => AtendidoExterno::class,
            'atendivel_id' => $atendidoColetivo->id,
            'tipo_publico' => 'coletivo',
            'tipo_atendimento' => 'psicologia',
            'natureza' => 'agendado',
            'status' => 'em_acompanhamento',
            'data_agendada' => '2026-04-03 09:00:00',
            'data_realizacao' => '2026-04-03 09:30:00',
            'motivo_demanda' => 'Demanda coletiva iniciada pela escola.',
            'nivel_sigilo' => 'normal',
        ]);

        $demanda = DemandaPsicossocial::create([
            'escola_id' => $escola->id,
            'usuario_registro_id' => $coordenador->id,
            'profissional_responsavel_id' => $funcionarioPsicossocial->id,
            'tipo_atendimento' => 'psicologia',
            'origem_demanda' => 'coordenacao',
            'tipo_publico' => 'coletivo',
            'motivo_inicial' => 'Acompanhamento coletivo solicitado pela escola.',
            'prioridade' => 'media',
            'status' => 'em_atendimento',
            'data_solicitacao' => '2026-04-01',
            'encaminhado_para_atendimento' => true,
            'atendimento_id' => $atendimento->id,
            'aberta_pela_escola' => true,
        ]);

        DevolutivaPsicossocial::create([
            'atendimento_id' => $atendimento->id,
            'usuario_responsavel_id' => $usuarioPsicossocial->id,
            'destinatario' => 'coordenacao',
            'data_devolutiva' => '2026-04-04',
            'resumo_devolutiva' => 'Devolutiva exclusiva da coordenacao.',
            'orientacoes' => 'Organizar acompanhamento pedagogico em sala.',
            'necessita_acompanhamento' => true,
        ]);

        DevolutivaPsicossocial::create([
            'atendimento_id' => $atendimento->id,
            'usuario_responsavel_id' => $usuarioPsicossocial->id,
            'destinatario' => 'direcao',
            'data_devolutiva' => '2026-04-05',
            'resumo_devolutiva' => 'Devolutiva exclusiva da direcao.',
            'orientacoes' => 'Validar agenda institucional para atividade coletiva.',
            'necessita_acompanhamento' => false,
        ]);

        $this->actingAs($coordenador)
            ->get(route('secretaria-escolar.demandas-psicossociais.show', $demanda))
            ->assertOk()
            ->assertSee('Devolutiva exclusiva da coordenacao.')
            ->assertDontSee('Devolutiva exclusiva da direcao.');

        $this->actingAs($diretor)
            ->get(route('secretaria-escolar.demandas-psicossociais.show', $demanda))
            ->assertOk()
            ->assertSee('Devolutiva exclusiva da direcao.')
            ->assertDontSee('Devolutiva exclusiva da coordenacao.');
    }

    public function test_secretario_sem_permissao_nao_acessa_demandas_psicossociais_escolares(): void
    {
        $escola = $this->criarEscola('Escola Restrita', '00.000.000/0001-71');
        $secretario = $this->criarUsuarioEscolar($escola, "Secret\u{00E1}rio Escolar", 'secretario.restrito@example.com');

        $this->actingAs($secretario)
            ->get('/secretaria-escolar/psicologia-psicopedagogia/demandas')
            ->assertForbidden();
    }

    private function criarUsuarioEscolar(Escola $escola, string $role, string $email): Usuario
    {
        $usuario = Usuario::factory()->create([
            'email' => $email,
        ]);
        $usuario->assignRole($role);
        $usuario->escolas()->attach($escola->id);

        return $usuario;
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
