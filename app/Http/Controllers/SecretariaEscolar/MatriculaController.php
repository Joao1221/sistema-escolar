<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatriculaRequest;
use App\Models\Matricula;
use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Turma;
use App\Services\MatriculaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MatriculaController extends Controller
{
    use AuthorizesRequests;

    protected $matriculaService;

    public function __construct(MatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    /**
     * Listagem de matrículas da unidade.
     */
    public function index(Request $request)
    {
        $this->authorize('consultar matrículas');
        
        $filtros = $request->only(['aluno_nome', 'status', 'tipo', 'ano_letivo']);
        // Forçamos a escola do usuário logado através do novo acessor
        $filtros['escola_id'] = Auth::user()->escola_id; 

        $matriculas = $this->matriculaService->listarMatriculas($filtros);

        return view('secretaria-escolar.matriculas.index', compact('matriculas'));
    }

    /**
     * Tela de nova matrícula.
     */
    public function create()
    {
        $this->authorize('cadastrar matrícula');
        
        $alunos = Aluno::where('ativo', true)->orderBy('nome_completo')->get();
        // Apenas turmas da escola do usuário logado
        $escolaId = Auth::user()->escola_id; 
        $turmas = Turma::where('escola_id', $escolaId)->where('ativa', true)->get();

        return view('secretaria-escolar.matriculas.create', compact('alunos', 'turmas'));
    }

    /**
     * Salvar matrícula.
     */
    public function store(StoreMatriculaRequest $request)
    {
        $data = $request->validated();
        $data['escola_id'] = Auth::user()->escola_id;

        $this->matriculaService->realizarMatricula($data);

        return redirect()->route('secretaria-escolar.matriculas.index')
            ->with('success', 'Matrícula realizada com sucesso!');
    }

    /**
     * Detalhes e Histórico.
     */
    public function show(Matricula $matricula)
    {
        $this->authorize('visualizar detalhes da matrícula');
        $matricula->load(['aluno', 'escola', 'turma', 'historico.usuario']);

        return view('secretaria-escolar.matriculas.show', compact('matricula'));
    }

    /**
     * Tela de enturmação.
     */
    public function enturmarForm(Matricula $matricula)
    {
        $this->authorize('enturmar');
        $turmas = Turma::where('escola_id', $matricula->escola_id)->where('ativa', true)->get();

        return view('secretaria-escolar.matriculas.enturmar', compact('matricula', 'turmas'));
    }

    /**
     * Processar enturmação.
     */
    public function enturmarStore(Request $request, Matricula $matricula)
    {
        $this->authorize('enturmar');
        $request->validate(['turma_id' => 'required|exists:turmas,id']);

        $this->matriculaService->enturmar($matricula, $request->turma_id);

        return redirect()->route('secretaria-escolar.matriculas.show', $matricula)
            ->with('success', 'Aluno enturmado com sucesso!');
    }

    /**
     * Tela de transferência.
     */
    public function transferirForm(Matricula $matricula)
    {
        $this->authorize('transferir');
        return view('secretaria-escolar.matriculas.transferir', compact('matricula'));
    }

    /**
     * Processar transferência.
     */
    public function transferirStore(Request $request, Matricula $matricula)
    {
        $this->authorize('transferir');
        $request->validate(['motivo' => 'required|string|max:255']);

        $this->matriculaService->transferir($matricula, $request->motivo);

        return redirect()->route('secretaria-escolar.matriculas.index')
            ->with('success', 'Transferência registrada com sucesso!');
    }

    /**
     * Tela de rematrícula.
     */
    public function rematricularForm(Matricula $matricula)
    {
        $this->authorize('rematricular');
        return view('secretaria-escolar.matriculas.rematricular', compact('matricula'));
    }

    /**
     * Processar rematrícula.
     */
    public function rematricularStore(Request $request, Matricula $matricula)
    {
        $this->authorize('rematricular');
        $request->validate(['ano_letivo' => 'required|integer|min:2024']);

        $this->matriculaService->rematricular($matricula, $request->ano_letivo);

        return redirect()->route('secretaria-escolar.matriculas.index')
            ->with('success', 'Rematrícula realizada com sucesso!');
    }
}
