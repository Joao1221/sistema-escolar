<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\Usuario;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SecretariaController extends Controller
{
    use AuthorizesRequests;

    public function dashboard()
    {
        $totalEscolas = Escola::count();
        $escolasAtivas = Escola::where('ativo', true)->count();
        $escolasInativas = Escola::where('ativo', false)->count();

        $totalFuncionarios = Funcionario::count();
        $funcionariosAtivos = Funcionario::where('ativo', true)->count();

        $totalUsuarios = Usuario::count();
        $usuariosAtivos = Usuario::where('ativo', true)->count();

        $ultimasEscolas = Escola::orderByDesc('created_at')->take(5)->get();

        return view('secretaria.dashboard', compact(
            'totalEscolas',
            'escolasAtivas',
            'escolasInativas',
            'totalFuncionarios',
            'funcionariosAtivos',
            'totalUsuarios',
            'usuariosAtivos',
            'ultimasEscolas'
        ));
    }
}
