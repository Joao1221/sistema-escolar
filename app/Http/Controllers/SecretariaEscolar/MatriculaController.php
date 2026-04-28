<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnturmarMatriculaRequest;
use App\Http\Requests\RematricularMatriculaRequest;
use App\Http\Requests\StoreMatriculaRequest;
use App\Http\Requests\TransferirMatriculaRequest;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Turma;
use App\Services\MatriculaService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatriculaController extends Controller
{
    use AuthorizesRequests;

    protected MatriculaService $matriculaService;

    public function __construct(MatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    /**
     * Listagem de matrículas da unidade.
     */
    public function index(Request $request): View
    {
        $this->authorize('consultar matrículas');

        $escolaId = Auth::user()->escola_id;
        $filtros = $request->only(['aluno_nome', 'status', 'tipo', 'ano_letivo', 'turma_id']);
        $filtros['escola_id'] = $escolaId;

        $matriculas = $this->matriculaService->listarMatriculas($filtros);
        $turmas = Turma::where('escola_id', $escolaId)
            ->orderBy('nome')
            ->get();

        $stats = $this->matriculaService->obterStats($escolaId);
        $anosDisponiveis = $this->matriculaService->obterAnosLetivos($escolaId);

        return view('secretaria-escolar.matriculas.index', compact('matriculas', 'turmas', 'stats', 'anosDisponiveis'));
    }

    /**
     * Tela de nova matrícula.
     */
    public function create(): View
    {
        $this->authorize('cadastrar matrícula');

        $escolaId = Auth::user()->escola_id;
        $alunos = Aluno::where('escola_id', $escolaId)
            ->where('ativo', true)
            ->orderBy('nome_completo')
            ->get();
        $turmas = Turma::where('escola_id', $escolaId)->where('ativa', true)->get();

        return view('secretaria-escolar.matriculas.create', compact('alunos', 'turmas'));
    }

    /**
     * Salvar matrícula.
     */
    public function store(StoreMatriculaRequest $request): RedirectResponse
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
    public function show(Matricula $matricula): View
    {
        $this->authorize('visualizar detalhes da matrícula');
        $matricula->load(['aluno', 'escola', 'turma', 'historico.usuario']);

        return view('secretaria-escolar.matriculas.show', compact('matricula'));
    }

    /**
     * Tela de enturmação.
     */
    public function enturmarForm(Matricula $matricula): View
    {
        $this->authorize('enturmar');
        $turmas = Turma::where('escola_id', $matricula->escola_id)->where('ativa', true)->get();

        return view('secretaria-escolar.matriculas.enturmar', compact('matricula', 'turmas'));
    }

    /**
     * Processar enturmação.
     */
    public function enturmarStore(EnturmarMatriculaRequest $request, Matricula $matricula): RedirectResponse
    {
        $this->authorize('enturmar');

        $this->matriculaService->enturmar($matricula, (int) $request->validated('turma_id'));

        return redirect()->route('secretaria-escolar.matriculas.show', $matricula)
            ->with('success', 'Aluno enturmado com sucesso!');
    }

    /**
     * Tela de transferência.
     */
    public function transferirForm(Matricula $matricula): View
    {
        $this->authorize('transferir');

        return view('secretaria-escolar.matriculas.transferir', compact('matricula'));
    }

    /**
     * Processar transferência.
     */
    public function transferirStore(TransferirMatriculaRequest $request, Matricula $matricula): RedirectResponse
    {
        $this->authorize('transferir');

        $this->matriculaService->transferir($matricula, (string) $request->validated('motivo'));

        return redirect()->route('secretaria-escolar.matriculas.index')
            ->with('success', 'Transferência registrada com sucesso!');
    }

    /**
     * Tela de rematrícula.
     */
    public function rematricularForm(Matricula $matricula): View
    {
        $this->authorize('rematricular');

        return view('secretaria-escolar.matriculas.rematricular', compact('matricula'));
    }

    /**
     * Processar rematrícula.
     */
    public function rematricularStore(RematricularMatriculaRequest $request, Matricula $matricula): RedirectResponse
    {
        $this->authorize('rematricular');

        $this->matriculaService->rematricular($matricula, (int) $request->validated('ano_letivo'));

        return redirect()->route('secretaria-escolar.matriculas.index')
            ->with('success', 'Rematrícula realizada com sucesso!');
    }

    /**
     * Tela de edição da matrícula.
     */
    public function edit(Matricula $matricula): View
    {
        // $this->authorize('editar matrícula');

        $escolaId = Auth::user()->escola_id;
        $alunos = Aluno::where('escola_id', $escolaId)
            ->where('ativo', true)
            ->orderBy('nome_completo')
            ->get();
        $turmas = Turma::where('escola_id', $escolaId)->where('ativa', true)->get();

        return view('secretaria-escolar.matriculas.edit', compact('matricula', 'alunos', 'turmas'));
    }

    /**
     * Atualizar matrícula.
     */
    public function update(Request $request, Matricula $matricula): RedirectResponse
    {
        // $this->authorize('editar matrícula');

        $user = Auth::user();
        $isGestor = $user->hasRole('Coordenador Pedagógico') || $user->hasRole('Diretor Escolar') || $user->hasRole('Admin');

        $rules = [
            'aluno_id' => 'required|exists:alunos,id',
            'tipo' => 'required|in:regular,aee',
            'status' => 'required|in:ativa,concluida,transferida,cancelada',
            'ano_letivo' => 'required|integer|min:2000|max:2100',
            'serie_pretendida' => 'required|string|max:50',
            'turno' => 'nullable|in:manha,tarde,noite,integral',
            'escola_origem' => 'nullable|string|max:255',
            'escola_inep' => 'nullable|string|max:8',
            'rede' => 'nullable|in:municipal,publica,privada,outra',
            'cidade_uf' => 'nullable|string|max:100',
            'serie_cursada' => 'nullable|string|max:50',
            'ano_cursado' => 'nullable|integer|min:2000|max:2100',
            'situacao' => 'nullable|in:transferido,concluiu,cursando,desistente',
            'data_transferencia' => 'nullable|date',
            'transporte' => 'nullable|in:0,1',
            'transporte_veiculo' => 'nullable|in:nao,vans,onibus,bicicleta,outros',
            'bolsa_familia' => 'nullable|in:0,1',
            'bolsa_cartao' => 'nullable|string|max:11',
            'escolarizacao_outro' => 'nullable|in:nao,hospital,domicilio',
            'observacoes' => 'nullable|string',
            'pendencias' => 'nullable|boolean',
            'obs_pendencias' => 'nullable|string',
        ];

        if ($isGestor) {
            $rules['turma_id'] = 'nullable|exists:turmas,id';
        }

        $validated = $request->validate($rules);

        if (!$isGestor) {
            unset($validated['turma_id']);
        }

        $matricula->update($validated);

        return redirect()->route('secretaria-escolar.matriculas.show', $matricula)
            ->with('success', 'Matrícula atualizada com sucesso!');
    }
}
