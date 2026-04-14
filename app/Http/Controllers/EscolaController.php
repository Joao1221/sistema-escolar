<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEscolaRequest;
use App\Http\Requests\UpdateEscolaRequest;
use App\Models\Escola;
use App\Services\EscolaService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EscolaController extends Controller
{
    use AuthorizesRequests;

    protected EscolaService $escolaService;

    public function __construct(EscolaService $escolaService)
    {
        $this->escolaService = $escolaService;
    }

    public function index(Request $request): View
    {
        $this->authorize('visualizar escolas');

        $filtros = $request->only(['nome', 'status']);
        $escolas = $this->escolaService->listarEscolas($filtros);

        return view('escolas.index', compact('escolas'));
    }

    public function create(): View
    {
        $this->authorize('criar escola');

        return view('escolas.create');
    }

    public function store(StoreEscolaRequest $request): RedirectResponse
    {
        $this->escolaService->criarEscola($request->validated());

        return redirect()->route('secretaria.escolas.index')->with('success', 'Escola cadastrada com sucesso!');
    }

    public function show(Escola $escola): View
    {
        $this->authorize('visualizar escolas');

        return view('escolas.show', compact('escola'));
    }

    public function edit(Escola $escola): View
    {
        $this->authorize('editar escola');

        return view('escolas.edit', compact('escola'));
    }

    public function update(UpdateEscolaRequest $request, Escola $escola): RedirectResponse
    {
        $this->escolaService->atualizarEscola($escola, $request->validated());

        return redirect()->route('secretaria.escolas.index')->with('success', 'Escola atualizada com sucesso!');
    }

    public function toggleStatus(Escola $escola): RedirectResponse
    {
        $this->authorize('ativar inativar escola');
        $this->escolaService->alternarStatus($escola);

        return redirect()->back()->with('success', 'Status da escola alterado com sucesso!');
    }
}
