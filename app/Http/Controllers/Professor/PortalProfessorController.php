<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Services\PortalProfessorService;
use Illuminate\Http\Request;

class PortalProfessorController extends Controller
{
    public function __construct(
        private readonly PortalProfessorService $portalProfessorService
    ) {
    }

    public function dashboard(Request $request)
    {
        $dados = $this->portalProfessorService->obterDadosDashboard($request->user());

        return view('professor.dashboard', [
            ...$dados,
            'tituloPagina' => 'Dashboard',
            'subtituloPagina' => 'Visao geral das suas turmas, registros e proximas acoes.',
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Dashboard'],
            ]),
        ]);
    }
}
