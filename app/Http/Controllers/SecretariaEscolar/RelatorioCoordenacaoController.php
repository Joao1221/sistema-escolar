<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\GerarRelatorioRequest;
use App\Services\RelatorioPortalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RelatorioCoordenacaoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly RelatorioPortalService $relatorioPortalService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar relatorios pedagogicos'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.coordenacao.relatorios.index', [
            'relatorios' => $this->relatorioPortalService->relatoriosDisponiveis('coordenacao', $request->user()),
            'opcoesFormulario' => $this->relatorioPortalService->opcoesFormulario('coordenacao', $request->user()),
        ]);
    }

    public function preview(GerarRelatorioRequest $request, string $tipo)
    {
        return view('secretaria-escolar.coordenacao.relatorios.preview', [
            'relatorio' => $this->relatorioPortalService->gerar('coordenacao', $tipo, $request->user(), $request->validated()),
            'tipoRelatorio' => $tipo,
            'payload' => $request->validated(),
        ]);
    }

    public function print(GerarRelatorioRequest $request, string $tipo)
    {
        return view('relatorios.impressao', [
            'relatorio' => $this->relatorioPortalService->gerar('coordenacao', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
