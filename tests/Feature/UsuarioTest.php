<?php

namespace Tests\Feature;

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

        $novoUsuarioData = [
            'name' => 'Novo Professor Teste',
            'email' => 'prof.teste@escola.com',
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
        ]);
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
