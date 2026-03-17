<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAtendimentoPsicossocialRequest;
use App\Http\Requests\StoreCasoDisciplinarSigilosoRequest;
use App\Http\Requests\StoreEncaminhamentoPsicossocialRequest;
use App\Http\Requests\StorePlanoIntervencaoPsicossocialRequest;
use App\Http\Requests\StoreRelatorioTecnicoPsicossocialRequest;
use App\Models\AtendimentoPsicossocial;
use App\Services\PsicossocialService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PsicossocialController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly PsicossocialService $psicossocialService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:acessar modulo psicossocial'),
            new Middleware('can:consultar agenda psicossocial', only: ['index', 'agenda']),
            new Middleware('can:consultar historico psicossocial', only: ['historico', 'show', 'relatorios']),
            new Middleware('can:registrar atendimentos psicossociais', only: ['create', 'store']),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.psicossocial.index', [
            ...$this->psicossocialService->obterPainel($request->user()),
        ]);
    }

    public function agenda(Request $request)
    {
        return view('secretaria-escolar.psicossocial.agenda', [
            'atendimentos' => $this->psicossocialService->listarAgenda($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
        ]);
    }

    public function historico(Request $request)
    {
        return view('secretaria-escolar.psicossocial.historico', [
            'atendimentos' => $this->psicossocialService->listarHistorico($request->user(), $request->all()),
            ...$this->psicossocialService->opcoesFormulario($request->user()),
            'filtros' => $request->all(),
        ]);
    }

    public function relatorios(Request $request)
    {
        return view('secretaria-escolar.psicossocial.relatorios', [
            'relatorios' => $this->psicossocialService->listarRelatorios($request->user()),
        ]);
    }

    public function create(Request $request)
    {
        return view('secretaria-escolar.psicossocial.create', [
            ...$this->psicossocialService->opcoesFormulario($request->user()),
        ]);
    }

    public function store(StoreAtendimentoPsicossocialRequest $request)
    {
        $atendimento = $this->psicossocialService->criarAtendimento($request->user(), $request->validated());

        return redirect()->route('secretaria-escolar.psicossocial.show', $atendimento)
            ->with('success', 'Atendimento psicossocial registrado com sucesso.');
    }

    public function show(Request $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('view', $atendimento);

        return view('secretaria-escolar.psicossocial.show', [
            'atendimento' => $this->psicossocialService->carregarAtendimento($request->user(), $atendimento),
        ]);
    }

    public function storePlano(StorePlanoIntervencaoPsicossocialRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('createPlano', $atendimento);
        $this->psicossocialService->criarPlano($request->user(), $atendimento, $request->validated());

        return redirect()->route('secretaria-escolar.psicossocial.show', $atendimento)
            ->with('success', 'Plano de intervencao registrado com sucesso.');
    }

    public function storeEncaminhamento(StoreEncaminhamentoPsicossocialRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('createEncaminhamento', $atendimento);
        $this->psicossocialService->criarEncaminhamento($request->user(), $atendimento, $request->validated());

        return redirect()->route('secretaria-escolar.psicossocial.show', $atendimento)
            ->with('success', 'Encaminhamento registrado com sucesso.');
    }

    public function storeCaso(StoreCasoDisciplinarSigilosoRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('createCaso', $atendimento);
        $this->psicossocialService->criarCasoDisciplinar($request->user(), $atendimento, $request->validated());

        return redirect()->route('secretaria-escolar.psicossocial.show', $atendimento)
            ->with('success', 'Caso disciplinar sigiloso registrado com sucesso.');
    }

    public function storeRelatorio(StoreRelatorioTecnicoPsicossocialRequest $request, AtendimentoPsicossocial $atendimento)
    {
        $this->authorize('emitirRelatorio', $atendimento);
        $this->psicossocialService->criarRelatorio($request->user(), $atendimento, $request->validated());

        return redirect()->route('secretaria-escolar.psicossocial.show', $atendimento)
            ->with('success', 'Relatorio tecnico emitido com sucesso.');
    }
}
