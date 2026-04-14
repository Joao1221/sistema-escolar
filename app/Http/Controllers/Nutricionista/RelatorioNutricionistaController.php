<?php

namespace App\Http\Controllers\Nutricionista;

use App\Http\Controllers\Controller;
use App\Http\Requests\GerarRelatorioRequest;
use App\Services\PortalNutricionistaService;
use App\Services\RelatorioPortalService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RelatorioNutricionistaController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly RelatorioPortalService $relatorioPortalService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar relatorios da nutricionista'),
        ];
    }

    public function index(Request $request): View
    {
        return view('nutricionista.relatorios.index', [
            'relatorios' => $this->relatorioPortalService->relatoriosDisponiveis('nutricionista', $request->user()),
            'opcoesFormulario' => $this->relatorioPortalService->opcoesFormulario('nutricionista', $request->user()),
            'breadcrumbs' => app(PortalNutricionistaService::class)->construirBreadcrumbs([
                ['label' => 'Relatorios'],
            ]),
        ]);
    }

    public function preview(GerarRelatorioRequest $request, string $tipo): View
    {
        return view('nutricionista.relatorios.preview', [
            'relatorio' => $this->relatorioPortalService->gerar('nutricionista', $tipo, $request->user(), $request->validated()),
            'tipoRelatorio' => $tipo,
            'payload' => $request->validated(),
            'breadcrumbs' => app(PortalNutricionistaService::class)->construirBreadcrumbs([
                ['label' => 'Relatorios', 'url' => route('nutricionista.relatorios.index')],
                ['label' => 'Visualizacao'],
            ]),
        ]);
    }

    public function print(GerarRelatorioRequest $request, string $tipo): View
    {
        return view('relatorios.impressao', [
            'relatorio' => $this->relatorioPortalService->gerar('nutricionista', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
