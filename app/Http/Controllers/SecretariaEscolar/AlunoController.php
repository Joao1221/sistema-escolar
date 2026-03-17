<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlunoRequest;
use App\Http\Requests\UpdateAlunoRequest;
use App\Models\Aluno;
use App\Services\AlunoService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AlunoController extends Controller
{
    use AuthorizesRequests;

    protected $alunoService;

    public function __construct(AlunoService $alunoService)
    {
        $this->alunoService = $alunoService;
    }

    /**
     * Listagem de alunos com filtros (Portal Escolar).
     */
    public function index(Request $request)
    {
        $this->authorize('visualizar alunos');
        
        $filtros = $request->only(['nome', 'rgm', 'status']);
        $alunos = $this->alunoService->listarAlunos($filtros);

        return view('secretaria-escolar.alunos.index', compact('alunos'));
    }

    /**
     * Tela de cadastro de novo aluno.
     */
    public function create()
    {
        $this->authorize('criar aluno');
        return view('secretaria-escolar.alunos.create');
    }

    /**
     * Salvar novo aluno.
     */
    public function store(StoreAlunoRequest $request)
    {
        $this->alunoService->criarAluno($request->validated());

        return redirect()->route('secretaria-escolar.alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    /**
     * Detalhes do aluno.
     */
    public function show(Aluno $aluno)
    {
        $this->authorize('detalhar aluno');
        return view('secretaria-escolar.alunos.show', compact('aluno'));
    }

    /**
     * Tela de edição de aluno.
     */
    public function edit(Aluno $aluno)
    {
        $this->authorize('editar aluno');
        return view('secretaria-escolar.alunos.edit', compact('aluno'));
    }

    /**
     * Atualizar dados do aluno.
     */
    public function update(UpdateAlunoRequest $request, Aluno $aluno)
    {
        $this->alunoService->atualizarAluno($aluno, $request->validated());

        return redirect()->route('secretaria-escolar.alunos.index')
            ->with('success', 'Dados do aluno atualizados com sucesso!');
    }

    /**
     * Alternar status Ativo/Inativo.
     */
    public function toggleStatus(Aluno $aluno)
    {
        $this->authorize('ativar inativar aluno');
        $this->alunoService->alternarStatus($aluno);

        return redirect()->back()->with('success', 'Status do aluno alterado com sucesso!');
    }
}
