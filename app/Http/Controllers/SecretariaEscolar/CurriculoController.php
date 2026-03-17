<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Models\MatrizCurricular;
use App\Models\ModalidadeEnsino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurriculoController extends Controller
{
    public function index(Request $request)
    {
        $escolaId = Auth::user()->escola_id;

        // Consulta matrizes que são da rede (escola_id nulo) OR da escola do usuário
        $query = MatrizCurricular::with(['modalidade', 'escola'])
            ->where(fn($q) => $q->whereNull('escola_id')->orWhere('escola_id', $escolaId))
            ->where('ativa', true);

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('modalidade_id')) {
            $query->where('modalidade_id', $request->modalidade_id);
        }

        $matrizes = $query->orderByDesc('ano_vigencia')->paginate(15);
        $modalidades = ModalidadeEnsino::where('ativo', true)->get();

        return view('secretaria-escolar.curriculo.index', compact('matrizes', 'modalidades'));
    }

    public function show(MatrizCurricular $matriz)
    {
        $escolaId = Auth::user()->escola_id;

        // Verifica se o usuário tem acesso a essa matriz
        if ($matriz->escola_id && $matriz->escola_id !== $escolaId) {
            abort(403, 'Você não tem permissão para visualizar esta matriz.');
        }

        $matriz->load(['modalidade', 'escola', 'disciplinas']);
        
        return view('secretaria-escolar.curriculo.show', compact('matriz'));
    }
}
