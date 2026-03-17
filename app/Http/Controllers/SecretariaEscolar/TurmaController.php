<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTurmaRequest;
use App\Http\Requests\UpdateTurmaRequest;
use App\Models\Turma;
use App\Models\Escola;
use App\Models\ModalidadeEnsino;
use App\Models\MatrizCurricular;
use App\Services\TurmaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TurmaController extends Controller
{
    use AuthorizesRequests;

    protected $turmaService;

    public function __construct(TurmaService $turmaService)
    {
        $this->turmaService = $turmaService;
    }

    /**
     * Helper para buscar matrizes acessíveis.
     */
    private function getMatrizesAcessiveis()
    {
        $escolaId = Auth::user()->escola_id;
        return MatrizCurricular::where('ativa', true)
            ->where(function($q) use ($escolaId) {
                $q->whereNull('escola_id');
                if ($escolaId) {
                    $q->orWhere('escola_id', $escolaId);
                }
            })
            ->orderBy('nome')
            ->get();
    }

    /**
     * Listagem de turmas na Secretaria Escolar.
     */
    public function index(Request $request)
    {
        $this->authorize('consultar turmas');
        
        $filtros = $request->only(['nome', 'modalidade_id', 'ano_letivo', 'ativa']);
        $filtros['escola_id'] = Auth::user()->escola_id;

        $turmas = $this->turmaService->listarTurmas($filtros);
        
        $modalidades = ModalidadeEnsino::where('ativo', true)->orderBy('nome')->get();

        return view('secretaria-escolar.turmas.index', compact('turmas', 'modalidades'));
    }

    /**
     * Formulário de criação.
     */
    public function create()
    {
        $this->authorize('cadastrar turmas');
        
        $modalidades = ModalidadeEnsino::where('ativo', true)->orderBy('nome')->get();
        $matrizes = $this->getMatrizesAcessiveis();
        
        return view('secretaria-escolar.turmas.create', compact('modalidades', 'matrizes'));
    }

    /**
     * Salvar nova turma.
     */
    public function store(StoreTurmaRequest $request)
    {
        $data = $request->validated();
        $data['escola_id'] = Auth::user()->escola_id;

        $this->turmaService->criarTurma($data);

        return redirect()->route('secretaria-escolar.turmas.index')
            ->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Detalhes da turma.
     */
    public function show(Turma $turma)
    {
        $this->authorize('detalhar turma');
        $turma->load(['escola', 'modalidade', 'matriz.disciplinas']);
        return view('secretaria-escolar.turmas.show', compact('turma'));
    }

    /**
     * Formulário de edição.
     */
    public function edit(Turma $turma)
    {
        $this->authorize('editar turmas');
        
        $modalidades = ModalidadeEnsino::where('ativo', true)->orderBy('nome')->get();
        $matrizes = $this->getMatrizesAcessiveis();
        
        return view('secretaria-escolar.turmas.edit', compact('turma', 'modalidades', 'matrizes'));
    }

    /**
     * Atualizar turma.
     */
    public function update(UpdateTurmaRequest $request, Turma $turma)
    {
        $this->turmaService->atualizarTurma($turma, $request->validated());

        return redirect()->route('secretaria-escolar.turmas.index')
            ->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Alternar status Ativa/Inativa.
     */
    public function toggleStatus(Turma $turma)
    {
        $this->authorize('excluir turmas');
        $this->turmaService->toggleStatus($turma);

        return redirect()->back()->with('success', 'Status da turma alterado com sucesso!');
    }
}
