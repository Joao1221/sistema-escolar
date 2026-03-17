<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlimentoRequest;
use App\Http\Requests\UpdateAlimentoRequest;
use App\Models\Alimento;
use App\Services\AlimentacaoEscolarService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AlimentoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AlimentacaoEscolarService $service
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar alimentacao escolar', only: ['index', 'create', 'edit']),
            new Middleware('can:cadastrar alimentos', only: ['store']),
            new Middleware('can:editar alimentos', only: ['update']),
        ];
    }

    public function index()
    {
        return view('secretaria-escolar.alimentacao.alimentos.index', [
            'alimentos' => $this->service->listarAlimentos(),
        ]);
    }

    public function create()
    {
        return view('secretaria-escolar.alimentacao.alimentos.create', [
            'categorias' => $this->service->listarCategorias(),
        ]);
    }

    public function store(StoreAlimentoRequest $request)
    {
        $this->service->salvarAlimento($request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.alimentos.index')
            ->with('success', 'Alimento cadastrado com sucesso.');
    }

    public function edit(Alimento $alimento)
    {
        return view('secretaria-escolar.alimentacao.alimentos.edit', [
            'alimento' => $alimento,
            'categorias' => $this->service->listarCategorias(),
        ]);
    }

    public function update(UpdateAlimentoRequest $request, Alimento $alimento)
    {
        $this->service->atualizarAlimento($alimento, $request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.alimentos.index')
            ->with('success', 'Alimento atualizado com sucesso.');
    }
}
