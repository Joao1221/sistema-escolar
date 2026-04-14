<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlunoRequest;
use App\Http\Requests\UpdateAlunoRequest;
use App\Models\Aluno;
use App\Services\AlunoService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    use AuthorizesRequests;

    protected AlunoService $alunoService;

    public function __construct(AlunoService $alunoService)
    {
        $this->alunoService = $alunoService;
    }

    /**
     * Listagem de alunos com filtros.
     */
    public function index(Request $request): View
    {
        $this->authorize('visualizar alunos');

        $filtros = $request->only(['nome', 'rgm', 'status']);
        $alunos = $this->alunoService->listarAlunos($filtros);

        return view('alunos.index', compact('alunos'));
    }

    /**
     * Tela de cadastro de novo aluno.
     */
    public function create(): View
    {
        $this->authorize('criar aluno');

        return view('alunos.create');
    }

    /**
     * Salvar novo aluno.
     */
    public function store(StoreAlunoRequest $request): RedirectResponse
    {
        $this->alunoService->criarAluno($request->validated());

        return redirect()->route('secretaria.alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    /**
     * Detalhes do aluno.
     */
    public function show(Aluno $aluno): View
    {
        $this->authorize('detalhar aluno');

        return view('alunos.show', compact('aluno'));
    }

    /**
     * Tela de edição de aluno.
     */
    public function edit(Aluno $aluno): View
    {
        $this->authorize('editar aluno');

        return view('alunos.edit', compact('aluno'));
    }

    /**
     * Atualizar dados do aluno.
     */
    public function update(UpdateAlunoRequest $request, Aluno $aluno): RedirectResponse
    {
        $this->alunoService->atualizarAluno($aluno, $request->validated());

        return redirect()->route('secretaria.alunos.index')
            ->with('success', 'Dados do aluno atualizados com sucesso!');
    }

    /**
     * Alternar status Ativo/Inativo.
     */
    public function toggleStatus(Aluno $aluno): RedirectResponse
    {
        $this->authorize('ativar inativar aluno');
        $this->alunoService->alternarStatus($aluno);

        return redirect()->back()->with('success', 'Status do aluno alterado com sucesso!');
    }
}
