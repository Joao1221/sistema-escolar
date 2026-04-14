<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFuncionarioRequest;
use App\Http\Requests\UpdateFuncionarioRequest;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Services\FuncionarioService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    use AuthorizesRequests;

    protected FuncionarioService $funcionarioService;

    public function __construct(FuncionarioService $funcionarioService)
    {
        $this->funcionarioService = $funcionarioService;
    }

    public function index(Request $request): View
    {
        $this->authorize('visualizar funcionarios');

        $filtros = $request->only(['nome', 'cpf', 'cargo', 'escola_id']);
        $funcionarios = $this->funcionarioService->listarFuncionarios($filtros);
        $escolas = Escola::where('ativo', true)->orderBy('nome')->get();

        return view('funcionarios.index', compact('funcionarios', 'escolas'));
    }

    public function create(): View
    {
        $this->authorize('criar funcionario');
        $escolas = Escola::where('ativo', true)->orderBy('nome')->get();

        return view('funcionarios.create', compact('escolas'));
    }

    public function store(StoreFuncionarioRequest $request): RedirectResponse
    {
        $this->funcionarioService->criarFuncionario($request->validated());

        return redirect()->route('secretaria.funcionarios.index')->with('success', 'Funcionário cadastrado com sucesso!');
    }

    public function show(Funcionario $funcionario): View
    {
        $this->authorize('visualizar funcionarios');

        return view('funcionarios.show', compact('funcionario'));
    }

    public function edit(Funcionario $funcionario): View
    {
        $this->authorize('editar funcionario');
        $escolas = Escola::where('ativo', true)->orderBy('nome')->get();
        $escolasSelecionadas = $funcionario->escolas->pluck('id')->toArray();

        return view('funcionarios.edit', compact('funcionario', 'escolas', 'escolasSelecionadas'));
    }

    public function update(UpdateFuncionarioRequest $request, Funcionario $funcionario): RedirectResponse
    {
        $this->funcionarioService->atualizarFuncionario($funcionario, $request->validated());

        return redirect()->route('secretaria.funcionarios.index')->with('success', 'Funcionário atualizado com sucesso!');
    }

    public function toggleStatus(Funcionario $funcionario): RedirectResponse
    {
        $this->authorize('ativar inativar funcionario');
        $this->funcionarioService->alternarStatus($funcionario);

        return redirect()->back()->with('success', 'Status do funcionário alterado!');
    }
}
