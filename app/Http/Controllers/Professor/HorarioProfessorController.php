<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Services\PortalProfessorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HorarioProfessorController extends Controller
{
    public function __construct(
        private readonly PortalProfessorService $portalProfessorService
    ) {}

    public function index(Request $request): View
    {
        return view('professor.horarios.index', [
            'horariosAgrupados' => $this->portalProfessorService->listarMeuHorario($request->user()),
            'tituloPagina' => 'Meu Horario',
            'subtituloPagina' => 'Grade semanal com suas aulas, turmas e disciplinas.',
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Meu Horario'],
            ]),
        ]);
    }
}
