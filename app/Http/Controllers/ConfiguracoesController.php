<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateParametrosRequest;
use App\Http\Requests\StoreModalidadeRequest;
use App\Services\ConfiguracoesService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ConfiguracoesController extends Controller
{
    use AuthorizesRequests;

    protected $configuracoesService;

    public function __construct(ConfiguracoesService $configuracoesService)
    {
        $this->configuracoesService = $configuracoesService;
    }

    /**
     * Exibe a tela de configurações globais.
     */
    public function index()
    {
        $this->authorize('visualizar configuracoes');

        $parametros = $this->configuracoesService->obterParametros();
        $modalidades = $this->configuracoesService->listarModalidades();

        return view('configuracoes.index', compact('parametros', 'modalidades'));
    }

    /**
     * Atualiza os parâmetros globais da rede.
     */
    public function updateParametros(UpdateParametrosRequest $request)
    {
        $this->configuracoesService->atualizarParametros($request->validated());

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Parâmetros globais atualizados com sucesso!');
    }

    /**
     * Salva ou atualiza uma modalidade de ensino.
     */
    public function storeModalidade(StoreModalidadeRequest $request)
    {
        $this->configuracoesService->salvarModalidade($request->validated());

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Modalidade de ensino cadastrada com sucesso!');
    }

    /**
     * Atualiza uma modalidade de ensino existente.
     */
    public function updateModalidade(StoreModalidadeRequest $request, $id)
    {
        $this->configuracoesService->salvarModalidade($request->validated(), $id);

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Modalidade de ensino atualizada com sucesso!');
    }

    /**
     * Alterna o status da modalidade.
     */
    public function toggleModalidade($id)
    {
        $this->authorize('editar configuracoes');
        $this->configuracoesService->alternarStatusModalidade($id);

        return redirect()->route('secretaria.configuracoes.index')->with('success', 'Status da modalidade alterado!');
    }
}
