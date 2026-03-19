<?php

namespace Tests\Feature;

use App\Models\Funcionario;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::findOrCreate('visualizar usuarios', 'web');
        Permission::findOrCreate('criar usuario', 'web');
        Permission::findOrCreate('editar usuario', 'web');
        Permission::findOrCreate('ativar inativar usuario', 'web');

        Role::findOrCreate('Administrador da Rede', 'web')
            ->givePermissionTo(Permission::all());
    }

    public function test_usuario_sem_permissao_nao_pode_ver_lista_de_usuarios(): void
    {
        $userSemPermissao = Usuario::factory()->create();

        $response = $this->actingAs($userSemPermissao)
            ->get(route('secretaria.usuarios.index'));

        $response->assertForbidden();
    }

    public function test_administrador_pode_ver_lista_de_usuarios(): void
    {
        $admin = Usuario::factory()->create();
        $admin->assignRole('Administrador da Rede');

        $response = $this->actingAs($admin)
            ->get(route('secretaria.usuarios.index'));

        $response->assertOk();
        $response->assertViewIs('usuarios.index');
    }

    public function test_administrador_pode_criar_usuario(): void
    {
        $admin = Usuario::factory()->create();
        $admin->assignRole('Administrador da Rede');
        $funcionario = Funcionario::create([
            'nome' => 'Novo Professor Teste',
            'cpf' => '123.456.789-00',
            'email' => 'prof.teste@escola.com',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);

        $novoUsuarioData = [
            'funcionario_id' => $funcionario->id,
            'password' => 'senha1234',
            'password_confirmation' => 'senha1234',
            'ativo' => 1,
        ];

        $response = $this->actingAs($admin)
            ->post(route('secretaria.usuarios.store'), $novoUsuarioData);

        $response->assertRedirect(route('secretaria.usuarios.index'));
        $this->assertDatabaseHas('usuarios', [
            'email' => 'prof.teste@escola.com',
            'ativo' => 1,
            'funcionario_id' => $funcionario->id,
        ]);
    }

    public function test_administrador_nao_pode_criar_usuario_sem_funcionario_vinculado(): void
    {
        $admin = Usuario::factory()->create();
        $admin->assignRole('Administrador da Rede');

        $response = $this->actingAs($admin)
            ->from(route('secretaria.usuarios.create'))
            ->post(route('secretaria.usuarios.store'), [
                'password' => 'senha1234',
                'password_confirmation' => 'senha1234',
                'ativo' => 1,
            ]);

        $response->assertRedirect(route('secretaria.usuarios.create'));
        $response->assertSessionHasErrors('funcionario_id');
        $this->assertDatabaseCount('usuarios', 1);
    }

    public function test_administrador_nao_pode_reutilizar_funcionario_que_ja_possui_usuario(): void
    {
        $admin = Usuario::factory()->create();
        $admin->assignRole('Administrador da Rede');

        $funcionario = Funcionario::create([
            'nome' => 'Funcionario Ja Vinculado',
            'cpf' => '987.654.321-00',
            'email' => 'funcionario.vinculado@escola.com',
            'cargo' => 'Professor',
            'ativo' => true,
        ]);

        Usuario::factory()->create([
            'email' => $funcionario->email,
            'funcionario_id' => $funcionario->id,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('secretaria.usuarios.create'))
            ->post(route('secretaria.usuarios.store'), [
                'funcionario_id' => $funcionario->id,
                'password' => 'senha1234',
                'password_confirmation' => 'senha1234',
                'ativo' => 1,
            ]);

        $response->assertRedirect(route('secretaria.usuarios.create'));
        $response->assertSessionHasErrors('funcionario_id');
        $this->assertDatabaseCount('usuarios', 2);
    }

    public function test_administrador_pode_alternar_status(): void
    {
        $admin = Usuario::factory()->create();
        $admin->assignRole('Administrador da Rede');

        $userInativo = Usuario::factory()->create(['ativo' => false]);

        $response = $this->actingAs($admin)
            ->patch(route('secretaria.usuarios.status', $userInativo));

        $response->assertRedirect(route('secretaria.usuarios.index'));
        $this->assertDatabaseHas('usuarios', [
            'id' => $userInativo->id,
            'ativo' => 1,
        ]);
    }
}
