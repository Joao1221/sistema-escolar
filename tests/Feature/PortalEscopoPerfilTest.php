<?php

namespace Tests\Feature;

use App\Models\Escola;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PortalEscopoPerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretario_escolar_nao_acessa_portal_da_secretaria_de_educacao(): void
    {
        $usuario = $this->criarUsuarioComPerfil('Secretário Escolar', 'secretario.perfil@example.com');

        $this->actingAs($usuario)
            ->get(route('secretaria.dashboard'))
            ->assertForbidden();
    }

    public function test_secretario_escolar_acessa_apenas_portal_escolar_na_home_de_portais(): void
    {
        $usuario = $this->criarUsuarioComPerfil('Secretário Escolar', 'secretario.home@example.com');

        $response = $this->actingAs($usuario)->get(route('hub'));

        $response->assertOk();
        $response->assertSee('Secretaria Escolar');
        $response->assertDontSee('Secretaria de Educação');
    }

    private function criarUsuarioComPerfil(string $perfil, string $email): Usuario
    {
        $escola = Escola::create([
            'nome' => 'Escola Perfil Teste',
            'cnpj' => '99.999.999/0001-99',
            'email' => 'perfil.teste@example.com',
            'telefone' => '(85) 3333-4444',
            'cep' => '60000-000',
            'endereco' => 'Rua Perfil, 10',
            'bairro' => 'Centro',
            'cidade' => 'Fortaleza',
            'uf' => 'CE',
            'ativo' => true,
        ]);

        $usuario = Usuario::factory()->create(['email' => $email]);
        $usuario->assignRole(Role::findOrCreate($perfil, 'web'));
        $usuario->escolas()->attach($escola->id);

        return $usuario;
    }
}
