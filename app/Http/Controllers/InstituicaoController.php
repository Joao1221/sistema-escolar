<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInstituicaoRequest;
use App\Services\InstituicaoService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class InstituicaoController extends Controller
{
    use AuthorizesRequests;

    protected InstituicaoService $instituicaoService;

    public function __construct(InstituicaoService $instituicaoService)
    {
        $this->instituicaoService = $instituicaoService;
    }

    /**
     * Display the institution data.
     */
    public function show(): View
    {
        $this->authorize('visualizar instituicao');

        $instituicao = $this->instituicaoService->obterInstituicao();

        return view('instituicao.show', compact('instituicao'));
    }

    /**
     * Show the form for editing the institution data.
     */
    public function edit(): View
    {
        $this->authorize('editar instituicao');

        $instituicao = $this->instituicaoService->obterInstituicao();

        return view('instituicao.edit', compact('instituicao'));
    }

    /**
     * Update the specified institution config in storage.
     */
    public function update(UpdateInstituicaoRequest $request): RedirectResponse
    {
        $this->instituicaoService->atualizarInstituicao($request->validated());

        return redirect()->route('secretaria.instituicao.show')->with('success', 'Dados institucionais atualizados com sucesso!');
    }
}
