<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalidadeRequest;
use App\Http\Requests\UpdateParametrosRequest;
use App\Services\ConfiguracoesService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class ConfiguracoesController extends Controller
{
    use AuthorizesRequests;

    protected ConfiguracoesService $configuracoesService;

    public function __construct(ConfiguracoesService $configuracoesService)
    {
        $this->configuracoesService = $configuracoesService;
    }

    /**
     * Exibe a tela de configurações globais.
     */
    public function index(): View
    {
        $this->authorize('visualizar configuracoes');

        $parametros = $this->configuracoesService->obterParametros();
        $modalidades = $this->configuracoesService->listarModalidades();

        return view('configuracoes.index', compact('parametros', 'modalidades'));
    }

    /**
     * Atualiza os parâmetros globais da rede.
     */
    public function updateParametros(UpdateParametrosRequest $request): RedirectResponse
    {
        $this->configuracoesService->atualizarParametros($request->validated());

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Parâmetros globais atualizados com sucesso!');
    }

    /**
     * Salva ou atualiza uma modalidade de ensino.
     */
    public function storeModalidade(StoreModalidadeRequest $request): RedirectResponse
    {
        $this->configuracoesService->salvarModalidade($request->validated());

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Modalidade de ensino cadastrada com sucesso!');
    }

    /**
     * Atualiza uma modalidade de ensino existente.
     */
    public function updateModalidade(StoreModalidadeRequest $request, int $id): RedirectResponse
    {
        $this->configuracoesService->salvarModalidade($request->validated(), $id);

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Modalidade de ensino atualizada com sucesso!');
    }

    /**
     * Alterna o status da modalidade.
     */
    public function toggleModalidade(int $id): RedirectResponse
    {
        $this->authorize('editar configuracoes');
        $this->configuracoesService->alternarStatusModalidade($id);

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Status da modalidade alterado!');
    }
}
