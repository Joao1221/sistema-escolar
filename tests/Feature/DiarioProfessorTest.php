<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\DiarioProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\Matricula;
use App\Models\ModalidadeEnsino;
use App\Models\Turma;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DiarioProfessorTest extends TestCase
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
            'registrar observacoes pedagogicas',
            'registrar ocorrencias pedagogicas',
            'gerenciar pendencias do professor',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Professor', 'web')->givePermissionTo([
            'criar diarios',
            'registrar aulas',
            'lancar frequencia',
            'gerenciar planejamentos',
            'registrar observacoes pedagogicas',
            'registrar ocorrencias pedagogicas',
            'gerenciar pendencias do professor',
        ]);

        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo([
            'consultar diarios',
        ]);
    }

    public function test_professor_pode_criar_diario_a_partir_do_seu_horario(): void
    {
        [$usuarioProfessor, $funcionarioProfessor, $escola, $turma, $disciplina] = $this->criarContextoProfessor();

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

        $response = $this->actingAs($usuarioProfessor)->post('/professor/diario', [
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '1',
            'observacoes_gerais' => 'Diario do 1o bimestre.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('diarios_professor', [
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $funcionarioProfessor->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '1',
        ]);
    }

    public function test_professor_nao_pode_acessar_diario_de_outro_professor(): void
    {
        [$usuarioProfessor, $funcionarioProfessor, $escola, $turma, $disciplina] = $this->criarContextoProfessor();
        [, $outroFuncionario] = $this->criarContextoProfessor('prof2@example.com', 'Professor Dois');

        $diario = DiarioProfessor::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $outroFuncionario->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '1',
            'situacao' => 'em_andamento',
        ]);

        $response = $this->actingAs($usuarioProfessor)->get('/professor/diario/' . $diario->id);

        $response->assertForbidden();
    }

    public function test_secretario_escolar_pode_consultar_diario_da_sua_escola(): void
    {
        [$usuarioProfessor, $funcionarioProfessor, $escola, $turma, $disciplina] = $this->criarContextoProfessor();

        $diario = DiarioProfessor::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $funcionarioProfessor->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '2',
            'situacao' => 'em_andamento',
        ]);

        $secretario = Usuario::factory()->create([
            'email' => 'secretaria@example.com',
        ]);
        $secretario->assignRole('Secretário Escolar');
        $secretario->escolas()->attach($escola->id);

        $response = $this->actingAs($secretario)->get('/secretaria-escolar/diarios/' . $diario->id);

        $response->assertOk();
        $response->assertSee($turma->nome);
        $response->assertSee($disciplina->nome);
    }

    public function test_professor_pode_registrar_planejamento_mensal_no_diario(): void
    {
        [$usuarioProfessor, $funcionarioProfessor, $escola, $turma, $disciplina] = $this->criarContextoProfessor();

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

        $response = $this->actingAs($usuarioProfessor)->post('/professor/diario/' . $diario->id . '/planejamento-periodo', [
            'tipo_planejamento' => 'mensal',
            'periodo_referencia' => 'Abril/2026',
            'data_inicio' => '2026-04-01',
            'data_fim' => '2026-04-30',
            'tema_gerador' => 'Praticas de linguagem',
            'objetivos_aprendizagem' => 'Desenvolver leitura e interpretacao.',
            'habilidades_competencias' => 'Leitura, oralidade e escrita.',
            'conteudos' => 'Genero textual e ortografia.',
            'metodologia' => 'Aulas dialogadas.',
            'recursos_didaticos' => 'Livro didatico e quadro.',
            'estrategias_pedagogicas' => 'Leitura compartilhada e producao textual.',
            'instrumentos_avaliacao' => 'Rubricas e atividades.',
            'adequacoes_inclusao' => 'Atividades adaptadas quando necessario.',
            'observacoes' => 'Turma com bom engajamento.',
            'referencias' => 'BNCC e livro didatico adotado.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('planejamentos_periodo', [
            'diario_professor_id' => $diario->id,
            'tipo_planejamento' => 'mensal',
            'periodo_referencia' => 'Abril/2026',
            'tema_gerador' => 'Praticas de linguagem',
            'referencias' => 'BNCC e livro didatico adotado.',
        ]);

        $planejamento = \App\Models\PlanejamentoPeriodo::firstOrFail();

        $this->actingAs($usuarioProfessor)
            ->get('/professor/diario/' . $diario->id)
            ->assertOk()
            ->assertSee('Editar planejamento')
            ->assertSee('Enviar para validacao da coordenacao');

        $this->actingAs($usuarioProfessor)->post('/professor/diario/' . $diario->id . '/planejamento-periodo', [
            'planejamento_periodo_id' => $planejamento->id,
            'tipo_planejamento' => 'mensal',
            'periodo_referencia' => 'Abril/2026 revisado',
            'data_inicio' => '2026-04-01',
            'data_fim' => '2026-04-30',
            'tema_gerador' => 'Praticas de linguagem revisadas',
            'objetivos_aprendizagem' => 'Desenvolver leitura, interpretacao e reescrita.',
            'habilidades_competencias' => 'Leitura, oralidade, escrita e revisao textual.',
            'conteudos' => 'Genero textual, ortografia e pontuacao.',
            'metodologia' => 'Aulas dialogadas e oficinas.',
            'recursos_didaticos' => 'Livro didatico, quadro e textos impressos.',
            'estrategias_pedagogicas' => 'Leitura compartilhada, producao textual e revisao em pares.',
            'instrumentos_avaliacao' => 'Rubricas, atividades e autoavaliacao.',
            'adequacoes_inclusao' => 'Atividades adaptadas quando necessario.',
            'observacoes' => 'Ajuste realizado antes do envio.',
            'referencias' => 'BNCC e livro didatico adotado.',
        ])->assertRedirect();

        $this->assertDatabaseCount('planejamentos_periodo', 1);
        $this->assertDatabaseHas('planejamentos_periodo', [
            'id' => $planejamento->id,
            'periodo_referencia' => 'Abril/2026 revisado',
            'tema_gerador' => 'Praticas de linguagem revisadas',
            'observacoes' => 'Ajuste realizado antes do envio.',
        ]);

        $this->actingAs($usuarioProfessor)
            ->patch('/professor/diario/' . $diario->id . '/planejamento-periodo/' . $planejamento->id . '/enviar')
            ->assertRedirect();

        $this->assertDatabaseHas('planejamentos_periodo', [
            'id' => $planejamento->id,
            'status' => 'enviado',
        ]);
    }

    public function test_professor_pode_lancar_nota_no_diario_conforme_modalidade(): void
    {
        [$usuarioProfessor, $funcionarioProfessor, $escola, $turma, $disciplina] = $this->criarContextoProfessor();

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

        $matricula = Matricula::firstOrFail();

        $response = $this->actingAs($usuarioProfessor)->post('/professor/diario/' . $diario->id . '/avaliacoes', [
            'matricula_id' => $matricula->id,
            'avaliacao_referencia' => 'Prova 1',
            'valor_numerico' => 8.5,
            'observacoes' => 'Bom desempenho.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lancamentos_avaliativos', [
            'diario_professor_id' => $diario->id,
            'matricula_id' => $matricula->id,
            'avaliacao_referencia' => 'Prova 1',
            'tipo_avaliacao' => 'nota',
        ]);
    }

    private function criarContextoProfessor(
        string $email = 'professor@example.com',
        string $nome = 'Professor Teste'
    ): array {
        $escola = Escola::create([
            'nome' => 'Escola Municipal Centro',
            'cnpj' => fake()->numerify('##.###.###/####-##'),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => '(88) 3333-4444',
            'cep' => '60000-000',
            'endereco' => 'Rua das Flores, 10',
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
            'serie_etapa' => '5o Ano',
            'nome' => 'Turma A',
            'turno' => 'Matutino',
            'ano_letivo' => 2026,
            'vagas' => 30,
            'is_multisseriada' => false,
            'ativa' => true,
        ]);

        $disciplina = Disciplina::create([
            'nome' => 'Lingua Portuguesa',
            'codigo' => fake()->unique()->bothify('LP###'),
            'carga_horaria_sugerida' => 200,
            'ativo' => true,
        ]);

        $funcionario = Funcionario::create([
            'nome' => $nome,
            'cpf' => fake()->unique()->numerify('###########'),
            'email' => $email,
            'telefone' => '(88) 99999-0000',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);
        $funcionario->escolas()->attach($escola->id);

        $usuario = Usuario::factory()->create([
            'email' => $email,
            'funcionario_id' => $funcionario->id,
        ]);
        $usuario->assignRole('Professor');
        $usuario->escolas()->attach($escola->id);

        $aluno = Aluno::create([
            'rgm' => fake()->unique()->numerify('2026####'),
            'nome_completo' => 'Aluno Diario',
            'data_nascimento' => '2015-04-12',
            'sexo' => 'M',
            'cpf' => fake()->unique()->numerify('###########'),
            'nis' => null,
            'nome_mae' => 'Maria Diario',
            'nome_pai' => null,
            'responsavel_nome' => 'Maria Diario',
            'responsavel_cpf' => fake()->unique()->numerify('###########'),
            'responsavel_telefone' => '(88) 98888-7777',
            'cep' => '60000-001',
            'logradouro' => 'Rua A',
            'numero' => '100',
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

        Matricula::create([
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

        return [$usuario, $funcionario, $escola, $turma, $disciplina];
    }
}
