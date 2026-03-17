<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcompanhamentoPedagogicoAlunoRequest;
use App\Http\Requests\StorePendenciaDocenteCoordenacaoRequest;
use App\Http\Requests\StoreValidacaoPlanejamentoAnualRequest;
use App\Http\Requests\StoreValidacaoPlanejamentoPeriodoRequest;
use App\Http\Requests\StoreValidacaoPlanejamentoSemanalRequest;
use App\Http\Requests\StoreValidacaoRegistroAulaRequest;
use App\Http\Requests\UpdateLancamentoAvaliativoCoordenacaoRequest;
use App\Http\Requests\UpdateRegistroAulaCoordenacaoRequest;
use App\Models\DiarioProfessor;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\PlanejamentoSemanal;
use App\Models\RegistroAula;
use App\Services\CoordenacaoPedagogicaService;
use Illuminate\Http\Request;

class CoordenacaoPedagogicaController extends Controller
{
    public function __construct(
        private readonly CoordenacaoPedagogicaService $coordenacaoPedagogicaService
    ) {
    }

    public function index(Request $request)
    {
        abort_unless($request->user()->can('acompanhar diarios pedagogicamente'), 403);

        return view('secretaria-escolar.coordenacao.diarios.index', [
            'diarios' => $this->coordenacaoPedagogicaService->listarDiarios(
                $request->user(),
                $request->only([
                    'escola_id',
                    'turma_id',
                    'disciplina_id',
                    'professor_id',
                    'ano_letivo',
                    'periodo_tipo',
                    'periodo_referencia',
                    'situacao_validacao',
                ])
            ),
            'filtros' => $this->coordenacaoPedagogicaService->opcoesFiltros($request->user()),
        ]);
    }

    public function show(Request $request, DiarioProfessor $diario)
    {
        $this->authorize('acompanharCoordenacao', $diario);

        return view('secretaria-escolar.coordenacao.diarios.show', $this->coordenacaoPedagogicaService->obterPainelDiario(
            $request->user(),
            $diario
        ));
    }

    public function validarPlanejamentoAnual(
        StoreValidacaoPlanejamentoAnualRequest $request,
        DiarioProfessor $diario,
        PlanejamentoAnual $planejamento
    ) {
        $this->authorize('validarPlanejamentoAnual', $diario);

        $this->coordenacaoPedagogicaService->validarPlanejamentoAnual(
            $request->user(),
            $diario,
            $planejamento,
            $request->validated()
        );

        return back()->with('success', 'Planejamento anual validado com sucesso.');
    }

    public function validarPlanejamentoSemanal(
        StoreValidacaoPlanejamentoSemanalRequest $request,
        DiarioProfessor $diario,
        PlanejamentoSemanal $planejamento
    ) {
        $this->authorize('validarPlanejamentoSemanal', $diario);

        $this->coordenacaoPedagogicaService->validarPlanejamentoSemanal(
            $request->user(),
            $diario,
            $planejamento,
            $request->validated()
        );

        return back()->with('success', 'Planejamento semanal validado com sucesso.');
    }

    public function validarPlanejamentoPeriodo(
        StoreValidacaoPlanejamentoPeriodoRequest $request,
        DiarioProfessor $diario,
        PlanejamentoPeriodo $planejamento
    ) {
        $this->authorize('validarPlanejamentoPeriodo', $diario);

        $this->coordenacaoPedagogicaService->validarPlanejamentoPeriodo(
            $request->user(),
            $diario,
            $planejamento,
            $request->validated()
        );

        return back()->with('success', 'Planejamento por periodo validado com sucesso.');
    }

    public function validarRegistroAula(
        StoreValidacaoRegistroAulaRequest $request,
        DiarioProfessor $diario,
        RegistroAula $registro
    ) {
        $this->authorize('validarRegistroAula', $diario);

        $this->coordenacaoPedagogicaService->validarRegistroAula(
            $request->user(),
            $diario,
            $registro,
            $request->validated()
        );

        return back()->with('success', 'Registro de aula validado com sucesso.');
    }

    public function updateAvaliacao(
        UpdateLancamentoAvaliativoCoordenacaoRequest $request,
        DiarioProfessor $diario,
        LancamentoAvaliativo $avaliacao
    ) {
        $this->authorize('alterarAvaliacaoCoordenacao', $diario);

        $this->coordenacaoPedagogicaService->atualizarLancamentoAvaliativo(
            $request->user(),
            $diario,
            $avaliacao,
            $request->validated()
        );

        return back()->with('success', 'Lancamento avaliativo atualizado com sucesso.');
    }

    public function updateRegistroAula(
        UpdateRegistroAulaCoordenacaoRequest $request,
        DiarioProfessor $diario,
        RegistroAula $registro
    ) {
        $this->authorize('ajustarAulaCoordenacao', $diario);

        $dados = $request->validated();
        $dados['aula_dada'] = $request->boolean('aula_dada', true);

        $this->coordenacaoPedagogicaService->ajustarRegistroAula(
            $request->user(),
            $diario,
            $registro,
            $dados
        );

        return back()->with('success', 'Aula ajustada pela coordenacao com sucesso.');
    }

    public function storeAcompanhamentoAluno(
        StoreAcompanhamentoPedagogicoAlunoRequest $request,
        DiarioProfessor $diario,
        Matricula $matricula
    ) {
        $this->authorize('acompanharRendimentoPedagogico', $diario);

        $this->coordenacaoPedagogicaService->salvarAcompanhamentoAluno(
            $request->user(),
            $diario,
            $matricula,
            $request->validated()
        );

        return back()->with('success', 'Acompanhamento do aluno registrado com sucesso.');
    }

    public function storePendencia(
        StorePendenciaDocenteCoordenacaoRequest $request,
        DiarioProfessor $diario
    ) {
        $this->authorize('gerenciarPendenciaDocente', $diario);

        $this->coordenacaoPedagogicaService->criarPendenciaDocente(
            $request->user(),
            $diario,
            $request->validated()
        );

        return back()->with('success', 'Pendencia docente registrada com sucesso.');
    }
}
