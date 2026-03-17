<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaDirecaoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar auditoria da direcao escolar'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.direcao.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('direcao'),
            'registros' => $this->auditoriaService->listarRegistros('direcao', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('direcao', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('direcao', $request->user()),
            'filtros' => $request->all(),
        ]);
    }
}
