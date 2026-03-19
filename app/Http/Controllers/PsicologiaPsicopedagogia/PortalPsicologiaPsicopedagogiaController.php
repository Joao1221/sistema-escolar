<?php

namespace App\Http\Controllers\PsicologiaPsicopedagogia;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmitirDocumentoRequest;
use App\Http\Requests\GerarRelatorioRequest;
use App\Http\Requests\StoreAtendimentoPsicossocialRequest;
use App\Http\Requests\StoreCasoDisciplinarSigilosoRequest;
use App\Http\Requests\StoreEncaminhamentoPsicossocialRequest;
use App\Http\Requests\StorePlanoIntervencaoPsicossocialRequest;
use App\Http\Requests\StoreRelatorioTecnicoPsicossocialRequest;
use App\Models\AtendimentoPsicossocial;
use App\Services\AuditoriaService;
use App\Services\DocumentoEscolarService;
use App\Services\PsicossocialService;
use App\Services\RelatorioPortalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PortalPsicologiaPsicopedagogiaController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly PsicossocialService $psicossocialService,
        private readonly DocumentoEscolarService $documentoEscolarService,
        private readonly RelatorioPortalService $relatorioPortalService,
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:acessar modulo psicossocial'),
            new Middleware('can:acessar dados sigilosos psicossociais'),
            new Middleware('can:consultar agenda psicossocial', only: ['dashboard', 'agenda']),
            new Middleware('can:consultar historico psicossocial', only: ['atendimentos', 'historico', 'show', 'planos', 'encaminhamentos', 'casos']),
            new Middleware('can:registrar atendimentos psicossociais', only: ['create', 'store']),
            new Middleware('can:registrar planos de intervencao psicossociais', only: ['storePlano']),
            new Middleware('can:registrar encaminhamentos psicossociais', only: ['storeEncaminhamento']),
            new Middleware('can:registrar casos disciplinares sigilosos', only: ['storeCaso']),
            new Middleware('can:emitir relatorios tecnicos psicossociais', only: ['storeRelatorio']),
            new Middleware('can:consultar documentos psicossociais', only: ['documentos', 'previewDocumento', 'imprimirDocumento']),
            new Middleware('can:consultar relatorios tecnicos do psicossocial', only: ['relatoriosTecnicos', 'previewRelatorioTecnico', 'imprimirRelatorioTecnico']),
            new Middleware('can:consultar auditoria psicossocial sigilosa', only: ['auditoria']),
        ];
    }

    public function index()
    {
        return redirect()->route('psicologia.dashboard');
    }

    public function dashboard(Request $request)
    {
        return view('psicologia-psicopedagogia.dashboard', [
            ...$this->psicossocialService->obterPainel($request->user()),
            'tituloPagina' => 'Dashboard restrito',
            'subtituloPagina' => 'Visao tecnica do fluxo psicologico e psicopedagogico, com acesso sigiloso e controle de contexto escolar.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Dashboard'],
            ]),
        ]);
    }

    public function agenda(Request $request)
    {
        return view('psicologia-psicopedagogia.agenda', [
            'atendimentos' => $this->psicossocialService->listarAgenda($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Agenda',
            'subtituloPagina' => 'Compromissos sigilosos organizados por escola, status e tipo de publico.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Agenda'],
            ]),
        ]);
    }

    public function atendimentos(Request $request)
    {
        return view('psicologia-psicopedagogia.atendimentos.index', [
            'atendimentos' => $this->psicossocialService->listarAtendimentos($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Atendimentos',
            'subtituloPagina' => 'Registro consolidado dos atendimentos em andamento, realizados ou pendentes.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Atendimentos'],
            ]),
        ]);
    }

    public function historico(Request $request)
    {
        return view('psicologia-psicopedagogia.historico', [
            'atendimentos' => $this->psicossocialService->listarHistorico($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Historico',
            'subtituloPagina' => 'Consulta retroativa dos atendimentos concluidos, cancelados ou nao comparecidos.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Historico'],
            ]),
        ]);
    }

    public function planos(Request $request)
    {
        return view('psicologia-psicopedagogia.planos.index', [
            'planos' => $this->psicossocialService->listarPlanos($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Planos de intervencao',
            'subtituloPagina' => 'Acompanhamento tecnico dos planos em andamento, concluidos ou sob revisao.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Planos de intervencao'],
            ]),
        ]);
    }

    public function encaminhamentos(Request $request)
    {
        return view('psicologia-psicopedagogia.encaminhamentos.index', [
            'encaminhamentos' => $this->psicossocialService->listarEncaminhamentos($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Encaminhamentos',
            'subtituloPagina' => 'Encaminhamentos internos e externos com rastreio restrito por escola.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Encaminhamentos'],
            ]),
        ]);
    }

    public function casos(Request $request)
    {
        return view('psicologia-psicopedagogia.casos.index', [
            'casos' => $this->psicossocialService->listarCasos($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Casos disciplinares',
            'subtituloPagina' => 'Ocorrencias sigilosas vinculadas ao acompanhamento tecnico quando aplicavel.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Casos disciplinares'],
            ]),
        ]);
    }

    public function relatoriosTecnicos(Request $request)
    {
        return view('psicologia-psicopedagogia.relatorios-tecnicos.index', [
            'relatorios' => $this->psicossocialService->listarRelatoriosTecnicos($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Relatorios tecnicos',
            'subtituloPagina' => 'Painel restrito de producoes tecnicas da equipe psicologica e psicopedagogica.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Relatorios tecnicos'],
            ]),
        ]);
    }

    public function documentos(Request $request)
    {
        return view('psicologia-psicopedagogia.documentos.index', [
            'documentos' => $this->documentoEscolarService->documentosDisponiveis('psicossocial', $request->user()),
            'opcoesFormulario' => $this->documentoEscolarService->opcoesFormulario('psicossocial', $request->user()),
            'tituloPagina' => 'Documentos restritos',
            'subtituloPagina' => 'Registros de atendimento, relatorios e encaminhamentos com fluxo de sigilo reforcado.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Documentos restritos'],
            ]),
        ]);
    }

    public function previewDocumento(EmitirDocumentoRequest $request, string $tipo)
    {
        return view('psicologia-psicopedagogia.documentos.preview', [
            'documento' => $this->documentoEscolarService->emitir('psicossocial', $tipo, $request->user(), $request->validated()),
            'tipoDocumento' => $tipo,
            'payload' => $request->validated(),
            'tituloPagina' => 'Pre-visualizacao de documento',
            'subtituloPagina' => 'Conteudo sigiloso preparado para revisao antes da impressao.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Documentos restritos', 'url' => route('psicologia.documentos.index')],
                ['label' => 'Pre-visualizacao'],
            ]),
        ]);
    }

    public function imprimirDocumento(EmitirDocumentoRequest $request, string $tipo)
    {
        return view('documentos.impressao', [
            'documento' => $this->documentoEscolarService->emitir('psicossocial', $tipo, $request->user(), $request->validated()),
        ]);
    }

    public function previewRelatorioTecnico(GerarRelatorioRequest $request, string $tipo)
    {
        return view('psicologia-psicopedagogia.relatorios-tecnicos.preview', [
            'relatorio' => $this->relatorioPortalService->gerar('psicossocial', $tipo, $request->user(), $request->validated()),
            'tipoRelatorio' => $tipo,
            'payload' => $request->validated(),
            'tituloPagina' => 'Pre-visualizacao de relatorio',
            'subtituloPagina' => 'Relatorio tecnico restrito com os filtros aplicados no momento da emissao.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Relatorios tecnicos', 'url' => route('psicologia.relatorios_tecnicos.index')],
                ['label' => 'Pre-visualizacao'],
            ]),
        ]);
    }

    public function imprimirRelatorioTecnico(GerarRelatorioRequest $request, string $tipo)
    {
        return view('relatorios.impressao', [
            'relatorio' => $this->relatorioPortalService->gerar('psicossocial', $tipo, $request->user(), $request->validated()),
        ]);
    }

    public function create(Request $request)
    {
        return view('psicologia-psicopedagogia.create', [
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'tituloPagina' => 'Novo atendimento',
            'subtituloPagina' => 'Cadastro de atendimento para aluno, professor, funcionario ou responsavel.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Novo atendimento'],
            ]),
        ]);
    }

    public function store(StoreAtendimentoPsicossocialRequest $request)
    {
        $atendimento = $this->psicossocialService->criarAtendimento($request->user(), $request->validated());

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Atendimento registrado com sucesso.');
    }

    public function show(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        return view('psicologia-psicopedagogia.show', [
            'atendimento' => $this->psicossocialService->carregarAtendimento($request->user(), $atendimento),
            'tituloPagina' => 'Atendimento sigiloso',
            'subtituloPagina' => 'Visao tecnica do caso, com planos, encaminhamentos e relatorios restritos.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Atendimentos', 'url' => route('psicologia.atendimentos.index')],
                ['label' => 'Detalhe'],
            ]),
        ]);
    }

    public function storePlano(StorePlanoIntervencaoPsicossocialRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('createPlano', $atendimento);
        $this->psicossocialService->criarPlano($request->user(), $atendimento, $request->validated());

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Plano de intervencao registrado com sucesso.');
    }

    public function storeEncaminhamento(StoreEncaminhamentoPsicossocialRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('createEncaminhamento', $atendimento);
        $this->psicossocialService->criarEncaminhamento($request->user(), $atendimento, $request->validated());

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Encaminhamento registrado com sucesso.');
    }

    public function storeCaso(StoreCasoDisciplinarSigilosoRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('createCaso', $atendimento);
        $this->psicossocialService->criarCasoDisciplinar($request->user(), $atendimento, $request->validated());

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Caso disciplinar sigiloso registrado com sucesso.');
    }

    public function storeRelatorio(StoreRelatorioTecnicoPsicossocialRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('emitirRelatorio', $atendimento);
        $this->psicossocialService->criarRelatorio($request->user(), $atendimento, $request->validated());

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Relatorio tecnico emitido com sucesso.');
    }

    public function auditoria(Request $request)
    {
        return view('psicologia-psicopedagogia.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('psicossocial'),
            'registros' => $this->auditoriaService->listarRegistros('psicossocial', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('psicossocial', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('psicossocial', $request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Auditoria restrita',
            'subtituloPagina' => 'Rastros de operacoes sigilosas, com contexto tecnico e acesso controlado.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Auditoria restrita'],
            ]),
        ]);
    }
}
