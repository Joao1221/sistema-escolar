<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\DiarioProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\ModalidadeEnsino;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\RegistroAula;
use App\Models\Turma;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CoordenacaoPedagogicaTest extends TestCase
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
            'criar diarios',
            'registrar aulas',
            'lancar frequencia',
            'gerenciar planejamentos',
            'gerenciar pendencias do professor',
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar planejamento por periodo',
            'validar aulas registradas',
            'acompanhar frequencia pedagogica',
            'acompanhar rendimento pedagogico',
            'acompanhar alunos em risco',
            'gerenciar pendencias docentes',
            'consultar notas e conceitos pedagogicos',
            'alterar notas e conceitos pedagogicos',
            'consultar horarios pedagogicamente',
            'cadastrar horarios pedagogicamente',
            'editar horarios pedagogicamente',
            'reorganizar horarios pedagogicamente',
            'consultar aulas pedagogicamente',
            'ajustar aulas pedagogicamente',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Professor', 'web')->givePermissionTo([
            'criar diarios',
            'registrar aulas',
            'lancar frequencia',
            'gerenciar planejamentos',
            'gerenciar pendencias do professor',
        ]);

        Role::findOrCreate('Coordenador Pedagógico', 'web')->givePermissionTo([
            'consultar diarios',
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar planejamento por periodo',
            'validar aulas registradas',
            'acompanhar frequencia pedagogica',
            'acompanhar rendimento pedagogico',
            'acompanhar alunos em risco',
            'gerenciar pendencias docentes',
            'consultar notas e conceitos pedagogicos',
            'alterar notas e conceitos pedagogicos',
            'consultar horarios pedagogicamente',
            'cadastrar horarios pedagogicamente',
            'editar horarios pedagogicamente',
            'reorganizar horarios pedagogicamente',
            'consultar aulas pedagogicamente',
            'ajustar aulas pedagogicamente',
        ]);

        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo([
            'consultar diarios',
        ]);
    }

    public function test_coordenacao_pode_visualizar_diario_e_validar_planejamento_anual(): void
    {
        [$usuarioProfessor, $escola, $turma, $disciplina, $diario, $matricula] = $this->criarContextoDiario();

        $planejamento = PlanejamentoAnual::create([
            'diario_professor_id' => $diario->id,
            'tema_gerador' => 'Leitura e interpretacao',
            'objetivos_gerais' => 'Desenvolver leitura e escrita.',
        ]);

        $coordenador = Usuario::factory()->create(['email' => 'coord@example.com']);
        $coordenador->assignRole('Coordenador Pedagógico');
        $coordenador->escolas()->attach($escola->id);

        $response = $this->actingAs($coordenador)->post(
            '/secretaria-escolar/coordenacao-pedagogica/diarios/' . $diario->id . '/planejamento-anual/' . $planejamento->id . '/validacao',
            [
                'status' => 'validado',
                'parecer' => 'Planejamento alinhado com os objetivos da etapa e pronto para acompanhamento.',
                'observacoes_internas' => 'Liberado pela coordenacao.',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('validacoes_pedagogicas', [
            'diario_professor_id' => $diario->id,
            'validavel_type' => PlanejamentoAnual::class,
            'validavel_id' => $planejamento->id,
            'status' => 'validado',
        ]);
    }

    public function test_coordenacao_pode_registrar_acompanhamento_de_aluno_em_risco(): void
    {
        [$usuarioProfessor, $escola, $turma, $disciplina, $diario, $matricula] = $this->criarContextoDiario();

        $registro = RegistroAula::create([
            'diario_professor_id' => $diario->id,
            'usuario_registro_id' => $usuarioProfessor->id,
            'data_aula' => '2026-03-18',
            'titulo' => 'Texto narrativo',
            'conteudo_ministrado' => 'Leitura compartilhada e interpretacao.',
            'quantidade_aulas' => 1,
            'aula_dada' => true,
        ]);

        $registro->frequencias()->create([
            'matricula_id' => $matricula->id,
            'situacao' => 'falta',
        ]);

        $coordenador = Usuario::factory()->create(['email' => 'coord.risco@example.com']);
        $coordenador->assignRole('Coordenador Pedagógico');
        $coordenador->escolas()->attach($escola->id);

        $response = $this->actingAs($coordenador)->post(
            '/secretaria-escolar/coordenacao-pedagogica/diarios/' . $diario->id . '/acompanhamentos/' . $matricula->id,
            [
                'nivel_rendimento' => 'defasado',
                'situacao_risco' => 'alto',
                'indicativos_aprendizagem' => 'Apresenta baixa participacao e dificuldade de acompanhamento das atividades.',
                'fatores_risco' => 'Frequencia irregular e pouca devolutiva nas atividades.',
                'encaminhamentos' => 'Planejar intervencao com o professor e contato com a familia.',
                'precisa_intervencao' => '1',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('acompanhamentos_pedagogicos_aluno', [
            'diario_professor_id' => $diario->id,
            'matricula_id' => $matricula->id,
            'situacao_risco' => 'alto',
            'precisa_intervencao' => true,
        ]);
    }

    public function test_secretario_sem_permissoes_da_coordenacao_nao_acessa_modulo(): void
    {
        [, $escola, , , $diario] = $this->criarContextoDiario();

        $secretario = Usuario::factory()->create(['email' => 'secretaria.modulo@example.com']);
        $secretario->assignRole('Secretário Escolar');
        $secretario->escolas()->attach($escola->id);

        $response = $this->actingAs($secretario)->get('/secretaria-escolar/coordenacao-pedagogica/diarios/' . $diario->id);

        $response->assertForbidden();
    }

    public function test_coordenacao_pode_validar_planejamento_por_periodo_e_alterar_nota(): void
    {
        [$usuarioProfessor, $escola, , , $diario, $matricula] = $this->criarContextoDiario();

        $planejamentoPeriodo = PlanejamentoPeriodo::create([
            'diario_professor_id' => $diario->id,
            'tipo_planejamento' => 'mensal',
            'periodo_referencia' => 'Abril/2026',
            'data_inicio' => '2026-04-01',
            'data_fim' => '2026-04-30',
            'objetivos_aprendizagem' => 'Consolidar leitura e escrita.',
            'conteudos' => 'Interpretação textual.',
        ]);

        $avaliacao = LancamentoAvaliativo::create([
            'diario_professor_id' => $diario->id,
            'matricula_id' => $matricula->id,
            'usuario_registro_id' => $usuarioProfessor->id,
            'tipo_avaliacao' => 'nota',
            'avaliacao_referencia' => 'Prova 1',
            'valor_numerico' => 6.5,
        ]);

        $coordenador = Usuario::factory()->create(['email' => 'coord.avaliacao@example.com']);
        $coordenador->assignRole('Coordenador Pedagógico');
        $coordenador->escolas()->attach($escola->id);

        $this->actingAs($coordenador)->post(
            '/secretaria-escolar/coordenacao-pedagogica/diarios/' . $diario->id . '/planejamentos-periodo/' . $planejamentoPeriodo->id . '/validacao',
            [
                'status' => 'validado',
                'parecer' => 'Planejamento mensal aderente a proposta da escola.',
                'observacoes_internas' => 'Liberado para acompanhamento.',
            ]
        )->assertRedirect();

        $this->actingAs($coordenador)->put(
            '/secretaria-escolar/coordenacao-pedagogica/diarios/' . $diario->id . '/avaliacoes/' . $avaliacao->id,
            [
                'avaliacao_referencia' => 'Prova 1 revisada',
                'valor_numerico' => 7.75,
                'observacoes' => 'Nota ajustada apos revisao pedagogica.',
            ]
        )->assertRedirect();

        $this->assertDatabaseHas('validacoes_pedagogicas', [
            'diario_professor_id' => $diario->id,
            'validavel_type' => PlanejamentoPeriodo::class,
            'validavel_id' => $planejamentoPeriodo->id,
            'status' => 'validado',
        ]);

        $this->assertDatabaseHas('planejamentos_periodo', [
            'id' => $planejamentoPeriodo->id,
            'status' => 'aprovado',
        ]);

        $this->assertDatabaseHas('lancamentos_avaliativos', [
            'id' => $avaliacao->id,
            'avaliacao_referencia' => 'Prova 1 revisada',
            'valor_numerico' => 7.75,
        ]);
    }

    public function test_coordenacao_pode_cadastrar_horario_e_ajustar_aula(): void
    {
        [$usuarioProfessor, $escola, $turma, $disciplina, $diario] = $this->criarContextoDiario();

        $registro = RegistroAula::create([
            'diario_professor_id' => $diario->id,
            'usuario_registro_id' => $usuarioProfessor->id,
            'data_aula' => '2026-03-20',
            'titulo' => 'Aula original',
            'conteudo_ministrado' => 'Conteudo original.',
            'quantidade_aulas' => 1,
            'aula_dada' => true,
        ]);

        $professor = Funcionario::where('email', 'prof.coord@example.com')->firstOrFail();

        $coordenador = Usuario::factory()->create(['email' => 'coord.horario@example.com']);
        $coordenador->assignRole('Coordenador Pedagógico');
        $coordenador->escolas()->attach($escola->id);

        $this->actingAs($coordenador)->post('/secretaria-escolar/coordenacao-pedagogica/horarios', [
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'horarios' => [
                [
                    'dia_semana' => 4,
                    'horario_inicial' => '08:00',
                    'horario_final' => '08:50',
                    'disciplina_id' => $disciplina->id,
                    'professor_id' => $professor->id,
                    'ordem_aula' => 2,
                ],
            ],
        ])->assertRedirect();

        $this->actingAs($coordenador)->put(
            '/secretaria-escolar/coordenacao-pedagogica/diarios/' . $diario->id . '/registros-aula/' . $registro->id,
            [
                'data_aula' => '2026-03-21',
                'titulo' => 'Aula ajustada',
                'conteudo_previsto' => 'Conteudo previsto ajustado.',
                'conteudo_ministrado' => 'Conteudo ajustado pela coordenacao.',
                'metodologia' => 'Roda de conversa.',
                'recursos_utilizados' => 'Quadro e livro.',
                'quantidade_aulas' => 2,
                'aula_dada' => '1',
            ]
        )->assertRedirect();

        $this->assertDatabaseHas('horario_aulas', [
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'dia_semana' => 4,
            'horario_inicial' => '08:00',
        ]);

        $this->assertDatabaseHas('registros_aula', [
            'id' => $registro->id,
            'titulo' => 'Aula ajustada',
            'quantidade_aulas' => 2,
        ]);
    }

    private function criarContextoDiario(): array
    {
        $escola = Escola::create([
            'nome' => 'Escola Municipal da Coordenacao',
            'cnpj' => '00.000.000/0001-77',
            'email' => 'escola.coordenacao@example.com',
            'telefone' => '(88) 3333-7777',
            'cep' => '60000-000',
            'endereco' => 'Rua Central, 50',
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

        $turma = Turma::create([
            'escola_id' => $escola->id,
            'modalidade_id' => $modalidade->id,
            'serie_etapa' => '5 Ano',
            'nome' => '5 Ano B',
            'turno' => 'Matutino',
            'ano_letivo' => 2026,
            'vagas' => 30,
            'is_multisseriada' => false,
            'ativa' => true,
        ]);

        $disciplina = Disciplina::create([
            'nome' => 'Historia',
            'codigo' => 'HIS501',
            'carga_horaria_sugerida' => 80,
            'ativo' => true,
        ]);

        $funcionarioProfessor = Funcionario::create([
            'nome' => 'Professor Coordenado',
            'cpf' => '12345678999',
            'email' => 'prof.coord@example.com',
            'telefone' => '(88) 99999-1111',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);
        $funcionarioProfessor->escolas()->attach($escola->id);

        $usuarioProfessor = Usuario::factory()->create([
            'email' => 'prof.coord@example.com',
            'funcionario_id' => $funcionarioProfessor->id,
        ]);
        $usuarioProfessor->assignRole('Professor');
        $usuarioProfessor->escolas()->attach($escola->id);

        HorarioAula::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $funcionarioProfessor->id,
            'dia_semana' => 2,
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
            'rgm' => '20261234',
            'nome_completo' => 'Aluno em Acompanhamento',
            'data_nascimento' => '2015-02-10',
            'sexo' => 'M',
            'cpf' => '123.456.789-10',
            'nis' => null,
            'nome_mae' => 'Maria Acompanhamento',
            'nome_pai' => null,
            'responsavel_nome' => 'Maria Acompanhamento',
            'responsavel_cpf' => '123.456.789-11',
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

        return [$usuarioProfessor, $escola, $turma, $disciplina, $diario, $matricula];
    }
}
