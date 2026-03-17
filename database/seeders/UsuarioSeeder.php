<?php

namespace Database\Seeders;

use App\Models\Escola;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $escola = Escola::updateOrCreate(
            ['email' => 'contato@sme.gov.br'],
            [
                'nome' => 'Secretaria Municipal de Educação',
                'cnpj' => '00.000.000/0001-00',
                'telefone' => '(00) 0000-0000',
                'cep' => '00000-000',
                'endereco' => 'Rua Principal, 100',
                'bairro' => 'Centro',
                'cidade' => 'Cidade Base',
                'uf' => 'UF',
                'ativo' => true,
            ]
        );

        $usuario = Usuario::updateOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
                'ativo' => true,
            ]
        );

        $usuario->escolas()->sync([$escola->id]);

        $perfilAdmin = Role::where('name', 'Administrador da Rede')->first();

        if ($perfilAdmin) {
            $usuario->syncRoles([$perfilAdmin]);
        }
    }
}
