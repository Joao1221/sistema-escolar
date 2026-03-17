<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\GerarRelatorioRequest;
use App\Services\RelatorioPortalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RelatorioEscolarController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly RelatorioPortalService $relatorioPortalService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar relatorios escolares'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.relatorios.index', [
            'relatorios' => $this->relatorioPortalService->relatoriosDisponiveis('secretaria-escolar', $request->user()),
            'opcoesFormulario' => $this->relatorioPortalService->opcoesFormulario('secretaria-escolar', $request->user()),
        ]);
    }

    public function preview(GerarRelatorioRequest $request, string $tipo)
    {
        return view('secretaria-escolar.relatorios.preview', [
            'relatorio' => $this->relatorioPortalService->gerar('secretaria-escolar', $tipo, $request->user(), $request->validated()),
            'tipoRelatorio' => $tipo,
            'payload' => $request->validated(),
        ]);
    }

    public function print(GerarRelatorioRequest $request, string $tipo)
    {
        return view('relatorios.impressao', [
            'relatorio' => $this->relatorioPortalService->gerar('secretaria-escolar', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
