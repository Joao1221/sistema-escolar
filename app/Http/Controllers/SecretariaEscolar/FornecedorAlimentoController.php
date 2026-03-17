<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFornecedorAlimentoRequest;
use App\Http\Requests\UpdateFornecedorAlimentoRequest;
use App\Models\FornecedorAlimento;
use App\Services\AlimentacaoEscolarService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FornecedorAlimentoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AlimentacaoEscolarService $service
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar alimentacao escolar', only: ['index']),
            new Middleware('can:cadastrar fornecedores de alimentos', only: ['store']),
            new Middleware('can:editar fornecedores de alimentos', only: ['update']),
        ];
    }

    public function index(Request $request)
    {
        $fornecedorEmEdicao = null;

        if ($request->filled('editar')) {
            $fornecedorEmEdicao = FornecedorAlimento::query()->findOrFail($request->integer('editar'));
        }

        return view('secretaria-escolar.alimentacao.fornecedores.index', [
            'fornecedores' => $this->service->listarFornecedores(),
            'fornecedorEmEdicao' => $fornecedorEmEdicao,
        ]);
    }

    public function store(StoreFornecedorAlimentoRequest $request)
    {
        $this->service->salvarFornecedor($request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.fornecedores.index')
            ->with('success', 'Fornecedor cadastrado com sucesso.');
    }

    public function update(UpdateFornecedorAlimentoRequest $request, FornecedorAlimento $fornecedor)
    {
        $this->service->atualizarFornecedor($fornecedor, $request->validated());

        return redirect()->route('secretaria-escolar.alimentacao.fornecedores.index')
            ->with('success', 'Fornecedor atualizado com sucesso.');
    }
}
