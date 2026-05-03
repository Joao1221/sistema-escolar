<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFechamentoLetivoRequest;
use App\Http\Requests\StoreFaltaFuncionarioDirecaoRequest;
use App\Http\Requests\StoreJustificativaFaltaAlunoRequest;
use App\Http\Requests\StoreLiberacaoPrazoProfessorRequest;
use App\Http\Requests\StoreValidacaoPlanejamentoPeriodoDirecaoRequest;
use App\Http\Requests\StoreValidacaoPlanejamentoDirecaoRequest;
use App\Http\Requests\StoreValidacaoRegistroAulaDirecaoRequest;
use App\Models\DiarioProfessor;
use App\Models\FrequenciaAula;
use App\Models\LancamentoAvaliativo;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\RegistroAula;
use App\Http\Requests\UpdateLancamentoAvaliativoDirecaoRequest;
use App\Http\Requests\UpdateRegistroAulaDirecaoRequest;
use App\Services\DirecaoEscolarService;
use Illuminate\Http\Request;

class DirecaoEscolarController extends Controller
{
    public function __construct(
        private readonly DirecaoEscolarService $direcaoEscolarService
    ) {
    }

    public function index(Request $request)
    {
        abort_unless($request->user()->can('acompanhar diarios da direcao'), 403);

        return view('secretaria-escolar.direcao.diarios.index', [
            'diarios' => $this->direcaoEscolarService->listarDiarios(
                $request->user(),
                $request->only([
                    'escola_id',
                    'turma_id',
                    'disciplina_id',
                    'professor_id',
                    'ano_letivo',
                    'situacao_validacao',
                ])
            ),
            ...$this->direcaoEscolarService->obterPainelInicial(
                $request->user(),
                $request->only(['escola_id', 'turma_id'])
            ),
        ]);
    }

    public function show(Request $request, DiarioProfessor $diario)
    {
        $this->authorize('acompanharDirecao', $diario);

        return view('secretaria-escolar.direcao.diarios.show', $this->direcaoEscolarService->obterPainelDiario(
            $request->user(),
            $diario
        ));
    }

    public function validarPlanejamentoAnual(
        StoreValidacaoPlanejamentoDirecaoRequest $request,
        DiarioProfessor $diario,
        PlanejamentoAnual $planejamento
    ) {
        $this->authorize('validarPlanejamentoDirecao', $diario);

        $this->direcaoEscolarService->validarPlanejamento(
            $request->user(),
            $diario,
            $planejamento,
            $request->validated()
        );

        return back()->with('success', 'Planejamento anual validado pela direcao com sucesso.');
    }

    public function validarPlanejamentoPeriodo(
        StoreValidacaoPlanejamentoPeriodoDirecaoRequest $request,
        DiarioProfessor $diario,
        PlanejamentoPeriodo $planejamento
    ) {
        $this->authorize('validarPlanejamentoPeriodoDirecao', $diario);

        $this->direcaoEscolarService->validarPlanejamento(
            $request->user(),
            $diario,
            $planejamento,
            $request->validated()
        );

        return back()->with('success', 'Planejamento por periodo validado pela direcao com sucesso.');
    }

    public function validarRegistroAula(
        StoreValidacaoRegistroAulaDirecaoRequest $request,
        DiarioProfessor $diario,
        RegistroAula $registro
    ) {
        $this->authorize('validarRegistroAulaDirecao', $diario);

        $this->direcaoEscolarService->validarRegistroAula(
            $request->user(),
            $diario,
            $registro,
            $request->validated()
        );

        return back()->with('success', 'Registro de aula validado pela direcao com sucesso.');
    }

    public function updateRegistroAula(
        UpdateRegistroAulaDirecaoRequest $request,
        DiarioProfessor $diario,
        RegistroAula $registro
    ) {
        $this->authorize('ajustarAulaDirecao', $diario);

        $dados = $request->validated();
        $dados['aula_dada'] = $request->boolean('aula_dada', true);

        $this->direcaoEscolarService->ajustarRegistroAula(
            $request->user(),
            $diario,
            $registro,
            $dados
        );

        return back()->with('success', 'Aula ajustada pela direcao com sucesso.');
    }

    public function updateAvaliacao(
        UpdateLancamentoAvaliativoDirecaoRequest $request,
        DiarioProfessor $diario,
        LancamentoAvaliativo $avaliacao
    ) {
        $this->authorize('alterarAvaliacaoDirecao', $diario);

        $this->direcaoEscolarService->atualizarLancamentoAvaliativo(
            $request->user(),
            $diario,
            $avaliacao,
            $request->validated()
        );

        return back()->with('success', 'Lancamento avaliativo atualizado pela direcao com sucesso.');
    }

    public function justificarFalta(
        StoreJustificativaFaltaAlunoRequest $request,
        DiarioProfessor $diario,
        FrequenciaAula $frequencia
    ) {
        $this->authorize('justificarFaltaAluno', $diario);

        $this->direcaoEscolarService->justificarFaltaAluno(
            $request->user(),
            $diario,
            $frequencia,
            $request->validated()
        );

        return back()->with('success', 'Falta do aluno justificada com sucesso.');
    }

    public function storeLiberacaoPrazo(
        StoreLiberacaoPrazoProfessorRequest $request,
        DiarioProfessor $diario
    ) {
        $this->authorize('liberarPrazoLancamento', $diario);

        $this->direcaoEscolarService->liberarPrazoLancamento(
            $request->user(),
            $diario,
            $request->validated()
        );

        return back()->with('success', 'Liberacao de prazo registrada com sucesso.');
    }

    public function storeFaltaFuncionario(StoreFaltaFuncionarioDirecaoRequest $request)
    {
        abort_unless($request->user()->can('registrar faltas de funcionarios'), 403);

        $this->direcaoEscolarService->registrarFaltaFuncionario(
            $request->user(),
            $request->validated()
        );

        return back()->with('success', 'Falta de funcionario registrada com sucesso.');
    }

    public function storeFechamentoLetivo(StoreFechamentoLetivoRequest $request)
    {
        $dados = $request->validated();

        abort_unless(
            $request->user()->can($dados['status'] === 'concluido' ? 'concluir fechamento letivo' : 'iniciar fechamento letivo'),
            403
        );

        $this->direcaoEscolarService->salvarFechamentoLetivo(
            $request->user(),
            $dados
        );

        return back()->with('success', 'Fluxo inicial de fechamento letivo registrado com sucesso.');
    }
}
