<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Services\MatriculaService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MatriculaConsultaController extends Controller
{
    use AuthorizesRequests;

    protected $matriculaService;

    public function __construct(MatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    /**
     * Listagem consolidada de matrículas da rede.
     */
    public function index(Request $request)
    {
        $this->authorize('consultar matrículas');
        
        $filtros = $request->only(['aluno_nome', 'status', 'tipo', 'ano_letivo', 'escola_id']);
        $matriculas = $this->matriculaService->listarMatriculas($filtros);

        return view('secretaria.matriculas.index', compact('matriculas'));
    }

    /**
     * Detalhes e Histórico (Somente Leitura).
     */
    public function show(Matricula $matricula)
    {
        $this->authorize('visualizar detalhes da matrícula');
        $matricula->load(['aluno', 'escola', 'turma', 'historico.usuario']);

        return view('secretaria.matriculas.show', compact('matricula'));
    }
}
