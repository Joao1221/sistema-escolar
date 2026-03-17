<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\Usuario;
use App\Services\UsuarioService;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsuarioController extends Controller
{
    use AuthorizesRequests;

    protected $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('visualizar usuarios');
        $usuarios = $this->usuarioService->obterTodos();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('criar usuario');
        $perfis = Role::all();
        $escolas = Escola::where('ativo', true)->get();
        $funcionarios = Funcionario::where('ativo', true)->orderBy('nome')->get();
        return view('usuarios.create', compact('perfis', 'escolas', 'funcionarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioRequest $request)
    {
        $this->usuarioService->criar($request->validated());
        return redirect()->route('secretaria.usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        $this->authorize('editar usuario');
        $perfis = Role::all();
        $escolas = Escola::where('ativo', true)->get();
        $funcionarios = Funcionario::where('ativo', true)->orderBy('nome')->get();
        return view('usuarios.edit', compact('usuario', 'perfis', 'escolas', 'funcionarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        $this->usuarioService->atualizar($usuario, $request->validated());
        return redirect()->route('secretaria.usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Alternar status de ativação do usuário (Ativar/Inativar).
     */
    public function alternarStatus(Usuario $usuario)
    {
        $this->authorize('ativar inativar usuario');
        $this->usuarioService->alternarStatus($usuario);
        return redirect()->route('secretaria.usuarios.index')->with('success', 'Status do usuário alterado com sucesso.');
    }
}
