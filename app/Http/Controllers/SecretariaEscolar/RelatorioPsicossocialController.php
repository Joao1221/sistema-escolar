<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\GerarRelatorioRequest;
use App\Services\RelatorioPortalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RelatorioPsicossocialController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly RelatorioPortalService $relatorioPortalService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar relatorios tecnicos do psicossocial'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.psicossocial.relatorios.index', [
            'relatorios' => $this->relatorioPortalService->relatoriosDisponiveis('psicossocial', $request->user()),
            'opcoesFormulario' => $this->relatorioPortalService->opcoesFormulario('psicossocial', $request->user()),
        ]);
    }

    public function preview(GerarRelatorioRequest $request, string $tipo)
    {
        return view('secretaria-escolar.psicossocial.relatorios.preview', [
            'relatorio' => $this->relatorioPortalService->gerar('psicossocial', $tipo, $request->user(), $request->validated()),
            'tipoRelatorio' => $tipo,
            'payload' => $request->validated(),
        ]);
    }

    public function print(GerarRelatorioRequest $request, string $tipo)
    {
        return view('relatorios.impressao', [
            'relatorio' => $this->relatorioPortalService->gerar('psicossocial', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
