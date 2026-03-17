<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovimentacaoAlimentoRequest;
use App\Services\AlimentacaoEscolarService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MovimentacaoAlimentoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AlimentacaoEscolarService $service
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar movimentacoes de alimentos', only: ['index']),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.alimentacao.movimentacoes.index', [
            'movimentacoes' => $this->service->listarMovimentacoes($request->user(), $request->all()),
            'filtros' => $request->all(),
            ...$this->service->opcoesFormulario($request->user()),
        ]);
    }

    public function create(Request $request)
    {
        if (
            ! $request->user()->can('registrar entrada de alimentos') &&
            ! $request->user()->can('registrar saida de alimentos')
        ) {
            abort(403);
        }

        return view('secretaria-escolar.alimentacao.movimentacoes.create', [
            'tipoPadrao' => $request->get('tipo', 'entrada'),
            ...$this->service->opcoesFormulario($request->user()),
        ]);
    }

    public function store(StoreMovimentacaoAlimentoRequest $request)
    {
        $this->service->registrarMovimentacao($request->user(), $request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.movimentacoes.index')
            ->with('success', 'Movimentacao registrada com sucesso.');
    }
}
