<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiarioProfessorRequest;
use App\Http\Requests\StoreLancamentoAvaliativoRequest;
use App\Http\Requests\StoreLancamentoFrequenciaRequest;
use App\Http\Requests\StoreObservacaoAlunoRequest;
use App\Http\Requests\StoreOcorrenciaDiarioRequest;
use App\Http\Requests\StorePendenciaProfessorRequest;
use App\Http\Requests\StorePlanejamentoAnualRequest;
use App\Http\Requests\StorePlanejamentoPeriodoRequest;
use App\Http\Requests\StorePlanejamentoSemanalRequest;
use App\Http\Requests\StoreRegistroAulaRequest;
use App\Models\DiarioProfessor;
use App\Services\DiarioProfessorService;
use App\Services\PortalProfessorService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DiarioProfessorController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly DiarioProfessorService $diarioProfessorService,
        private readonly PortalProfessorService $portalProfessorService
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', DiarioProfessor::class);

        $diarios = $this->diarioProfessorService->listarDiariosParaUsuario(
            $request->user(),
            $request->only(['escola_id', 'turma_id', 'disciplina_id', 'ano_letivo', 'periodo_tipo', 'periodo_referencia'])
        );

        return view('professor.diario.index', [
            'diarios' => $diarios,
            'opcoesCriacao' => $this->diarioProfessorService->opcoesCriacaoParaUsuario($request->user()),
            'tituloPagina' => 'Diario Eletronico',
            'subtituloPagina' => 'Gerencie seus diarios, aulas, frequencias e planejamentos.',
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Diario Eletronico'],
            ]),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', DiarioProfessor::class);

        return view('professor.diario.create', [
            'opcoesCriacao' => $this->diarioProfessorService->opcoesCriacaoParaUsuario($request->user()),
            'anoAtual' => now()->year,
            'tituloPagina' => 'Novo Diario',
            'subtituloPagina' => 'Abra um diario para turma, disciplina e periodo ja vinculados ao seu horario.',
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Diario Eletronico', 'url' => route('professor.diario.index')],
                ['label' => 'Novo Diario'],
            ]),
        ]);
    }

    public function store(StoreDiarioProfessorRequest $request)
    {
        $this->authorize('create', DiarioProfessor::class);

        $diario = $this->diarioProfessorService->criarDiario(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Diario criado com sucesso.');
    }

    public function show(DiarioProfessor $diario)
    {
        $this->authorize('view', $diario);

        $diarioDetalhado = $this->diarioProfessorService->obterDiarioDetalhado($diario);

        return view('professor.diario.show', [
            'diario' => $diarioDetalhado,
            'matriculasAtivas' => $this->diarioProfessorService->listarMatriculasAtivas($diario),
            'horariosRelacionados' => $this->diarioProfessorService->listarHorariosDoDiario($diario),
            'tipoAvaliacaoDiario' => $this->diarioProfessorService->resolverTipoAvaliacao($diarioDetalhado),
            'tituloPagina' => 'Diario de ' . $diario->turma->nome,
            'subtituloPagina' => $diario->disciplina->nome . ' • ' . $diario->escola->nome,
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Diario Eletronico', 'url' => route('professor.diario.index')],
                ['label' => $diario->turma->nome],
            ]),
        ]);
    }

    public function storePlanejamentoAnual(StorePlanejamentoAnualRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('gerenciarPlanejamento', $diario);

        $this->diarioProfessorService->salvarPlanejamentoAnual($diario, $request->validated());

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Planejamento anual salvo com sucesso.');
    }

    public function storePlanejamentoSemanal(StorePlanejamentoSemanalRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('gerenciarPlanejamento', $diario);

        $this->diarioProfessorService->salvarPlanejamentoSemanal($diario, $request->validated());

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Planejamento semanal registrado com sucesso.');
    }

    public function storePlanejamentoPeriodo(StorePlanejamentoPeriodoRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('gerenciarPlanejamento', $diario);

        $this->diarioProfessorService->salvarPlanejamentoPeriodo($diario, $request->validated());

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Planejamento do periodo registrado com sucesso.');
    }

    public function storeRegistroAula(StoreRegistroAulaRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('registrarAula', $diario);

        $dados = $request->validated();
        $dados['aula_dada'] = $request->boolean('aula_dada', true);

        $this->diarioProfessorService->registrarAula($diario, $dados, $request->user()->id);

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Registro de aula salvo com sucesso.');
    }

    public function storeFrequencia(StoreLancamentoFrequenciaRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('lancarFrequencia', $diario);

        $this->diarioProfessorService->lancarFrequencia($diario, $request->validated());

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Frequencia registrada com sucesso.');
    }

    public function storeAvaliacao(StoreLancamentoAvaliativoRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('gerenciarPlanejamento', $diario);

        $this->diarioProfessorService->lancarAvaliacao($diario, $request->validated(), $request->user()->id);

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Lancamento avaliativo salvo com sucesso.');
    }

    public function storeObservacao(StoreObservacaoAlunoRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('registrarObservacao', $diario);

        $dados = $request->validated();
        $dados['destaque'] = $request->boolean('destaque');

        $this->diarioProfessorService->registrarObservacao($diario, $dados, $request->user()->id);

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Observacao registrada com sucesso.');
    }

    public function storeOcorrencia(StoreOcorrenciaDiarioRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('registrarOcorrencia', $diario);

        $this->diarioProfessorService->registrarOcorrencia($diario, $request->validated(), $request->user()->id);

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Ocorrencia registrada com sucesso.');
    }

    public function storePendencia(StorePendenciaProfessorRequest $request, DiarioProfessor $diario)
    {
        $this->authorize('gerenciarPendencia', $diario);

        $this->diarioProfessorService->registrarPendencia($diario, $request->validated(), $request->user()->id);

        return redirect()
            ->route('professor.diario.show', $diario)
            ->with('success', 'Pendencia registrada com sucesso.');
    }
}
