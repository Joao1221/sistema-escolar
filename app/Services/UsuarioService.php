<?php

namespace App\Services;

use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\Usuario;
use App\Support\CargosPsicossociais;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioService
{
    /**
     * Obter lista paginada de usuários com seus relacionamentos.
     */
    public function obterTodos($paginacao = 10)
    {
        return Usuario::with(['roles', 'escolas', 'funcionario'])->paginate($paginacao);
    }

    /**
     * Criar um novo usuário.
     */
    public function criar(array $dados)
    {
        return DB::transaction(function () use ($dados) {
            $usuario = Usuario::create([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => Hash::make($dados['password']),
                'ativo' => $dados['ativo'] ?? true,
                'funcionario_id' => $dados['funcionario_id'] ?? null,
            ]);

            // Sincronizar Perfil
            if (!empty($dados['role'])) {
                $role = Role::find($dados['role']);
                if ($role) {
                    $usuario->assignRole($role);
                }
            }

            // Sincronizar Escolas
            $usuario->escolas()->sync($this->resolverEscolaIds($dados));

            return $usuario;
        });
    }

    /**
     * Atualizar um usuário existente.
     */
    public function atualizar(Usuario $usuario, array $dados)
    {
        return DB::transaction(function () use ($usuario, $dados) {
            // Preparar dados para update do modelo base
            $updateData = [
                'name'  => $dados['name'],
                'email' => $dados['email'],
                'ativo' => $dados['ativo'] ?? $usuario->ativo,
                'funcionario_id' => $dados['funcionario_id'] ?? null,
            ];

            // Se senha foi preenchida, hash e adiciona
            if (!empty($dados['password'])) {
                $updateData['password'] = Hash::make($dados['password']);
            }

            $usuario->update($updateData);

            // Sincronizar Perfil
            if (isset($dados['role'])) {
                $role = Role::find($dados['role']);
                if ($role) {
                    $usuario->syncRoles([$role]);
                } else {
                    $usuario->syncRoles([]);
                }
            }

            // Sincronizar Escolas
            $usuario->escolas()->sync($this->resolverEscolaIds($dados));

            return $usuario;
        });
    }

    /**
     * Alternar status de ativação do usuário.
     */
    public function alternarStatus(Usuario $usuario)
    {
        $usuario->update(['ativo' => !$usuario->ativo]);
        return $usuario;
    }

    private function resolverEscolaIds(array $dados): array
    {
        if ($this->deveVincularTodasEscolas($dados)) {
            return Escola::query()->where('ativo', true)->pluck('id')->all();
        }

        return array_values($dados['escolas'] ?? []);
    }

    private function deveVincularTodasEscolas(array $dados): bool
    {
        return CargosPsicossociais::contains($this->resolverCargoFuncionario($dados))
            || $this->possuiPerfilPsicossocial($dados['role'] ?? null);
    }

    private function resolverCargoFuncionario(array $dados): ?string
    {
        if (empty($dados['funcionario_id'])) {
            return null;
        }

        return Funcionario::query()
            ->whereKey($dados['funcionario_id'])
            ->value('cargo');
    }

    private function possuiPerfilPsicossocial(mixed $roleId): bool
    {
        if (blank($roleId)) {
            return false;
        }

        return Role::query()
            ->whereKey($roleId)
            ->where('name', 'Psicologia/Psicopedagogia')
            ->exists();
    }
}
