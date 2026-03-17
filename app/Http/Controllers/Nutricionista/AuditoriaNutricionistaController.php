<?php

namespace App\Http\Controllers\Nutricionista;

use App\Http\Controllers\Controller;
use App\Services\AuditoriaService;
use App\Services\PortalNutricionistaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaNutricionistaController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService,
        private readonly PortalNutricionistaService $portalNutricionistaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar auditoria da alimentacao escolar'),
        ];
    }

    public function index(Request $request)
    {
        return view('nutricionista.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('nutricionista'),
            'registros' => $this->auditoriaService->listarRegistros('nutricionista', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('nutricionista', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('nutricionista', $request->user()),
            'filtros' => $request->all(),
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Auditoria'],
            ]),
        ]);
    }
}
