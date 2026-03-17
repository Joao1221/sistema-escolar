<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Services\AlunoService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AlunoConsultaController extends Controller
{
    use AuthorizesRequests;

    protected $alunoService;

    public function __construct(AlunoService $alunoService)
    {
        $this->alunoService = $alunoService;
    }

    /**
     * Listagem de alunos para consulta (Portal de Educação).
     */
    public function index(Request $request)
    {
        $this->authorize('visualizar alunos');
        
        $filtros = $request->only(['nome', 'rgm', 'status']);
        $alunos = $this->alunoService->listarAlunos($filtros);

        return view('secretaria.alunos.index', compact('alunos'));
    }

    /**
     * Detalhes do aluno (Somente Leitura).
     */
    public function show(Aluno $aluno)
    {
        $this->authorize('detalhar aluno');
        return view('secretaria.alunos.show', compact('aluno'));
    }
}
