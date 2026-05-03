<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\DiarioProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\FrequenciaAula;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\ModalidadeEnsino;
use App\Models\PlanejamentoPeriodo;
use App\Models\RegistroAula;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DirecaoEscolarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
            'consultar diarios',
            'acompanhar diarios da direcao',
            'validar planejamento pela direcao',
            'validar planejamento por periodo pela direcao',
            'validar aulas pela direcao',
            'consultar notas e conceitos da direcao',
            'alterar notas e conceitos da direcao',
            'justificar faltas de alunos',
            'liberar prazo de lancamento',
            'consultar horarios da direcao',
            'cadastrar horarios da direcao',
            'editar horarios da direcao',
            'reorganizar horarios da direcao',
            'consultar aulas da direcao',
            'ajustar aulas da direcao',
            'registrar faltas de funcionarios',
            'iniciar fechamento letivo',
            'concluir fechamento letivo',
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar aulas registradas',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Diretor Escolar', 'web')->givePermissionTo([
            'consultar diarios',
            'acompanhar diarios da direcao',
            'validar planejamento pela direcao',
            'validar planejamento por periodo pela direcao',
            'validar aulas pela direcao',
            'consultar notas e conceitos da direcao',
            'alterar notas e conceitos da direcao',
            'justificar faltas de alunos',
            'liberar prazo de lancamento',
            'consultar horarios da direcao',
            'cadastrar horarios da direcao',
            'editar horarios da direcao',
            'reorganizar horarios da direcao',
            'consultar aulas da direcao',
            'ajustar aulas da direcao',
            'registrar faltas de funcionarios',
            'iniciar fechamento letivo',
            'concluir fechamento letivo',
        ]);

        Role::findOrCreate('Coordenador Restrito', 'web')->givePermissionTo([
            'consultar diarios',
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar aulas registradas',
        ]);
    }

    public function test_direcao_pode_justificar_falta_e_liberar_prazo(): void
    {
        [$escola, $funcionarioProfessor, $diario, $frequencia] = $this->criarContextoDiario();

        $diretor = Usuario::factory()->create(['email' => 'diretor@example.com']);
        $diretor->assignRole('Diretor Escolar');
        $diretor->escolas()->attach($escola->id);

        $responseJustificativa = $this->actingAs($diretor)->post(
            '/secretaria-escolar/direcao-escolar/diarios/' . $diario->id . '/frequencias/' . $frequencia->id . '/justificativa',
            [
                'motivo' => 'Atestado medico apresentado pela familia.',
                'documento_comprobatorio' => 'Atestado 2026/03',
            ]
        );

        $responseJustificativa->assertRedirect();
        $this->assertDatabaseHas('justificativas_falta_aluno', [
            'frequencia_aula_id' => $frequencia->id,
            'diario_professor_id' => $diario->id,
            'situacao_atual' => 'falta_justificada',
        ]);
        $this->assertDatabaseHas('frequencias_aula', [
            'id' => $frequencia->id,
            'situacao' => 'falta_justificada',
        ]);

        $responseLiberacao = $this->actingAs($diretor)->post(
            '/secretaria-escolar/direcao-escolar/diarios/' . $diario->id . '/liberacoes-prazo',
            [
                'tipo_lancamento' => 'frequencia',
                'data_limite' => now()->addDays(2)->toDateString(),
                'motivo' => 'Ajuste excepcional apos reuniao com a equipe.',
                'observacoes' => 'Registrar ate o final do expediente.',
            ]
        );

        $responseLiberacao->assertRedirect();
        $this->assertDatabaseHas('liberacoes_prazo_professor', [
            'diario_professor_id' => $diario->id,
            'professor_id' => $funcionarioProfessor->id,
            'tipo_lancamento' => 'frequencia',
            'status' => 'ativa',
        ]);
    }

    public function test_direcao_pode_registrar_falta_funcional_e_fechamento_letivo(): void
    {
        [$escola, $funcionarioProfessor] = $this->criarContextoDiario();

        $diretor = Usuario::factory()->create(['email' => 'diretor.fechamento@example.com']);
        $diretor->assignRole('Diretor Escolar');
        $diretor->escolas()->attach($escola->id);

        $responseFalta = $this->actingAs($diretor)->post(
            '/secretaria-escolar/direcao-escolar/faltas-funcionarios',
            [
                'escola_id' => $escola->id,
                'funcionario_id' => $funcionarioProfessor->id,
                'data_falta' => '2026-03-22',
                'turno' => 'matutino',
                'tipo_falta' => 'falta',
                'justificada' => '1',
                'motivo' => 'Participacao em formacao externa.',
                'observacoes' => 'Reposicao combinada com a equipe gestora.',
            ]
        );

        $responseFalta->assertRedirect();
        $this->assertDatabaseHas('faltas_funcionarios', [
            'escola_id' => $escola->id,
            'funcionario_id' => $funcionarioProfessor->id,
            'justificada' => true,
        ]);

        $responseFechamento = $this->actingAs($diretor)->post(
            '/secretaria-escolar/direcao-escolar/fechamento-letivo',
            [
                'escola_id' => $escola->id,
                'ano_letivo' => 2026,
                'status' => 'concluido',
                'resumo' => 'Fluxo inicial de fechamento conferido pela direcao.',
                'observacoes' => 'Sem pendencias abertas para o ano letivo.',
            ]
        );

        $responseFechamento->assertRedirect();
        $this->assertDatabaseHas('fechamentos_letivos', [
            'escola_id' => $escola->id,
            'ano_letivo' => 2026,
            'status' => 'concluido',
        ]);
    }

    public function test_coordenacao_nao_acessa_area_privativa_da_direcao(): void
    {
        [$escola, , $diario] = $this->criarContextoDiario();

        $coordenador = Usuario::factory()->create(['email' => 'coord.restrito@example.com']);
        $coordenador->assignRole('Coordenador Restrito');
        $coordenador->escolas()->attach($escola->id);

        $response = $this->actingAs($coordenador)->get('/secretaria-escolar/direcao-escolar/diarios/' . $diario->id);

        $response->assertForbidden();
    }

    public function test_direcao_pode_validar_planejamento_por_periodo_e_alterar_nota(): void
    {
        [$escola, , $diario] = $this->criarContextoDiario();

        $planejamento = PlanejamentoPeriodo::create([
            'diario_professor_id' => $diario->id,
            'tipo_planejamento' => 'mensal',
            'periodo_referencia' => 'Abril/2026',
            'data_inicio' => '2026-04-01',
            'data_fim' => '2026-04-30',
            'objetivos_aprendizagem' => 'Consolidar leitura cartografica.',
            'conteudos' => 'Mapas e escalas.',
        ]);

        $matricula = Matricula::firstOrFail();
        $usuarioProfessor = Usuario::where('email', 'prof.direcao@example.com')->firstOrFail();

        $avaliacao = LancamentoAvaliativo::create([
            'diario_professor_id' => $diario->id,
            'matricula_id' => $matricula->id,
            'usuario_registro_id' => $usuarioProfessor->id,
            'tipo_avaliacao' => 'nota',
            'avaliacao_referencia' => 'Prova 1',
            'valor_numerico' => 6.00,
        ]);

        $diretor = Usuario::factory()->create(['email' => 'diretor.avaliacao@example.com']);
        $diretor->assignRole('Diretor Escolar');
        $diretor->escolas()->attach($escola->id);

        $this->actingAs($diretor)->post(
            '/secretaria-escolar/direcao-escolar/diarios/' . $diario->id . '/planejamentos-periodo/' . $planejamento->id . '/validacao',
            [
                'status' => 'validado',
                'parecer' => 'Planejamento mensal aprovado pela direcao.',
                'observacoes_internas' => 'Sem ressalvas.',
            ]
        )->assertRedirect();

        $this->actingAs($diretor)->put(
            '/secretaria-escolar/direcao-escolar/diarios/' . $diario->id . '/avaliacoes/' . $avaliacao->id,
            [
                'avaliacao_referencia' => 'Prova 1 revisada',
                'valor_numerico' => 8.25,
                'observacoes' => 'Ajuste autorizado pela direcao.',
            ]
        )->assertRedirect();

        $this->assertDatabaseHas('validacoes_direcao', [
            'diario_professor_id' => $diario->id,
            'validavel_type' => PlanejamentoPeriodo::class,
            'validavel_id' => $planejamento->id,
            'status' => 'validado',
        ]);

        $this->assertDatabaseHas('planejamentos_periodo', [
            'id' => $planejamento->id,
            'status' => 'aprovado',
        ]);

        $this->assertDatabaseHas('lancamentos_avaliativos', [
            'id' => $avaliacao->id,
            'avaliacao_referencia' => 'Prova 1 revisada',
            'valor_numerico' => 8.25,
        ]);
    }

    public function test_direcao_pode_gerir_horarios_e_ajustar_aula(): void
    {
        [$escola, $funcionarioProfessor, $diario] = $this->criarContextoDiario();

        $turma = \App\Models\Turma::firstOrFail();
        $disciplina = Disciplina::firstOrFail();

        $registro = RegistroAula::firstOrFail();

        $diretor = Usuario::factory()->create(['email' => 'diretor.horario@example.com']);
        $diretor->assignRole('Diretor Escolar');
        $diretor->escolas()->attach($escola->id);

        $this->actingAs($diretor)->post('/secretaria-escolar/direcao-escolar/horarios', [
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'horarios' => [
                [
                    'dia_semana' => 5,
                    'horario_inicial' => '08:00',
                    'horario_final' => '08:50',
                    'disciplina_id' => $disciplina->id,
                    'professor_id' => $funcionarioProfessor->id,
                    'ordem_aula' => 2,
                ],
            ],
        ])->assertRedirect();

        $this->actingAs($diretor)->put(
            '/secretaria-escolar/direcao-escolar/diarios/' . $diario->id . '/registros-aula/' . $registro->id,
            [
                'data_aula' => '2026-03-19',
                'titulo' => 'Aula ajustada pela direcao',
                'conteudo_previsto' => 'Conteudo previsto atualizado.',
                'conteudo_ministrado' => 'Leitura cartografica aprofundada.',
                'metodologia' => 'Estudo dirigido.',
                'recursos_utilizados' => 'Atlas e projetor.',
                'quantidade_aulas' => 2,
                'aula_dada' => '1',
            ]
        )->assertRedirect();

        $this->assertDatabaseHas('horario_aulas', [
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'dia_semana' => 5,
            'horario_inicial' => '08:00',
        ]);

        $this->assertDatabaseHas('registros_aula', [
            'id' => $registro->id,
            'titulo' => 'Aula ajustada pela direcao',
            'quantidade_aulas' => 2,
        ]);
    }

    private function criarContextoDiario(): array
    {
        $escola = Escola::create([
            'nome' => 'Escola Municipal da Direcao',
            'cnpj' => '00.000.000/0001-66',
            'email' => 'escola.direcao@example.com',
            'telefone' => '(88) 3333-6666',
            'cep' => '60000-000',
            'endereco' => 'Rua da Gestao, 10',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        $modalidade = ModalidadeEnsino::create([
            'nome' => 'Ensino Fundamental',
            'estrutura_avaliativa' => 'Bimestral',
            'tipo_avaliacao' => 'Nota',
            'carga_horaria_minima' => 800,
            'ativo' => true,
        ]);

        $turma = \App\Models\Turma::create([
            'escola_id' => $escola->id,
            'modalidade_id' => $modalidade->id,
            'serie_etapa' => '6 Ano',
            'nome' => '6 Ano A',
            'turno' => 'Matutino',
            'ano_letivo' => 2026,
            'vagas' => 30,
            'is_multisseriada' => false,
            'ativa' => true,
        ]);

        $disciplina = Disciplina::create([
            'nome' => 'Geografia',
            'codigo' => 'GEO601',
            'carga_horaria_sugerida' => 80,
            'ativo' => true,
        ]);

        $funcionarioProfessor = Funcionario::create([
            'nome' => 'Professor da Direcao',
            'cpf' => '12345678911',
            'email' => 'prof.direcao@example.com',
            'telefone' => '(88) 99999-1111',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);
        $funcionarioProfessor->escolas()->attach($escola->id);

        $usuarioProfessor = Usuario::factory()->create([
            'email' => 'prof.direcao@example.com',
            'funcionario_id' => $funcionarioProfessor->id,
        ]);
        $usuarioProfessor->escolas()->attach($escola->id);

        HorarioAula::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $funcionarioProfessor->id,
            'dia_semana' => 3,
            'horario_inicial' => '07:00',
            'horario_final' => '07:50',
            'ordem_aula' => 1,
            'ativo' => true,
        ]);

        $diario = DiarioProfessor::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $funcionarioProfessor->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '1',
            'situacao' => 'em_andamento',
        ]);

        $aluno = Aluno::create([
            'rgm' => '20261235',
            'nome_completo' => 'Aluno da Direcao',
            'data_nascimento' => '2014-08-10',
            'sexo' => 'F',
            'cpf' => '123.456.789-20',
            'nis' => null,
            'nome_mae' => 'Maria Direcao',
            'nome_pai' => null,
            'responsavel_nome' => 'Maria Direcao',
            'responsavel_cpf' => '123.456.789-21',
            'responsavel_telefone' => '(88) 98888-2222',
            'cep' => '60000-001',
            'logradouro' => 'Rua B',
            'numero' => '120',
            'complemento' => null,
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'certidao_nascimento' => null,
            'rg_numero' => null,
            'rg_orgao' => null,
            'alergias' => null,
            'medicamentos' => null,
            'restricoes_alimentares' => null,
            'obs_saude' => null,
            'ativo' => true,
        ]);

        $matricula = Matricula::create([
            'aluno_id' => $aluno->id,
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'ano_letivo' => 2026,
            'tipo' => 'regular',
            'status' => 'ativa',
            'matricula_regular_id' => null,
            'data_matricula' => now()->toDateString(),
            'data_encerramento' => null,
            'observacoes' => null,
        ]);

        $registro = RegistroAula::create([
            'diario_professor_id' => $diario->id,
            'usuario_registro_id' => $usuarioProfessor->id,
            'data_aula' => '2026-03-18',
            'titulo' => 'Mapas e territorio',
            'conteudo_ministrado' => 'Leitura cartografica introdutoria.',
            'quantidade_aulas' => 1,
            'aula_dada' => true,
        ]);

        $frequencia = FrequenciaAula::create([
            'registro_aula_id' => $registro->id,
            'matricula_id' => $matricula->id,
            'situacao' => 'falta',
        ]);

        return [$escola, $funcionarioProfessor, $diario, $frequencia];
    }
}
