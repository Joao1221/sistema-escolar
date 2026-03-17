<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEscolaRequest;
use App\Http\Requests\UpdateEscolaRequest;
use App\Models\Escola;
use App\Services\EscolaService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EscolaController extends Controller
{
    use AuthorizesRequests;

    protected $escolaService;

    public function __construct(EscolaService $escolaService)
    {
        $this->escolaService = $escolaService;
    }

    public function index(Request $request)
    {
        $this->authorize('visualizar escolas');

        $filtros = $request->only(['nome', 'status']);
        $escolas = $this->escolaService->listarEscolas($filtros);

        return view('escolas.index', compact('escolas'));
    }

    public function create()
    {
        $this->authorize('criar escola');
        return view('escolas.create');
    }

    public function store(StoreEscolaRequest $request)
    {
        $this->escolaService->criarEscola($request->validated());

        return redirect()->route('secretaria.escolas.index')->with('success', 'Escola cadastrada com sucesso!');
    }

    public function show(Escola $escola)
    {
        $this->authorize('visualizar escolas');
        return view('escolas.show', compact('escola'));
    }

    public function edit(Escola $escola)
    {
        $this->authorize('editar escola');
        return view('escolas.edit', compact('escola'));
    }

    public function update(UpdateEscolaRequest $request, Escola $escola)
    {
        $this->escolaService->atualizarEscola($escola, $request->validated());

        return redirect()->route('secretaria.escolas.index')->with('success', 'Escola atualizada com sucesso!');
    }

    public function toggleStatus(Escola $escola)
    {
        $this->authorize('ativar inativar escola');
        $this->escolaService->alternarStatus($escola);

        return redirect()->back()->with('success', 'Status da escola alterado com sucesso!');
    }
}
