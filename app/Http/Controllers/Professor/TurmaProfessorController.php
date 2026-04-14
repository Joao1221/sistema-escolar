<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Services\PortalProfessorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TurmaProfessorController extends Controller
{
    public function __construct(
        private readonly PortalProfessorService $portalProfessorService
    ) {}

    public function index(Request $request): View
    {
        return view('professor.turmas.index', [
            'turmas' => $this->portalProfessorService->listarMinhasTurmas($request->user()),
            'tituloPagina' => 'Minhas Turmas',
            'subtituloPagina' => 'Acompanhamento das turmas e disciplinas vinculadas ao professor.',
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Minhas Turmas'],
            ]),
        ]);
    }
}
