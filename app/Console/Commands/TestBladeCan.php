<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Illuminate\Support\Facades\Gate;

class TestBladeCan extends Command
{
    protected $signature = 'test:can';
    protected $description = 'Test gate and can';

    public function handle()
    {
        $user = Usuario::first();
        auth()->login($user);
        
        $this->info("User id: " . $user->id);
        $this->info("User hasRole admin: " . ($user->hasRole('Administrador da Rede') ? 'Yes' : 'No'));
        $this->info("Gate check visualizar: " . (Gate::check('visualizar usuarios') ? 'Yes' : 'No'));
        $this->info("Gate check criar: " . (Gate::check('criar usuario') ? 'Yes' : 'No'));
        
        $view = view('usuarios.index', ['usuarios' => app(\App\Services\UsuarioService::class)->obterTodos()])->render();
        
        if (strpos($view, 'Novo Usuário') !== false) {
            $this->info("Botao 'Novo Usuário' ENCONTRADO na view compilada.");
        } else {
            $this->error("Botao 'Novo Usuário' NAO ENCONTRADO na view compilada.");
        }
    }
}
