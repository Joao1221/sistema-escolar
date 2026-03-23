<?php

namespace Tests\Feature;

use App\Models\Alimento;
use App\Models\Aluno;
use App\Models\AtendimentoPsicossocial;
use App\Models\CategoriaAlimento;
use App\Models\CardapioDiario;
use App\Models\DiarioProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\Instituicao;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\MatrizCurricular;
use App\Models\ModalidadeEnsino;
use App\Models\MovimentacaoAlimento;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\Turma;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RelatoriosPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
            'consultar relatorios da rede',
            'emitir relatorio institucional da rede',
            'consultar relatorios escolares',
            'consultar notas e conceitos em relatorios',
            'consultar relatorios da nutricionista',
            'emitir relatorios da nutricionista',
            'consultar relatorios tecnicos do psicossocial',
            'emitir relatorios tecnicos do psicossocial',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Administrador da Rede', 'web')->givePermissionTo([
            'consultar relatorios da rede',
            'emitir relatorio institucional da rede',
        ]);

        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo([
            'consultar relatorios escolares',
            'consultar notas e conceitos em relatorios',
        ]);

        Role::findOrCreate('Nutricionista', 'web')->givePermissionTo([
            'consultar relatorios da nutricionista',
            'emitir relatorios da nutricionista',
        ]);

        Role::findOrCreate('Psicologia/Psicopedagogia', 'web')->givePermissionTo([
            'consultar relatorios tecnicos do psicossocial',
            'emitir relatorios tecnicos do psicossocial',
        ]);
    }

    public function test_secretaria_de_educacao_visualiza_relatorio_institucional_da_rede(): void
    {
        $this->criarInstituicao();

        $usuario = Usuario::factory()->create(['email' => 'rede.relatorios@example.com']);
        $usuario->assignRole('Administrador da Rede');

        $response = $this->actingAs($usuario)->post('/secretaria/relatorios/institucional-rede/visualizar', [
            'ano_letivo' => 2026,
        ]);

        $response->assertOk();
        $response->assertSee('Relatorio Institucional da Rede');
        $response->assertSee('Prefeitura Municipal de Teste');
        $response->assertSee('Secretaria Municipal de Educacao de Teste');
    }

    public function test_secretaria_escolar_apenas_visualiza_notas_e_conceitos_em_relatorios(): void
    {
        $this->criarInstituicao();
        [$escola, $turma, $matricula, $diario, $avaliacao] = $this->criarContextoEscolarComAvaliacao();

        $usuario = Usuario::factory()->create(['email' => 'secretaria.relatorios@example.com']);
        $usuario->assignRole('Secretário Escolar');
        $usuario->escolas()->attach($escola->id);

        $response = $this->actingAs($usuario)->post('/secretaria-escolar/relatorios/notas-conceitos-consulta/visualizar', [
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'ano_letivo' => 2026,
        ]);

        $response->assertOk();
        $response->assertSee('Consulta de Notas e Conceitos');
        $response->assertSee($matricula->aluno->nome_completo);
        $response->assertSee('8,50');

        $this->actingAs($usuario)
            ->put(route('secretaria-escolar.coordenacao.diarios.avaliacoes.update', [$diario, $avaliacao]), [
                'avaliacao_referencia' => 'Bimestre 1',
                'valor_numerico' => 9.5,
            ])
            ->assertForbidden();
    }

    public function test_nutricionista_visualiza_relatorio_comparativo_de_consumo(): void
    {
        $this->criarInstituicao();
        [$escolaA, $escolaB] = $this->criarContextoAlimentacao();

        $usuario = Usuario::factory()->create(['email' => 'nutri.relatorios@example.com']);
        $usuario->assignRole('Nutricionista');

        $response = $this->actingAs($usuario)->post('/nutricionista/relatorios/comparativo-consumo/visualizar', [
            'data_inicio' => '2026-03-01',
            'data_fim' => '2026-03-31',
        ]);

        $response->assertOk();
        $response->assertSee('Comparativo de Consumo entre Escolas');
        $response->assertSee($escolaA->nome);
        $response->assertSee($escolaB->nome);
    }

    public function test_relatorio_tecnico_psicossocial_respeita_sigilo(): void
    {
        $this->criarInstituicao();
        $escola = $this->criarEscola('Escola Sigilo Relatorios');
        $aluno = $this->criarAluno('Aluno Sigiloso Relatorio');

        [$usuarioPsico, $funcionarioPsico] = $this->criarUsuarioPsicossocial($escola, 'Psicologa Relatorios', 'psico.relatorios@example.com');
        [$usuarioOutroPsico] = $this->criarUsuarioPsicossocial($escola, 'Psicopedagoga Relatorios', 'psico.outra@example.com');

        $atendimento = AtendimentoPsicossocial::create([
            'escola_id' => $escola->id,
            'usuario_registro_id' => $usuarioPsico->id,
            'profissional_responsavel_id' => $funcionarioPsico->id,
            'atendivel_type' => Aluno::class,
            'atendivel_id' => $aluno->id,
            'tipo_publico' => 'aluno',
            'tipo_atendimento' => 'psicopedagogico',
            'natureza' => 'acompanhamento',
            'status' => 'realizado',
            'data_agendada' => '2026-03-17 08:00:00',
            'data_realizacao' => '2026-03-17 09:00:00',
            'local_atendimento' => 'Sala tecnica',
            'motivo_demanda' => 'Demanda sigilosa.',
            'resumo_sigiloso' => 'Resumo protegido.',
            'observacoes_restritas' => 'Observacao reservada.',
            'nivel_sigilo' => 'alto',
            'requer_acompanhamento' => true,
        ]);

        RelatorioTecnicoPsicossocial::create([
            'atendimento_psicossocial_id' => $atendimento->id,
            'escola_id' => $escola->id,
            'usuario_emissor_id' => $usuarioPsico->id,
            'tipo_relatorio' => 'parecer_inicial',
            'titulo' => 'Parecer sigiloso',
            'conteudo_sigiloso' => 'Conteudo sensivel.',
            'data_emissao' => '2026-03-18',
            'observacoes_restritas' => 'Observacao restrita.',
        ]);

        $response = $this->actingAs($usuarioPsico)->post('/secretaria-escolar/psicologia-psicopedagogia/relatorios/tecnico-psicossocial/visualizar', [
            'escola_id' => $escola->id,
            'data_inicio' => '2026-03-01',
            'data_fim' => '2026-03-31',
        ]);

        $response->assertOk();
        $response->assertSee('Relatorio Tecnico da Psicologia/Psicopedagogia');
        $response->assertSee('Parecer sigiloso');

        $responseOutroProfissional = $this->actingAs($usuarioOutroPsico)->post('/secretaria-escolar/psicologia-psicopedagogia/relatorios/tecnico-psicossocial/visualizar', [
            'escola_id' => $escola->id,
            'data_inicio' => '2026-03-01',
            'data_fim' => '2026-03-31',
        ]);

        $responseOutroProfissional->assertOk();
        $responseOutroProfissional->assertDontSee('Parecer sigiloso');

        $usuarioSemSigilo = Usuario::factory()->create(['email' => 'secretaria.sem.sigilo.relatorios@example.com']);
        $usuarioSemSigilo->assignRole('Secretário Escolar');
        $usuarioSemSigilo->escolas()->attach($escola->id);

        $this->actingAs($usuarioSemSigilo)
            ->get('/secretaria-escolar/psicologia-psicopedagogia/relatorios')
            ->assertForbidden();
    }

    private function criarInstituicao(): void
    {
        Instituicao::create([
            'nome_prefeitura' => 'Prefeitura Municipal de Teste',
            'nome_prefeito' => 'Prefeito Teste',
            'nome_secretaria' => 'Secretaria Municipal de Educacao de Teste',
            'sigla_secretaria' => 'SME',
            'nome_secretario' => 'Secretario Teste',
            'telefone' => '(85) 3000-0000',
            'email' => 'secretaria@example.com',
            'municipio' => 'Fortaleza',
            'uf' => 'CE',
            'textos_institucionais' => 'Relatorio emitido automaticamente pelo sistema.',
            'assinaturas_cargos' => "Secretario Municipal de Educacao\nDirecao Escolar",
        ]);
    }

    private function criarContextoEscolarComAvaliacao(): array
    {
        $escola = $this->criarEscola('Escola Relatorios');
        $modalidade = ModalidadeEnsino::create([
            'nome' => 'Ensino Fundamental',
            'estrutura_avaliativa' => 'bimestral',
            'tipo_avaliacao' => 'nota',
            'carga_horaria_minima' => 800,
            'ativo' => true,
        ]);

        $matriz = MatrizCurricular::create([
            'nome' => 'Matriz Relatorios',
            'modalidade_id' => $modalidade->id,
            'serie_etapa' => '5 Ano',
            'ano_vigencia' => 2026,
            'ativa' => true,
        ]);

        $turma = Turma::create([
            'escola_id' => $escola->id,
            'modalidade_id' => $modalidade->id,
            'matriz_id' => $matriz->id,
            'serie_etapa' => '5 Ano',
            'nome' => '5 Ano Relatorios',
            'turno' => 'matutino',
            'ano_letivo' => 2026,
            'vagas' => 30,
            'is_multisseriada' => false,
            'ativa' => true,
        ]);

        $aluno = $this->criarAluno('Aluno Relatorio Escolar');
        $matricula = Matricula::create([
            'aluno_id' => $aluno->id,
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'ano_letivo' => 2026,
            'tipo' => 'regular',
            'status' => 'ativa',
            'data_matricula' => '2026-01-15',
        ]);

        $disciplina = Disciplina::create([
            'nome' => 'Lingua Portuguesa',
            'codigo' => 'LP',
            'ativo' => true,
        ]);

        $professor = Funcionario::create([
            'nome' => 'Professor Relatorios',
            'cpf' => '12345678901',
            'email' => 'prof.relatorios@example.com',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);

        $diario = DiarioProfessor::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $professor->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '1',
            'situacao' => 'aberto',
        ]);

        $avaliacao = LancamentoAvaliativo::create([
            'diario_professor_id' => $diario->id,
            'matricula_id' => $matricula->id,
            'usuario_registro_id' => Usuario::factory()->create()->id,
            'tipo_avaliacao' => 'nota',
            'avaliacao_referencia' => 'Bimestre 1',
            'valor_numerico' => 8.5,
            'observacoes' => 'Bom desempenho.',
        ]);

        return [$escola, $turma, $matricula, $diario, $avaliacao];
    }

    private function criarContextoAlimentacao(): array
    {
        $escolaA = $this->criarEscola('Escola Nutri Relatorio A');
        $escolaB = $this->criarEscola('Escola Nutri Relatorio B');

        $categoria = CategoriaAlimento::create([
            'nome' => 'Frutas',
            'descricao' => 'Frutas da merenda',
            'ativo' => true,
        ]);

        $alimento = Alimento::create([
            'categoria_alimento_id' => $categoria->id,
            'nome' => 'Maca',
            'unidade_medida' => 'kg',
            'estoque_minimo' => 2,
            'controla_validade' => true,
            'ativo' => true,
        ]);

        $usuarioOperador = Usuario::factory()->create();

        MovimentacaoAlimento::create([
            'escola_id' => $escolaA->id,
            'alimento_id' => $alimento->id,
            'usuario_id' => $usuarioOperador->id,
            'tipo' => 'entrada',
            'quantidade' => 10,
            'saldo_resultante' => 10,
            'data_movimentacao' => '2026-03-10',
            'data_validade' => '2026-03-30',
        ]);

        MovimentacaoAlimento::create([
            'escola_id' => $escolaA->id,
            'alimento_id' => $alimento->id,
            'usuario_id' => $usuarioOperador->id,
            'tipo' => 'saida',
            'quantidade' => 4,
            'saldo_resultante' => 6,
            'data_movimentacao' => '2026-03-11',
        ]);

        MovimentacaoAlimento::create([
            'escola_id' => $escolaB->id,
            'alimento_id' => $alimento->id,
            'usuario_id' => $usuarioOperador->id,
            'tipo' => 'entrada',
            'quantidade' => 7,
            'saldo_resultante' => 7,
            'data_movimentacao' => '2026-03-12',
            'data_validade' => '2026-03-29',
        ]);

        CardapioDiario::create([
            'escola_id' => $escolaA->id,
            'usuario_id' => $usuarioOperador->id,
            'data_cardapio' => '2026-03-18',
            'observacoes' => 'Cardapio de teste.',
        ]);

        return [$escolaA, $escolaB];
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

    private function criarUsuarioPsicossocial(Escola $escola, string $nome, string $email): array
    {
        $funcionario = Funcionario::create([
            'nome' => $nome,
            'cpf' => fake()->unique()->numerify('###########'),
            'email' => $email,
            'telefone' => '(85) 99999-2222',
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
}
