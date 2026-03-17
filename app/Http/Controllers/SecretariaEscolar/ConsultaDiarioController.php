<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Models\DiarioProfessor;
use App\Services\DiarioProfessorService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ConsultaDiarioController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly DiarioProfessorService $diarioProfessorService
    ) {
    }

    public function index(Request $request)
    {
        abort_unless($request->user()->can('consultar diarios'), 403);

        return view('secretaria-escolar.diarios.index', [
            'diarios' => $this->diarioProfessorService->listarDiariosParaUsuario(
                $request->user(),
                $request->only(['escola_id', 'turma_id', 'disciplina_id', 'professor_id', 'ano_letivo', 'periodo_tipo', 'periodo_referencia'])
            ),
        ]);
    }

    public function show(Request $request, DiarioProfessor $diario)
    {
        abort_unless($request->user()->can('consultar diarios'), 403);
        $this->authorize('consultarPedagogico', $diario);

        return view('secretaria-escolar.diarios.show', [
            'diario' => $this->diarioProfessorService->obterDiarioDetalhado($diario),
            'matriculasAtivas' => $this->diarioProfessorService->listarMatriculasAtivas($diario),
        ]);
    }
}
