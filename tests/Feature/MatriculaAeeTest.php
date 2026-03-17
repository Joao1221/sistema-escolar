<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Matricula;
use App\Models\MatrizCurricular;
use App\Models\ModalidadeEnsino;
use App\Models\Turma;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MatriculaAeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::findOrCreate('cadastrar matrícula', 'web');
        Permission::findOrCreate('consultar matrículas', 'web');

        Role::findOrCreate('Secretário Escolar', 'web')
            ->givePermissionTo(['cadastrar matrícula', 'consultar matrículas']);
    }

    public function test_secretaria_escolar_pode_registrar_matricula_regular_e_aee_para_o_mesmo_aluno(): void
    {
        [$usuario, $turma, $aluno] = $this->criarContextoSecretaria();

        $this->actingAs($usuario)
            ->post(route('secretaria-escolar.matriculas.store'), [
                'aluno_id' => $aluno->id,
                'turma_id' => $turma->id,
                'ano_letivo' => 2026,
                'tipo' => 'regular',
                'data_matricula' => '2026-01-20',
            ])
            ->assertRedirect(route('secretaria-escolar.matriculas.index'));

        $matriculaRegular = Matricula::query()->where('tipo', 'regular')->firstOrFail();

        $this->actingAs($usuario)
            ->post(route('secretaria-escolar.matriculas.store'), [
                'aluno_id' => $aluno->id,
                'ano_letivo' => 2026,
                'tipo' => 'aee',
                'matricula_regular_id' => $matriculaRegular->id,
                'data_matricula' => '2026-01-21',
                'observacoes' => 'Atendimento educacional especializado.',
            ])
            ->assertRedirect(route('secretaria-escolar.matriculas.index'));

        $this->assertDatabaseHas('matriculas', [
            'aluno_id' => $aluno->id,
            'tipo' => 'regular',
        ]);

        $this->assertDatabaseHas('matriculas', [
            'aluno_id' => $aluno->id,
            'tipo' => 'aee',
            'matricula_regular_id' => $matriculaRegular->id,
        ]);

        $this->assertCount(2, Matricula::query()->where('aluno_id', $aluno->id)->get());
    }

    public function test_secretaria_escolar_pode_registrar_matricula_somente_aee(): void
    {
        [$usuario, , $aluno] = $this->criarContextoSecretaria();

        $this->actingAs($usuario)
            ->post(route('secretaria-escolar.matriculas.store'), [
                'aluno_id' => $aluno->id,
                'ano_letivo' => 2026,
                'tipo' => 'aee',
                'data_matricula' => '2026-02-01',
                'observacoes' => 'Aluno ingressou apenas no AEE.',
            ])
            ->assertRedirect(route('secretaria-escolar.matriculas.index'));

        $this->assertDatabaseHas('matriculas', [
            'aluno_id' => $aluno->id,
            'tipo' => 'aee',
            'matricula_regular_id' => null,
        ]);
    }

    private function criarContextoSecretaria(): array
    {
        $escola = Escola::create([
            'nome' => 'Escola Matricula AEE',
            'cnpj' => '11.111.111/0001-11',
            'email' => 'aee@example.com',
            'telefone' => '(85) 3333-1111',
            'cep' => '60000-000',
            'endereco' => 'Rua da Inclusao, 100',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        $usuario = Usuario::factory()->create(['email' => 'secretaria.aee@example.com']);
        $usuario->assignRole('Secretário Escolar');
        $usuario->escolas()->attach($escola->id);

        $modalidade = ModalidadeEnsino::create([
            'nome' => 'Ensino Fundamental',
            'estrutura_avaliativa' => 'bimestral',
            'tipo_avaliacao' => 'nota',
            'carga_horaria_minima' => 800,
            'ativo' => true,
        ]);

        $matriz = MatrizCurricular::create([
            'nome' => 'Matriz AEE Teste',
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
            'nome' => '5 Ano AEE',
            'turno' => 'matutino',
            'ano_letivo' => 2026,
            'vagas' => 25,
            'is_multisseriada' => false,
            'ativa' => true,
        ]);

        $aluno = Aluno::create([
            'rgm' => 'RGM-AEE-1001',
            'nome_completo' => 'Aluno AEE Teste',
            'data_nascimento' => '2015-04-10',
            'sexo' => 'M',
            'nome_mae' => 'Responsavel AEE',
            'responsavel_nome' => 'Responsavel AEE',
            'responsavel_cpf' => '123.456.789-00',
            'responsavel_telefone' => '(85) 99999-2222',
            'cep' => '60000-000',
            'logradouro' => 'Rua do Aluno',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        return [$usuario, $turma, $aluno];
    }
}
