<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\GerarRelatorioRequest;
use App\Services\RelatorioPortalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RelatorioRedeController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly RelatorioPortalService $relatorioPortalService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar relatorios da rede'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria.relatorios.index', [
            'relatorios' => $this->relatorioPortalService->relatoriosDisponiveis('secretaria', $request->user()),
            'opcoesFormulario' => $this->relatorioPortalService->opcoesFormulario('secretaria', $request->user()),
        ]);
    }

    public function preview(GerarRelatorioRequest $request, string $tipo)
    {
        return view('secretaria.relatorios.preview', [
            'relatorio' => $this->relatorioPortalService->gerar('secretaria', $tipo, $request->user(), $request->validated()),
            'tipoRelatorio' => $tipo,
            'payload' => $request->validated(),
        ]);
    }

    public function print(GerarRelatorioRequest $request, string $tipo)
    {
        return view('relatorios.impressao', [
            'relatorio' => $this->relatorioPortalService->gerar('secretaria', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
