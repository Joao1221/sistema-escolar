<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\AtendimentoPsicossocial;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\Instituicao;
use App\Models\Matricula;
use App\Models\MatrizCurricular;
use App\Models\ModalidadeEnsino;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\Turma;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DocumentosPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
            'consultar documentos escolares',
            'emitir declaracao de matricula',
            'emitir declaracao de frequencia',
            'emitir comprovante de matricula',
            'emitir ficha cadastral do aluno',
            'emitir ficha individual do aluno',
            'emitir guia de transferencia',
            'emitir historico escolar',
            'emitir ata escolar',
            'emitir oficio escolar',
            'consultar documentos institucionais da rede',
            'emitir oficio institucional da rede',
            'emitir modelo institucional da rede',
            'consultar documentos da direcao escolar',
            'emitir documentos da direcao escolar',
            'consultar documentos pedagogicos',
            'emitir documentos pedagogicos',
            'consultar documentos do professor',
            'emitir documentos do professor',
            'acessar modulo psicossocial',
            'acessar dados sigilosos psicossociais',
            'consultar relatorios tecnicos do psicossocial',
            'consultar documentos psicossociais',
            'emitir documentos psicossociais',
            'criar diarios',
        ] as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo([
            'consultar documentos escolares',
            'emitir declaracao de matricula',
        ]);

        Role::findOrCreate('Administrador da Rede', 'web')->givePermissionTo([
            'consultar documentos institucionais da rede',
            'emitir oficio institucional da rede',
            'emitir modelo institucional da rede',
        ]);

        Role::findOrCreate('Professor', 'web')->givePermissionTo([
            'consultar documentos do professor',
            'emitir documentos do professor',
            'criar diarios',
        ]);

        Role::findOrCreate('Psicologia/Psicopedagogia', 'web')->givePermissionTo([
            'acessar modulo psicossocial',
            'acessar dados sigilosos psicossociais',
            'consultar relatorios tecnicos do psicossocial',
            'consultar documentos psicossociais',
            'emitir documentos psicossociais',
        ]);
    }

    public function test_secretaria_escolar_visualiza_documento_operacional_no_proprio_portal(): void
    {
        $this->criarInstituicao();
        $escola = $this->criarEscola('Escola Operacional');
        $matricula = $this->criarMatricula($escola, 'Aluno Documento Escolar');

        $usuario = Usuario::factory()->create(['email' => 'secretaria.documentos@example.com']);
        $usuario->assignRole('Secretário Escolar');
        $usuario->escolas()->attach($escola->id);

        $response = $this->actingAs($usuario)->post('/secretaria-escolar/documentos/declaracao-matricula/visualizar', [
            'matricula_id' => $matricula->id,
        ]);

        $response->assertOk();
        $response->assertSee('Declaracao de Matricula');
        $response->assertSee('Aluno Documento Escolar');
        $response->assertSee('Prefeitura Municipal de Teste');
        $response->assertSee('Escola Operacional');
    }

    public function test_secretaria_de_educacao_emite_documento_institucional_da_rede(): void
    {
        $this->criarInstituicao();

        $usuario = Usuario::factory()->create(['email' => 'rede.documentos@example.com']);
        $usuario->assignRole('Administrador da Rede');

        $response = $this->actingAs($usuario)->post('/secretaria/documentos/oficio-institucional-rede/visualizar', [
            'destinatario' => 'Camara Municipal',
            'assunto' => 'Comunicado institucional',
            'conteudo' => 'Oficio emitido para validacao do modulo documental.',
        ]);

        $response->assertOk();
        $response->assertSee('Oficio Institucional da Rede');
        $response->assertSee('Camara Municipal');
        $response->assertSee('Comunicado institucional');
        $response->assertSee('Secretaria Municipal de Educacao de Teste');
    }

    public function test_professor_so_emite_documento_do_proprio_escopo(): void
    {
        $this->criarInstituicao();
        $escola = $this->criarEscola('Escola Professor');
        [$diarioDoProfessor, $usuarioProfessor] = $this->criarProfessorComDiario($escola, 'Professor Documento');
        [$diarioDeOutroProfessor] = $this->criarProfessorComDiario($escola, 'Professor Visitante');

        $responsePermitido = $this->actingAs($usuarioProfessor)->post('/professor/documentos/relatorio-operacional-turma/visualizar', [
            'diario_id' => $diarioDoProfessor->id,
        ]);

        $responsePermitido->assertOk();
        $responsePermitido->assertSee('Relatorio Operacional da Turma');
        $responsePermitido->assertSee($diarioDoProfessor->turma->nome);

        $this->actingAs($usuarioProfessor)->post('/professor/documentos/relatorio-operacional-turma/visualizar', [
            'diario_id' => $diarioDeOutroProfessor->id,
        ])->assertForbidden();
    }

    public function test_documentos_psicossociais_respeitam_sigilo(): void
    {
        $this->criarInstituicao();
        $escola = $this->criarEscola('Escola Sigilo');
        $aluno = $this->criarAluno('Aluno Sigiloso');

        [$usuarioPsico, $funcionarioPsico] = $this->criarUsuarioPsicossocial($escola, 'Psicologa Documentos', 'psico.documentos@example.com');
        [$usuarioOutroPsico] = $this->criarUsuarioPsicossocial($escola, 'Psicopedagogo Visitante', 'psico.visitante@example.com');

        $atendimento = AtendimentoPsicossocial::create([
            'escola_id' => $escola->id,
            'usuario_registro_id' => $usuarioPsico->id,
            'profissional_responsavel_id' => $funcionarioPsico->id,
            'atendivel_type' => Aluno::class,
            'atendivel_id' => $aluno->id,
            'tipo_publico' => 'aluno',
            'tipo_atendimento' => 'psicologico',
            'natureza' => 'escuta',
            'status' => 'realizado',
            'data_agendada' => '2026-03-17 08:00:00',
            'data_realizacao' => '2026-03-17 08:40:00',
            'local_atendimento' => 'Sala tecnica',
            'motivo_demanda' => 'Acompanhamento socioemocional.',
            'resumo_sigiloso' => 'Resumo sigiloso do atendimento.',
            'observacoes_restritas' => 'Observacao restrita.',
            'nivel_sigilo' => 'alto',
            'requer_acompanhamento' => true,
        ]);

        $relatorio = RelatorioTecnicoPsicossocial::create([
            'atendimento_psicossocial_id' => $atendimento->id,
            'escola_id' => $escola->id,
            'usuario_emissor_id' => $usuarioPsico->id,
            'tipo_relatorio' => 'tecnico',
            'titulo' => 'Relatorio reservado',
            'conteudo_sigiloso' => 'Conteudo tecnico sensivel.',
            'data_emissao' => '2026-03-18',
            'observacoes_restritas' => 'Observacao protegida.',
        ]);

        $response = $this->actingAs($usuarioPsico)->post('/secretaria-escolar/psicologia-psicopedagogia/documentos/relatorio-tecnico/visualizar', [
            'relatorio_id' => $relatorio->id,
        ]);

        $response->assertOk();
        $response->assertSee('Relatorio Tecnico');
        $response->assertSee('Relatorio reservado');
        $response->assertSee('Aluno Sigiloso');

        $this->actingAs($usuarioOutroPsico)->post('/secretaria-escolar/psicologia-psicopedagogia/documentos/relatorio-tecnico/visualizar', [
            'relatorio_id' => $relatorio->id,
        ])->assertForbidden();

        $usuarioSecretaria = Usuario::factory()->create(['email' => 'secretaria.sem.sigilo@example.com']);
        $usuarioSecretaria->assignRole('Secretário Escolar');
        $usuarioSecretaria->escolas()->attach($escola->id);

        $this->actingAs($usuarioSecretaria)->get('/secretaria-escolar/psicologia-psicopedagogia/documentos')
            ->assertForbidden();
    }

    public function test_portal_psicologia_visualiza_relatorio_tecnico_emitido_com_sigilo_por_profissional(): void
    {
        $this->criarInstituicao();
        $escola = $this->criarEscola('Escola Portal Psicologia');
        $aluno = $this->criarAluno('Aluno Portal Psicologia');

        [$usuarioPsico, $funcionarioPsico] = $this->criarUsuarioPsicossocial($escola, 'Psicologa Portal', 'psico.portal@example.com');
        [$usuarioOutroPsico] = $this->criarUsuarioPsicossocial($escola, 'Psicopedagogo Portal', 'psico.portal.outro@example.com');
        $funcionarioPsico->update(['cargo' => 'Psicólogo']);

        $atendimento = AtendimentoPsicossocial::create([
            'escola_id' => $escola->id,
            'usuario_registro_id' => $usuarioPsico->id,
            'profissional_responsavel_id' => $funcionarioPsico->id,
            'atendivel_type' => Aluno::class,
            'atendivel_id' => $aluno->id,
            'tipo_publico' => 'aluno',
            'tipo_atendimento' => 'psicologico',
            'natureza' => 'escuta',
            'status' => 'realizado',
            'data_agendada' => '2026-03-17 08:00:00',
            'data_realizacao' => '2026-03-17 08:40:00',
            'local_atendimento' => 'Sala tecnica',
            'motivo_demanda' => 'Acompanhamento clinico.',
            'resumo_sigiloso' => 'Resumo clinico.',
            'observacoes_restritas' => 'Observacao restrita.',
            'nivel_sigilo' => 'alto',
            'requer_acompanhamento' => true,
        ]);

        $relatorio = RelatorioTecnicoPsicossocial::create([
            'atendimento_psicossocial_id' => $atendimento->id,
            'escola_id' => $escola->id,
            'usuario_emissor_id' => $usuarioPsico->id,
            'tipo_relatorio' => 'parecer_inicial',
            'titulo' => 'Relatorio emitido no portal',
            'conteudo_sigiloso' => 'Conteudo tecnico visualizavel pelo responsavel.',
            'data_emissao' => '2026-03-18',
            'observacoes_restritas' => 'Observacao final.',
        ]);
        $relatorio->forceFill([
            'created_at' => Carbon::parse('2026-03-25 20:55:38', 'UTC'),
            'updated_at' => Carbon::parse('2026-03-25 20:55:38', 'UTC'),
        ])->saveQuietly();

        $response = $this->actingAs($usuarioPsico)
            ->get(route('psicologia.relatorios_tecnicos.show', $relatorio));

        $response->assertOk();
        $response->assertSee('Relatorio Tecnico');
        $response->assertSee('Relatorio emitido no portal');
        $response->assertSee('Conteudo tecnico visualizavel pelo responsavel.');
        $response->assertSee('Psicologa Portal');
        $response->assertSee('20260325175538');
        $response->assertSee('Emitido em 25/03/2026 17:55');
        $response->assertSee('Psicológico');
        $response->assertSee('Psicologa Portal - Psicólogo(a)');
        $response->assertSee('(85) 3000-0000');
        $response->assertSee('secretaria@example.com');

        $this->actingAs($usuarioOutroPsico)
            ->get(route('psicologia.relatorios_tecnicos.show', $relatorio))
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
            'textos_institucionais' => 'Documento emitido automaticamente pelo sistema.',
            'assinaturas_cargos' => "Secretario Municipal de Educacao\nDirecao da Unidade Escolar",
        ]);
    }

    private function criarEscola(string $nome): Escola
    {
        return Escola::create([
            'nome' => $nome,
            'cnpj' => '00.000.000/0001-11',
            'email' => strtolower(str_replace(' ', '.', $nome)) . '@example.com',
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

    private function criarMatricula(Escola $escola, string $nomeAluno): Matricula
    {
        $aluno = $this->criarAluno($nomeAluno);
        $contexto = $this->criarContextoAcademico($escola);

        return Matricula::create([
            'aluno_id' => $aluno->id,
            'escola_id' => $escola->id,
            'turma_id' => $contexto['turma']->id,
            'ano_letivo' => 2026,
            'tipo' => 'regular',
            'status' => 'ativa',
            'data_matricula' => now()->toDateString(),
        ]);
    }

    private function criarContextoAcademico(Escola $escola): array
    {
        $modalidade = ModalidadeEnsino::create([
            'nome' => 'Ensino Fundamental',
            'estrutura_avaliativa' => 'bimestral',
            'tipo_avaliacao' => 'nota',
            'carga_horaria_minima' => 800,
            'ativo' => true,
        ]);

        $matriz = MatrizCurricular::create([
            'nome' => 'Matriz Base',
            'modalidade_id' => $modalidade->id,
            'serie_etapa' => '5 Ano',
            'escola_id' => $escola->id,
            'ano_vigencia' => 2026,
            'ativa' => true,
        ]);

        $disciplina = Disciplina::create([
            'nome' => 'Lingua Portuguesa',
            'codigo' => 'LP01',
            'carga_horaria_sugerida' => 200,
            'ativo' => true,
        ]);

        $matriz->disciplinas()->attach($disciplina->id, [
            'carga_horaria' => 200,
            'obrigatoria' => true,
        ]);

        $turma = Turma::create([
            'escola_id' => $escola->id,
            'modalidade_id' => $modalidade->id,
            'matriz_id' => $matriz->id,
            'serie_etapa' => '5 Ano',
            'nome' => '5 Ano A',
            'turno' => 'Matutino',
            'ano_letivo' => 2026,
            'vagas' => 30,
            'is_multisseriada' => false,
            'ativa' => true,
        ]);

        return compact('modalidade', 'matriz', 'disciplina', 'turma');
    }

    private function criarProfessorComDiario(Escola $escola, string $nomeProfessor): array
    {
        $contexto = $this->criarContextoAcademico($escola);
        $funcionario = Funcionario::create([
            'nome' => $nomeProfessor,
            'cpf' => fake()->numerify('###########'),
            'email' => strtolower(str_replace(' ', '.', $nomeProfessor)) . '@example.com',
            'telefone' => '(85) 99999-0000',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);
        $funcionario->escolas()->attach($escola->id);

        $usuario = Usuario::factory()->create([
            'email' => $funcionario->email,
            'funcionario_id' => $funcionario->id,
        ]);
        $usuario->assignRole('Professor');
        $usuario->escolas()->attach($escola->id);

        $diario = \App\Models\DiarioProfessor::create([
            'escola_id' => $escola->id,
            'turma_id' => $contexto['turma']->id,
            'disciplina_id' => $contexto['disciplina']->id,
            'professor_id' => $funcionario->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '1',
            'situacao' => 'aberto',
        ]);

        return [$diario, $usuario];
    }

    private function criarUsuarioPsicossocial(Escola $escola, string $nome, string $email): array
    {
        $funcionario = Funcionario::create([
            'nome' => $nome,
            'cpf' => fake()->unique()->numerify('###########'),
            'email' => $email,
            'telefone' => '(85) 99999-1111',
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
