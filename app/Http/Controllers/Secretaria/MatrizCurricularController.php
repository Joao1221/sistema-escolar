<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\MatrizCurricular;
use App\Models\Disciplina;
use App\Models\ModalidadeEnsino;
use App\Models\Escola;
use App\Http\Requests\StoreMatrizRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatrizCurricularController extends Controller
{
    public function index(Request $request)
    {
        $query = MatrizCurricular::with(['modalidade', 'escola']);

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('modalidade_id')) {
            $query->where('modalidade_id', $request->modalidade_id);
        }

        $matrizes = $query->orderByDesc('ano_vigencia')->paginate(15);
        $modalidades = ModalidadeEnsino::where('ativo', true)->get();

        return view('secretaria.matrizes.index', compact('matrizes', 'modalidades'));
    }

    public function create()
    {
        $modalidades = ModalidadeEnsino::where('ativo', true)->get();
        $escolas = Escola::where('ativo', true)->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();

        return view('secretaria.matrizes.create', compact('modalidades', 'escolas', 'disciplinas'));
    }

    public function store(StoreMatrizRequest $request)
    {
        DB::transaction(function () use ($request) {
            $matriz = MatrizCurricular::create($request->only([
                'nome', 'modalidade_id', 'serie_etapa', 'escola_id', 'ano_vigencia', 'ativa'
            ]));

            foreach ($request->disciplinas as $item) {
                $matriz->disciplinas()->attach($item['id'], [
                    'carga_horaria' => $item['carga_horaria'],
                    'obrigatoria' => $item['obrigatoria'] ?? true,
                ]);
            }
        });

        return redirect()->route('secretaria.matrizes.index')
            ->with('success', 'Matriz Curricular criada com sucesso!');
    }

    public function show(MatrizCurricular $matriz)
    {
        $matriz->load(['modalidade', 'escola', 'disciplinas']);
        return view('secretaria.matrizes.show', compact('matriz'));
    }

    public function edit(MatrizCurricular $matriz)
    {
        $matriz->load('disciplinas');
        $modalidades = ModalidadeEnsino::where('ativo', true)->get();
        $escolas = Escola::where('ativo', true)->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();

        return view('secretaria.matrizes.edit', compact('matriz', 'modalidades', 'escolas', 'disciplinas'));
    }

    public function update(StoreMatrizRequest $request, MatrizCurricular $matriz)
    {
        DB::transaction(function () use ($request, $matriz) {
            $matriz->update($request->only([
                'nome', 'modalidade_id', 'serie_etapa', 'escola_id', 'ano_vigencia', 'ativa'
            ]));

            $syncData = [];
            foreach ($request->disciplinas as $item) {
                $syncData[$item['id']] = [
                    'carga_horaria' => $item['carga_horaria'],
                    'obrigatoria' => $item['obrigatoria'] ?? true,
                ];
            }
            $matriz->disciplinas()->sync($syncData);
        });

        return redirect()->route('secretaria.matrizes.index')
            ->with('success', 'Matriz Curricular atualizada com sucesso!');
    }

    /**
     * Relatório: Panorama de Uso das Matrizes na Rede
     */
    public function panorama()
    {
        // Busca todas as turmas agrupadas por matriz
        $turmasComMatriz = \App\Models\Turma::with(['escola', 'matriz', 'modalidade'])
            ->whereNotNull('matriz_id')
            ->orderBy('escola_id')
            ->get()
            ->groupBy('matriz_id');

        // Busca turmas sem matriz associada
        $turmasSemMatriz = \App\Models\Turma::with(['escola', 'modalidade'])
            ->whereNull('matriz_id')
            ->where('ativa', true)
            ->get();

        $totalMatrizes = MatrizCurricular::count();
        $totalTurmas = \App\Models\Turma::count();
        $cobertura = $totalTurmas > 0 ? round((($totalTurmas - $turmasSemMatriz->count()) / $totalTurmas) * 100) : 0;

        return view('secretaria.matrizes.panorama', compact(
            'turmasComMatriz',
            'turmasSemMatriz',
            'totalMatrizes',
            'totalTurmas',
            'cobertura'
        ));
    }
}
