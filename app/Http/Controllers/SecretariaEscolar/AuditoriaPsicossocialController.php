<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaPsicossocialController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar auditoria psicossocial sigilosa'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.psicossocial.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('psicossocial'),
            'registros' => $this->auditoriaService->listarRegistros('psicossocial', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('psicossocial', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('psicossocial', $request->user()),
            'filtros' => $request->all(),
        ]);
    }
}
