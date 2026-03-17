<?php

namespace Tests\Feature;

use App\Models\Escola;
use App\Models\ModalidadeEnsino;
use App\Models\Turma;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TurmaFluxoTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretario_escolar_cria_turma_com_escola_vinda_do_usuario(): void
    {
        $escola = Escola::create([
            'nome' => 'Escola Turma Teste',
            'cnpj' => '11.111.111/0001-11',
            'email' => 'escola.turma@example.com',
            'telefone' => '(85) 3000-1111',
            'cep' => '60000-000',
            'endereco' => 'Rua da Turma, 100',
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

        $usuario = Usuario::factory()->create([
            'email' => 'secretario.turma@example.com',
        ]);

        $papel = Role::findOrCreate('Secretário Escolar', 'web');
        $permissao = Permission::findOrCreate('cadastrar turmas', 'web');
        $papel->givePermissionTo($permissao);

        $usuario->assignRole($papel);
        $usuario->givePermissionTo($permissao);
        $usuario->escolas()->attach($escola->id);

        $response = $this->actingAs($usuario)->post(route('secretaria-escolar.turmas.store'), [
            'nome' => '5 ANO A',
            'ano_letivo' => 2026,
            'modalidade_id' => $modalidade->id,
            'serie_etapa' => '5 ANO',
            'turno' => 'Matutino',
            'vagas' => 30,
            'is_multisseriada' => false,
        ]);

        $response->assertRedirect(route('secretaria-escolar.turmas.index'));

        $this->assertDatabaseHas('turmas', [
            'nome' => '5 ANO A',
            'escola_id' => $escola->id,
            'modalidade_id' => $modalidade->id,
            'serie_etapa' => '5 ANO',
            'turno' => 'Matutino',
            'ano_letivo' => 2026,
            'vagas' => 30,
        ]);

        $turma = Turma::query()->where('nome', '5 ANO A')->firstOrFail();
        $this->assertSame($escola->id, $turma->escola_id);
    }
}
