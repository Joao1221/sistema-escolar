<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaRedeController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar auditoria da rede'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('secretaria'),
            'registros' => $this->auditoriaService->listarRegistros('secretaria', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('secretaria', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('secretaria', $request->user()),
            'filtros' => $request->all(),
        ]);
    }
}
