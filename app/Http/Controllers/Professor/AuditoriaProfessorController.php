<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditoriaProfessorController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar auditoria do proprio trabalho docente'),
        ];
    }

    public function index(Request $request)
    {
        return view('professor.auditoria.index', [
            'configuracaoPortal' => $this->auditoriaService->configuracaoPortal('professor'),
            'registros' => $this->auditoriaService->listarRegistros('professor', $request->user(), $request->all()),
            'metricas' => $this->auditoriaService->metricas('professor', $request->user(), $request->all()),
            'opcoesFiltros' => $this->auditoriaService->opcoesFiltros('professor', $request->user()),
            'filtros' => $request->all(),
            'breadcrumbs' => [
                ['label' => 'Portal do Professor', 'url' => route('professor.dashboard')],
                ['label' => 'Auditoria'],
            ],
        ]);
    }
}
