<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use App\Models\Escola;
use App\Models\ModalidadeEnsino;
use App\Services\TurmaService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TurmaConsultaController extends Controller
{
    use AuthorizesRequests;

    protected $turmaService;

    public function __construct(TurmaService $turmaService)
    {
        $this->turmaService = $turmaService;
    }

    /**
     * Listagem de turmas para consulta na Secretaria de Educação.
     */
    public function index(Request $request)
    {
        $this->authorize('consultar turmas');
        
        $filtros = $request->only(['nome', 'escola_id', 'modalidade_id', 'ano_letivo', 'ativa']);
        $turmas = $this->turmaService->listarTurmas($filtros);
        
        $escolas = Escola::where('ativo', true)->orderBy('nome')->get();
        $modalidades = ModalidadeEnsino::where('ativo', true)->orderBy('nome')->get();

        return view('secretaria.turmas.index', compact('turmas', 'escolas', 'modalidades'));
    }

    /**
     * Detalhes da turma na Secretaria de Educação.
     */
    public function show(Turma $turma)
    {
        $this->authorize('detalhar turma');
        return view('secretaria.turmas.show', compact('turma'));
    }
}
