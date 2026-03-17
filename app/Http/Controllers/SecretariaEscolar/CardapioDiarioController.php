<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCardapioDiarioRequest;
use App\Http\Requests\UpdateCardapioDiarioRequest;
use App\Models\CardapioDiario;
use App\Services\AlimentacaoEscolarService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CardapioDiarioController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AlimentacaoEscolarService $service
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar alimentacao escolar', only: ['index', 'show', 'create', 'edit']),
            new Middleware('can:lancar cardapio diario', only: ['store', 'update']),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.alimentacao.cardapios.index', [
            'cardapios' => $this->service->listarCardapios($request->user(), $request->all()),
            'filtros' => $request->all(),
            ...$this->service->opcoesFormulario($request->user()),
        ]);
    }

    public function create(Request $request)
    {
        return view('secretaria-escolar.alimentacao.cardapios.create', [
            ...$this->service->opcoesFormulario($request->user()),
        ]);
    }

    public function store(StoreCardapioDiarioRequest $request)
    {
        $cardapio = $this->service->criarCardapio($request->user(), $request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.cardapios.show', $cardapio)
            ->with('success', 'Cardapio diario lancado com sucesso.');
    }

    public function show(Request $request, CardapioDiario $cardapio)
    {
        $this->service->garantirEscolaPermitida($request->user(), $cardapio->escola_id);

        return view('secretaria-escolar.alimentacao.cardapios.show', [
            'cardapio' => $cardapio->load(['escola', 'usuario', 'itens.alimento']),
        ]);
    }

    public function edit(Request $request, CardapioDiario $cardapio)
    {
        $this->service->garantirEscolaPermitida($request->user(), $cardapio->escola_id);

        return view('secretaria-escolar.alimentacao.cardapios.edit', [
            'cardapio' => $cardapio->load('itens'),
            ...$this->service->opcoesFormulario($request->user()),
        ]);
    }

    public function update(UpdateCardapioDiarioRequest $request, CardapioDiario $cardapio)
    {
        $this->service->atualizarCardapio($request->user(), $cardapio, $request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.cardapios.show', $cardapio)
            ->with('success', 'Cardapio diario atualizado com sucesso.');
    }
}
