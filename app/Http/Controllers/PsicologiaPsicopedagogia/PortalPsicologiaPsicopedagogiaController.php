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
use App\Models\DemandaPsicossocial;
use App\Models\DevolutivaPsicossocial;
use App\Models\EncaminhamentoPsicossocial;
use App\Models\PlanoIntervencaoPsicossocial;
use App\Models\ReavaliacaoPsicossocial;
use App\Models\RelatorioTecnicoPsicossocial;
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
            new Middleware('can:emitir relatorios tecnicos psicossociais', only: ['storeRelatorio', 'editRelatorioTecnico', 'updateRelatorioTecnico', 'destroyRelatorioTecnico']),
            new Middleware('can:consultar documentos psicossociais', only: ['documentos', 'previewDocumento', 'imprimirDocumento']),
            new Middleware('can:consultar relatorios tecnicos do psicossocial', only: ['relatoriosTecnicos', 'previewRelatorioTecnico', 'imprimirRelatorioTecnico', 'showRelatorioTecnicoEmitido', 'imprimirRelatorioTecnicoEmitido', 'editRelatorioTecnico', 'updateRelatorioTecnico', 'destroyRelatorioTecnico']),
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
        // Página de atendimentos passa a ser atendida pelo Histórico
        return redirect()->route('psicologia.historico.index');
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

    public function showRelatorioTecnicoEmitido(Request $request, RelatorioTecnicoPsicossocial $relatorio)
    {
        $relatorio = $this->psicossocialService->carregarRelatorioTecnico($request->user(), $relatorio);
        $documento = $this->documentoEscolarService->emitir('psicossocial', 'relatorio-tecnico', $request->user(), [
            'relatorio_id' => $relatorio->id,
        ]);

        return view('psicologia-psicopedagogia.documentos.preview', [
            'documento' => $documento,
            'tipoDocumento' => 'relatorio-tecnico',
            'payload' => ['relatorio_id' => $relatorio->id],
            'urlImpressaoDireta' => route('psicologia.relatorios_tecnicos.emitidos.print', $relatorio),
            'acoesDocumento' => [
                [
                    'tipo' => 'link',
                    'label' => 'Editar relatorio',
                    'url' => route('psicologia.relatorios_tecnicos.edit', $relatorio),
                    'estilo' => 'secundario',
                ],
                [
                    'tipo' => 'form',
                    'label' => 'Excluir relatorio',
                    'url' => route('psicologia.relatorios_tecnicos.destroy', $relatorio),
                    'metodo' => 'DELETE',
                    'confirmacao' => 'Excluir este relatorio tecnico? Esta acao nao pode ser desfeita.',
                    'estilo' => 'perigo',
                ],
            ],
            'tituloPagina' => 'Relatorio tecnico emitido',
            'subtituloPagina' => 'Visualizacao do documento tecnico salvo no atendimento.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Relatorios tecnicos', 'url' => route('psicologia.relatorios_tecnicos.index')],
                ['label' => 'Visualizacao'],
            ]),
        ]);
    }

    public function imprimirRelatorioTecnicoEmitido(Request $request, RelatorioTecnicoPsicossocial $relatorio)
    {
        $relatorio = $this->psicossocialService->carregarRelatorioTecnico($request->user(), $relatorio);

        return view('documentos.impressao', [
            'documento' => $this->documentoEscolarService->emitir('psicossocial', 'relatorio-tecnico', $request->user(), [
                'relatorio_id' => $relatorio->id,
            ]),
            'orientacaoPagina' => 'portrait',
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
            'subtituloPagina' => 'Cadastro de atendimento para aluno, professor, funcionario, responsavel ou publico coletivo.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Novo atendimento'],
            ]),
        ]);
    }

    public function store(StoreAtendimentoPsicossocialRequest $request)
    {
        $atendimento = $this->psicossocialService->criarAtendimento($request->user(), $request->validated());

        $rotaDestino = $atendimento->visivelParaUsuario($request->user())
            ? route('psicologia.show', $atendimento)
            : route('psicologia.dashboard');

        return redirect()->to($rotaDestino)
            ->with('success', 'Atendimento registrado com sucesso.');
    }

    public function finalizar(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        $atendimento->update([
            'status' => 'realizado',
            'data_realizacao' => now(),
        ]);

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Atendimento iniciado com sucesso.');
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

    public function editPlano(PlanoIntervencaoPsicossocial $plano)
    {
        $this->authorize('view', $plano->atendimento);

        $atendimento = $plano->atendimento;

        return view('psicologia-psicopedagogia.planos.editar', [
            'plano' => $plano,
            'atendimento' => $atendimento,
            'tituloPagina' => 'Editar Plano de Intervencao',
            'subtituloPagina' => 'Atualize os dados do plano de intervencao.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Atendimentos', 'url' => route('psicologia.atendimentos.index')],
                ['label' => $atendimento->nome_atendido, 'url' => route('psicologia.show', $atendimento)],
                ['label' => 'Editar Plano'],
            ]),
        ]);
    }

    public function updatePlano(StorePlanoIntervencaoPsicossocialRequest $request, PlanoIntervencaoPsicossocial $plano)
    {
        $this->authorize('view', $plano->atendimento);

        $plano->update($request->validated());

        return redirect()->route('psicologia.show', $plano->atendimento)
            ->with('success', 'Plano de intervencao atualizado com sucesso.');
    }

    public function destroyPlano(PlanoIntervencaoPsicossocial $plano)
    {
        $this->authorize('view', $plano->atendimento);

        $atendimento = $plano->atendimento;
        $plano->delete();

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Plano de intervencao excluido com sucesso.');
    }

    public function storeEncaminhamento(StoreEncaminhamentoPsicossocialRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('createEncaminhamento', $atendimento);
        $this->psicossocialService->criarEncaminhamento($request->user(), $atendimento, $request->validated());

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Encaminhamento registrado com sucesso.');
    }

    public function editEncaminhamento(EncaminhamentoPsicossocial $encaminhamento)
    {
        $this->authorize('view', $encaminhamento->atendimento);

        $atendimento = $encaminhamento->atendimento;

        return view('psicologia-psicopedagogia.encaminhamentos.editar', [
            'encaminhamento' => $encaminhamento,
            'atendimento' => $atendimento,
            'tituloPagina' => 'Editar Encaminhamento',
            'subtituloPagina' => 'Atualize os dados do encaminhamento.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Atendimentos', 'url' => route('psicologia.atendimentos.index')],
                ['label' => $atendimento->nome_atendido, 'url' => route('psicologia.show', $atendimento)],
                ['label' => 'Editar Encaminhamento'],
            ]),
        ]);
    }

    public function updateEncaminhamento(StoreEncaminhamentoPsicossocialRequest $request, EncaminhamentoPsicossocial $encaminhamento)
    {
        $this->authorize('view', $encaminhamento->atendimento);

        $encaminhamento->update($request->validated());

        return redirect()->route('psicologia.show', $encaminhamento->atendimento)
            ->with('success', 'Encaminhamento atualizado com sucesso.');
    }

    public function destroyEncaminhamento(EncaminhamentoPsicossocial $encaminhamento)
    {
        $this->authorize('view', $encaminhamento->atendimento);

        $atendimento = $encaminhamento->atendimento;
        $encaminhamento->delete();

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Encaminhamento excluido com sucesso.');
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

    public function editRelatorioTecnico(Request $request, RelatorioTecnicoPsicossocial $relatorio)
    {
        $relatorio = $this->psicossocialService->carregarRelatorioTecnico($request->user(), $relatorio);
        $this->authorize('emitirRelatorio', $relatorio->atendimento);

        return view('psicologia-psicopedagogia.relatorios-tecnicos.edit', [
            'relatorio' => $relatorio,
            'atendimento' => $relatorio->atendimento,
            'tituloPagina' => 'Editar relatorio tecnico',
            'subtituloPagina' => 'Ajuste o conteudo do relatorio salvo sem alterar o codigo original de emissao.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Relatorios tecnicos', 'url' => route('psicologia.relatorios_tecnicos.index')],
                ['label' => 'Editar'],
            ]),
        ]);
    }

    public function updateRelatorioTecnico(StoreRelatorioTecnicoPsicossocialRequest $request, RelatorioTecnicoPsicossocial $relatorio)
    {
        $relatorio = $this->psicossocialService->carregarRelatorioTecnico($request->user(), $relatorio);
        $this->authorize('emitirRelatorio', $relatorio->atendimento);

        $relatorio = $this->psicossocialService->atualizarRelatorio($request->user(), $relatorio, $request->validated());

        return redirect()->route('psicologia.relatorios_tecnicos.show', $relatorio)
            ->with('success', 'Relatorio tecnico atualizado com sucesso.');
    }

    public function destroyRelatorioTecnico(Request $request, RelatorioTecnicoPsicossocial $relatorio)
    {
        $relatorio = $this->psicossocialService->carregarRelatorioTecnico($request->user(), $relatorio);
        $this->authorize('emitirRelatorio', $relatorio->atendimento);

        $this->psicossocialService->excluirRelatorio($request->user(), $relatorio);

        return redirect()->route('psicologia.relatorios_tecnicos.index')
            ->with('success', 'Relatorio tecnico excluido com sucesso.');
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

    public function demandas(Request $request)
    {
        return view('psicologia-psicopedagogia.demandas.index', [
            'demandas' => $this->psicossocialService->listarDemandasa($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
            'tituloPagina' => 'Demandas',
            'subtituloPagina' => 'Entrada formal de demandas para atendimento psicologico e psicopedagogico.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Demandas'],
            ]),
        ]);
    }

    public function criarDemanda(Request $request)
    {
        return view('psicologia-psicopedagogia.demandas.create', [
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'tituloPagina' => 'Nova demanda',
            'subtituloPagina' => 'Cadastro de nova demanda de atendimento.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Demandas', 'url' => route('psicologia.demandas.index')],
                ['label' => 'Nova demanda'],
            ]),
        ]);
    }

    public function dadosEscola(Request $request, int $escolaId)
    {
        try {
            return response()->json(
                $this->psicossocialService->dadosEscola($request->user(), $escolaId)
            );
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            return response()->json([
                'error' => $e->getMessage() ?: 'Nao foi possivel acessar os dados da escola.',
            ], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function salvarDemanda(Request $request)
    {
        $validated = $request->validate([
            'escola_id' => 'required|exists:escolas,id',
            'origem_demanda' => 'required|in:coordenacao,direcao,professor,familia,triagem_interna,demanda_espontanea,outro',
            'tipo_publico' => 'required|in:aluno,professor,funcionario,responsavel,coletivo',
            'aluno_id' => 'nullable|exists:alunos,id',
            'funcionario_id' => 'nullable|exists:funcionarios,id',
            'responsavel_nome' => 'nullable|string|max:255',
            'responsavel_telefone' => 'nullable|string|max:20',
            'responsavel_vinculo' => 'nullable|string|max:100',
            'tipo_atendimento' => 'nullable|in:psicologia,psicopedagogia,psicossocial',
            'motivo_inicial' => 'required|string',
            'prioridade' => 'nullable|in:baixa,media,alta,urgente',
            'data_solicitacao' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        $demanda = $this->psicossocialService->criarDemanda($request->user(), $validated);

        return redirect()->route('psicologia.demandas.show', $demanda)
            ->with('success', 'Demanda registrada com sucesso.');
    }

    public function verDemanda(Request $request, DemandaPsicossocial $demanda)
    {
        return view('psicologia-psicopedagogia.demandas.show', [
            'demanda' => $this->psicossocialService->carregarDemanda($request->user(), $demanda),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'tituloPagina' => 'Demanda: ' . $demanda->nome_atendido,
            'subtituloPagina' => 'Detalhes da demanda e opcoes de triagem.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Demandas', 'url' => route('psicologia.demandas.index')],
                ['label' => 'Detalhe'],
            ]),
        ]);
    }

    public function salvarTriagem(Request $request, DemandaPsicossocial $demanda)
    {
        $validated = $request->validate([
            'resumo_caso' => 'nullable|string',
            'sinais_observados' => 'nullable|string',
            'historico_breve' => 'nullable|string',
            'urgencia' => 'nullable|in:baixa,media,alta,critica',
            'risco_identificado' => 'nullable|boolean',
            'descricao_risco' => 'nullable|string',
            'nivel_sigilo' => 'nullable|in:normal,reforcado',
            'decisao' => 'required|in:iniciar_atendimento,observar,encaminhar_externo,devolver_pedagogico,encerrar_sem_atendimento',
            'justificativa_decisao' => 'nullable|string',
            'profissional_responsavel_id' => 'nullable|exists:funcionarios,id',
            'data_triagem' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        $this->psicossocialService->criarTriagem($request->user(), $demanda, $validated);
        $atendimento = $this->psicossocialService->finalizarTriagem($request->user(), $demanda, $validated);

        if ($atendimento) {
            $rotaDestino = $atendimento->visivelParaUsuario($request->user())
                ? route('psicologia.show', $atendimento)
                : route('psicologia.demandas.show', $demanda);

            return redirect()->to($rotaDestino)
                ->with('success', 'Triagem concluida. Atendimento criado com sucesso.');
        }

        return redirect()->route('psicologia.demandas.show', $demanda)
            ->with('success', 'Triagem concluida.');
    }

    public function registrarSessao(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        $validated = $request->validate([
            'data_sessao' => 'required|date',
            'hora_inicio' => 'nullable',
            'hora_fim' => 'nullable',
            'tipo_sessao' => 'required|in:avaliacao,intervencao,retorno,emergencial,acolhimento,devolutiva,reavaliacao',
            'objetivo_sessao' => 'nullable|string',
            'relato_sessao' => 'nullable|string',
            'estrategias_utilizadas' => 'nullable|string',
            'comportamento_observado' => 'nullable|string',
            'evolucao_percebida' => 'nullable|string',
            'encaminhamentos_definidos' => 'nullable|string',
            'necessita_retorno' => 'nullable|boolean',
            'proximo_passo' => 'nullable|string',
            'status' => 'nullable|in:realizado,remarcado,faltou,cancelado',
            'observacoes' => 'nullable|string',
        ]);

        $this->psicossocialService->criarSessao($request->user(), $atendimento, $validated);

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Sessao registrada com sucesso.');
    }

    public function relatorioSessoes(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        $atendimento->load(['escola', 'profissionalResponsavel', 'atendivel', 'sessoes' => function ($q) {
            $q->orderByDesc('data_sessao');
        }, 'devolutivas', 'reavaliacoes', 'encaminhamentos', 'planosIntervencao']);

        $instituicao = \App\Models\Instituicao::query()->first();

        return view('psicologia-psicopedagogia.relatorios.sessoes', [
            'atendimento' => $atendimento,
            'instituicao' => $instituicao,
            'tituloPagina' => 'Relatorio de sessoes',
            'subtituloPagina' => 'Resumo imprimivel das sessoes e procedimentos realizados.',
        ]);
    }

    public function salvarDevolutiva(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        $validated = $request->validate([
            'destinatario' => 'required|in:familia,professor,coordenacao,direcao,funcionario,outro',
            'nome_destinatario' => 'nullable|string|max:255',
            'data_devolutiva' => 'required|date',
            'resumo_devolutiva' => 'nullable|string',
            'orientacoes' => 'nullable|string',
            'encaminhamentos_combinados' => 'nullable|string',
            'necessita_acompanhamento' => 'nullable|boolean',
            'observacoes' => 'nullable|string',
        ]);

        $this->psicossocialService->criarDevolutiva($request->user(), $atendimento, $validated);

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Devolutiva registrada com sucesso.');
    }

    public function editDevolutiva(DevolutivaPsicossocial $devolutiva)
    {
        $this->authorize('view', $devolutiva->atendimento);

        $atendimento = $devolutiva->atendimento;

        return view('psicologia-psicopedagogia.devolutivas.editar', [
            'devolutiva' => $devolutiva,
            'atendimento' => $atendimento,
            'tituloPagina' => 'Editar Devolutiva',
            'subtituloPagina' => 'Atualize os dados da devolutiva.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Atendimentos', 'url' => route('psicologia.atendimentos.index')],
                ['label' => $atendimento->nome_atendido, 'url' => route('psicologia.show', $atendimento)],
                ['label' => 'Editar Devolutiva'],
            ]),
        ]);
    }

    public function updateDevolutiva(Request $request, DevolutivaPsicossocial $devolutiva)
    {
        $this->authorize('view', $devolutiva->atendimento);

        $validated = $request->validate([
            'destinatario' => 'required|in:familia,professor,coordenacao,direcao,funcionario,outro',
            'nome_destinatario' => 'nullable|string|max:255',
            'data_devolutiva' => 'required|date',
            'resumo_devolutiva' => 'nullable|string',
            'orientacoes' => 'nullable|string',
            'encaminhamentos_combinados' => 'nullable|string',
            'necessita_acompanhamento' => 'nullable|boolean',
            'observacoes' => 'nullable|string',
        ]);

        $devolutiva->update($validated);

        return redirect()->route('psicologia.show', $devolutiva->atendimento)
            ->with('success', 'Devolutiva atualizada com sucesso.');
    }

    public function destroyDevolutiva(DevolutivaPsicossocial $devolutiva)
    {
        $this->authorize('view', $devolutiva->atendimento);

        $atendimento = $devolutiva->atendimento;
        $devolutiva->delete();

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Devolutiva excluida com sucesso.');
    }

    public function salvarReavaliacao(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        $validated = $request->validate([
            'data_reavaliacao' => 'required|date',
            'progresso_observado' => 'nullable|string',
            'dificuldades_persistentes' => 'nullable|string',
            'ajuste_plano' => 'nullable|string',
            'frequencia_nova' => 'nullable|in:semanal,quinzenal,mensal,outra',
            'decisao' => 'required|in:manter_plano,ajustar_plano,suspender,encaminhar,encerrar',
            'justificativa' => 'nullable|string',
            'proxima_reavaliacao' => 'nullable|date',
        ]);

        $this->psicossocialService->criarReavaliacao($request->user(), $atendimento, $validated);

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Reavaliacao registrada com sucesso.');
    }

    public function editReavaliacao(ReavaliacaoPsicossocial $reavaliacao)
    {
        $this->authorize('view', $reavaliacao->atendimento);

        $atendimento = $reavaliacao->atendimento;

        return view('psicologia-psicopedagogia.reavaliacoes.editar', [
            'reavaliacao' => $reavaliacao,
            'atendimento' => $atendimento,
            'tituloPagina' => 'Editar Reavaliacao',
            'subtituloPagina' => 'Atualize os dados da reavaliacao.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Atendimentos', 'url' => route('psicologia.atendimentos.index')],
                ['label' => $atendimento->nome_atendido, 'url' => route('psicologia.show', $atendimento)],
                ['label' => 'Editar Reavaliacao'],
            ]),
        ]);
    }

    public function updateReavaliacao(Request $request, ReavaliacaoPsicossocial $reavaliacao)
    {
        $this->authorize('view', $reavaliacao->atendimento);

        $validated = $request->validate([
            'data_reavaliacao' => 'required|date',
            'progresso_observado' => 'nullable|string',
            'dificuldades_persistentes' => 'nullable|string',
            'ajuste_plano' => 'nullable|string',
            'frequencia_nova' => 'nullable|in:semanal,quinzenal,mensal,outra',
            'decisao' => 'required|in:manter_plano,ajustar_plano,suspender,encaminhar,encerrar',
            'justificativa' => 'nullable|string',
            'proxima_reavaliacao' => 'nullable|date',
        ]);

        $reavaliacao->update($validated);

        return redirect()->route('psicologia.show', $reavaliacao->atendimento)
            ->with('success', 'Reavaliacao atualizada com sucesso.');
    }

    public function destroyReavaliacao(ReavaliacaoPsicossocial $reavaliacao)
    {
        $this->authorize('view', $reavaliacao->atendimento);

        $atendimento = $reavaliacao->atendimento;
        $reavaliacao->delete();

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Reavaliacao excluida com sucesso.');
    }

    public function encerrarAtendimento(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        $validated = $request->validate([
            'data_encerramento' => 'nullable|date',
            'motivo_encerramento' => 'nullable|string',
            'resumo_encerramento' => 'nullable|string',
            'orientacoes_finais' => 'nullable|string',
        ]);

        $this->psicossocialService->encerrarAtendimento($request->user(), $atendimento, $validated);

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Atendimento encerrado com sucesso.');
    }

    public function reabrirAtendimento(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        $atendimento->update([
            'status' => 'em_acompanhamento',
            'data_encerramento' => null,
            'motivo_encerramento' => null,
            'resumo_encerramento' => null,
            'orientacoes_finais' => null,
        ]);

        return redirect()->route('psicologia.show', $atendimento)
            ->with('success', 'Atendimento reaberto com sucesso.');
    }
}
