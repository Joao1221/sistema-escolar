<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaCoordenacaoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar auditoria pedagogica'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.coordenacao.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('coordenacao'),
            'registros' => $this->auditoriaService->listarRegistros('coordenacao', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('coordenacao', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('coordenacao', $request->user()),
            'filtros' => $request->all(),
        ]);
    }
}
