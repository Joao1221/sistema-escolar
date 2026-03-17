<?php

namespace Tests\Feature;

use App\Models\DiarioProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\ModalidadeEnsino;
use App\Models\Turma;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PortalProfessorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost']);
        URL::forceRootUrl('http://localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::findOrCreate('criar diarios', 'web');

        Role::findOrCreate('Professor', 'web')->givePermissionTo(['criar diarios']);
    }

    public function test_professor_pode_entrar_no_dashboard_do_portal(): void
    {
        [$usuarioProfessor, $escola, $turma, $disciplina, $funcionario] = $this->criarProfessorComHorario();

        DiarioProfessor::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $funcionario->id,
            'ano_letivo' => 2026,
            'periodo_tipo' => 'bimestre',
            'periodo_referencia' => '1',
            'situacao' => 'em_andamento',
        ]);

        $response = $this->actingAs($usuarioProfessor)->get('/professor/dashboard');

        $response->assertOk();
        $response->assertSee('Portal do Professor');
        $response->assertSee('Dashboard');
        $response->assertSee($turma->nome);
    }

    public function test_professor_ve_apenas_suas_turmas_e_horarios_no_portal(): void
    {
        [$usuarioProfessor] = $this->criarProfessorComHorario('prof1@example.com', 'Professor Um', 'Turma A');
        [, , $outraTurma] = $this->criarProfessorComHorario('prof2@example.com', 'Professor Dois', 'Turma B');

        $turmasResponse = $this->actingAs($usuarioProfessor)->get('/professor/turmas');
        $horariosResponse = $this->actingAs($usuarioProfessor)->get('/professor/horarios');

        $turmasResponse->assertOk();
        $turmasResponse->assertSee('Turma A');
        $turmasResponse->assertDontSee('Turma B');

        $horariosResponse->assertOk();
        $horariosResponse->assertSee('Turma A');
        $horariosResponse->assertDontSee('Turma B');
    }

    public function test_usuario_sem_permissao_nao_pode_acessar_o_portal_do_professor(): void
    {
        $usuario = Usuario::factory()->create();

        $response = $this->actingAs($usuario)->get('/professor/dashboard');

        $response->assertForbidden();
    }

    private function criarProfessorComHorario(
        string $email = 'professor@example.com',
        string $nomeProfessor = 'Professor Teste',
        string $nomeTurma = 'Turma A'
    ): array {
        $escola = Escola::create([
            'nome' => 'Escola Professor',
            'cnpj' => fake()->unique()->numerify('##.###.###/####-##'),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => '(85) 3333-2222',
            'cep' => '60000-000',
            'endereco' => 'Rua Principal, 10',
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
            'serie_etapa' => '4o Ano',
            'nome' => $nomeTurma,
            'turno' => 'Matutino',
            'ano_letivo' => 2026,
            'vagas' => 25,
            'is_multisseriada' => false,
            'ativa' => true,
        ]);

        $disciplina = Disciplina::create([
            'nome' => 'Matemática ' . $nomeTurma,
            'codigo' => fake()->unique()->bothify('MAT###'),
            'carga_horaria_sugerida' => 160,
            'ativo' => true,
        ]);

        $funcionario = Funcionario::create([
            'nome' => $nomeProfessor,
            'cpf' => fake()->unique()->numerify('###########'),
            'email' => $email,
            'telefone' => '(85) 99999-1111',
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

        HorarioAula::create([
            'escola_id' => $escola->id,
            'turma_id' => $turma->id,
            'disciplina_id' => $disciplina->id,
            'professor_id' => $funcionario->id,
            'dia_semana' => 2,
            'horario_inicial' => '07:00',
            'horario_final' => '07:50',
            'ordem_aula' => 1,
            'ativo' => true,
        ]);

        return [$usuario, $escola, $turma, $disciplina, $funcionario];
    }
}
