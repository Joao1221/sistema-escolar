<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaEscolarController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar auditoria escolar'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('secretaria-escolar'),
            'registros' => $this->auditoriaService->listarRegistros('secretaria-escolar', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('secretaria-escolar', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('secretaria-escolar', $request->user()),
            'filtros' => $request->all(),
        ]);
    }
}
