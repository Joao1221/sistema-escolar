<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriaAlimentoRequest;
use App\Http\Requests\UpdateCategoriaAlimentoRequest;
use App\Models\CategoriaAlimento;
use App\Services\AlimentacaoEscolarService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoriaAlimentoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AlimentacaoEscolarService $service
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar alimentacao escolar', only: ['index']),
            new Middleware('can:cadastrar categorias de alimentos', only: ['store']),
            new Middleware('can:editar categorias de alimentos', only: ['update']),
        ];
    }

    public function index(Request $request)
    {
        $categoriaEmEdicao = null;

        if ($request->filled('editar')) {
            $categoriaEmEdicao = CategoriaAlimento::query()->findOrFail($request->integer('editar'));
        }

        return view('secretaria-escolar.alimentacao.categorias.index', [
            'categorias' => $this->service->listarCategorias(),
            'categoriaEmEdicao' => $categoriaEmEdicao,
        ]);
    }

    public function store(StoreCategoriaAlimentoRequest $request)
    {
        $this->service->salvarCategoria($request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.categorias.index')
            ->with('success', 'Categoria de alimento cadastrada com sucesso.');
    }

    public function update(UpdateCategoriaAlimentoRequest $request, CategoriaAlimento $categoria)
    {
        $this->service->atualizarCategoria($categoria, $request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.categorias.index')
            ->with('success', 'Categoria de alimento atualizada com sucesso.');
    }
}
