<?php

namespace App\Http\Controllers\Nutricionista;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlimentoRequest;
use App\Http\Requests\StoreCardapioDiarioRequest;
use App\Http\Requests\StoreCategoriaAlimentoRequest;
use App\Http\Requests\StoreFornecedorAlimentoRequest;
use App\Http\Requests\StoreMovimentacaoAlimentoRequest;
use App\Http\Requests\UpdateAlimentoRequest;
use App\Http\Requests\UpdateCardapioDiarioRequest;
use App\Http\Requests\UpdateCategoriaAlimentoRequest;
use App\Http\Requests\UpdateFornecedorAlimentoRequest;
use App\Models\Alimento;
use App\Models\CardapioDiario;
use App\Models\CategoriaAlimento;
use App\Models\FornecedorAlimento;
use App\Services\AlimentacaoEscolarService;
use App\Services\PortalNutricionistaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GestaoAlimentacaoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly PortalNutricionistaService $portalNutricionistaService,
        private readonly AlimentacaoEscolarService $alimentacaoEscolarService,
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:acessar portal da nutricionista'),
            new Middleware('can:consultar alimentos da nutricionista', only: ['alimentos']),
            new Middleware('can:consultar categorias da nutricionista', only: ['categorias']),
            new Middleware('can:consultar fornecedores da nutricionista', only: ['fornecedores']),
            new Middleware('can:consultar cardapios da nutricionista', only: ['cardapios']),
            new Middleware('can:consultar estoque da nutricionista', only: ['estoque']),
            new Middleware('can:consultar movimentacoes da nutricionista', only: ['movimentacoes']),
            new Middleware('can:consultar relatorios gerenciais da alimentacao', only: ['relatorios']),
            new Middleware('can:cadastrar alimentos', only: ['createAlimento', 'storeAlimento']),
            new Middleware('can:editar alimentos', only: ['editAlimento', 'updateAlimento']),
            new Middleware('can:cadastrar categorias de alimentos', only: ['storeCategoria']),
            new Middleware('can:editar categorias de alimentos', only: ['updateCategoria']),
            new Middleware('can:cadastrar fornecedores de alimentos', only: ['storeFornecedor']),
            new Middleware('can:editar fornecedores de alimentos', only: ['updateFornecedor']),
            new Middleware('can:lancar cardapio diario', only: ['createCardapio', 'storeCardapio', 'showCardapio', 'editCardapio', 'updateCardapio']),
        ];
    }

    public function alimentos(Request $request)
    {
        return view('nutricionista.alimentos.index', [
            'alimentos' => $this->portalNutricionistaService->listarAlimentos(),
            'categorias' => $this->alimentacaoEscolarService->listarCategorias(),
            'tituloPagina' => 'Alimentos',
            'subtituloPagina' => 'Cadastro consolidado de alimentos com foco tecnico e padronizacao da rede.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Alimentos'],
            ]),
        ]);
    }

    public function createAlimento()
    {
        return view('nutricionista.alimentos.create', [
            'categorias' => $this->alimentacaoEscolarService->listarCategorias(),
            'tituloPagina' => 'Novo Alimento',
            'subtituloPagina' => 'Cadastre um alimento base para uso tecnico e operacional em toda a rede.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Alimentos', 'url' => route('nutricionista.alimentos.index')],
                ['label' => 'Novo alimento'],
            ]),
        ]);
    }

    public function storeAlimento(StoreAlimentoRequest $request)
    {
        $this->alimentacaoEscolarService->salvarAlimento($request->validated());

        return redirect()->route('nutricionista.alimentos.index')
            ->with('success', 'Alimento cadastrado com sucesso.');
    }

    public function editAlimento(Alimento $alimento)
    {
        return view('nutricionista.alimentos.edit', [
            'alimento' => $alimento,
            'categorias' => $this->alimentacaoEscolarService->listarCategorias(),
            'tituloPagina' => 'Editar Alimento',
            'subtituloPagina' => 'Atualize os dados tecnicos do alimento selecionado.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Alimentos', 'url' => route('nutricionista.alimentos.index')],
                ['label' => 'Editar alimento'],
            ]),
        ]);
    }

    public function updateAlimento(UpdateAlimentoRequest $request, Alimento $alimento)
    {
        $this->alimentacaoEscolarService->atualizarAlimento($alimento, $request->validated());

        return redirect()->route('nutricionista.alimentos.index')
            ->with('success', 'Alimento atualizado com sucesso.');
    }

    public function categorias(Request $request)
    {
        $categoriaEmEdicao = null;

        if ($request->filled('editar')) {
            $categoriaEmEdicao = CategoriaAlimento::query()->findOrFail($request->integer('editar'));
        }

        return view('nutricionista.categorias.index', [
            'categorias' => $this->portalNutricionistaService->listarCategorias(),
            'categoriaEmEdicao' => $categoriaEmEdicao,
            'tituloPagina' => 'Categorias',
            'subtituloPagina' => 'Analise tecnica dos grupos de alimentos cadastrados.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Categorias'],
            ]),
        ]);
    }

    public function storeCategoria(StoreCategoriaAlimentoRequest $request)
    {
        $this->alimentacaoEscolarService->salvarCategoria($request->validated());

        return redirect()->route('nutricionista.categorias.index')
            ->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function updateCategoria(UpdateCategoriaAlimentoRequest $request, CategoriaAlimento $categoria)
    {
        $this->alimentacaoEscolarService->atualizarCategoria($categoria, $request->validated());

        return redirect()->route('nutricionista.categorias.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function fornecedores(Request $request)
    {
        $fornecedorEmEdicao = null;

        if ($request->filled('editar')) {
            $fornecedorEmEdicao = FornecedorAlimento::query()->findOrFail($request->integer('editar'));
        }

        return view('nutricionista.fornecedores.index', [
            'fornecedores' => $this->portalNutricionistaService->listarFornecedores(),
            'fornecedorEmEdicao' => $fornecedorEmEdicao,
            'tituloPagina' => 'Fornecedores',
            'subtituloPagina' => 'Acompanhamento gerencial dos fornecedores vinculados a alimentacao escolar.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Fornecedores'],
            ]),
        ]);
    }

    public function storeFornecedor(StoreFornecedorAlimentoRequest $request)
    {
        $this->alimentacaoEscolarService->salvarFornecedor($request->validated());

        return redirect()->route('nutricionista.fornecedores.index')
            ->with('success', 'Fornecedor cadastrado com sucesso.');
    }

    public function updateFornecedor(UpdateFornecedorAlimentoRequest $request, FornecedorAlimento $fornecedor)
    {
        $this->alimentacaoEscolarService->atualizarFornecedor($fornecedor, $request->validated());

        return redirect()->route('nutricionista.fornecedores.index')
            ->with('success', 'Fornecedor atualizado com sucesso.');
    }

    public function cardapios(Request $request)
    {
        return view('nutricionista.cardapios.index', [
            'cardapios' => $this->portalNutricionistaService->listarCardapios($request->all()),
            ...$this->portalNutricionistaService->obterOpcoesFiltros(),
            'filtros' => $request->all(),
            'tituloPagina' => 'Cardapios',
            'subtituloPagina' => 'Visao comparativa dos cardapios lancados pelas escolas.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Cardapios'],
            ]),
        ]);
    }

    public function createCardapio(Request $request)
    {
        return view('nutricionista.cardapios.create', [
            ...$this->alimentacaoEscolarService->opcoesFormulario($request->user()),
            'tituloPagina' => 'Novo Cardapio',
            'subtituloPagina' => 'Monte um cardapio tecnico para uma escola da rede.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Cardapios', 'url' => route('nutricionista.cardapios.index')],
                ['label' => 'Novo cardapio'],
            ]),
        ]);
    }

    public function storeCardapio(StoreCardapioDiarioRequest $request)
    {
        $cardapio = $this->alimentacaoEscolarService->criarCardapio($request->user(), $request->validated());

        return redirect()->route('nutricionista.cardapios.show', $cardapio)
            ->with('success', 'Cardapio lancado com sucesso.');
    }

    public function showCardapio(Request $request, CardapioDiario $cardapio)
    {
        $this->alimentacaoEscolarService->garantirEscolaPermitida($request->user(), $cardapio->escola_id);

        return view('nutricionista.cardapios.show', [
            'cardapio' => $cardapio->load(['escola', 'usuario', 'itens.alimento']),
            'tituloPagina' => 'Cardapio do Dia',
            'subtituloPagina' => 'Consulta detalhada do cardapio registrado para a escola selecionada.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Cardapios', 'url' => route('nutricionista.cardapios.index')],
                ['label' => optional($cardapio->data_cardapio)->format('d/m/Y') ?: 'Detalhe'],
            ]),
        ]);
    }

    public function editCardapio(Request $request, CardapioDiario $cardapio)
    {
        $this->alimentacaoEscolarService->garantirEscolaPermitida($request->user(), $cardapio->escola_id);

        return view('nutricionista.cardapios.edit', [
            'cardapio' => $cardapio->load('itens'),
            ...$this->alimentacaoEscolarService->opcoesFormulario($request->user()),
            'tituloPagina' => 'Editar Cardapio',
            'subtituloPagina' => 'Ajuste o cardapio tecnico ja lancado.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Cardapios', 'url' => route('nutricionista.cardapios.index')],
                ['label' => 'Editar cardapio'],
            ]),
        ]);
    }

    public function updateCardapio(UpdateCardapioDiarioRequest $request, CardapioDiario $cardapio)
    {
        $this->alimentacaoEscolarService->atualizarCardapio($request->user(), $cardapio, $request->validated());

        return redirect()->route('nutricionista.cardapios.show', $cardapio)
            ->with('success', 'Cardapio atualizado com sucesso.');
    }

    public function estoque(Request $request)
    {
        return view('nutricionista.estoque.index', [
            'estoque' => $this->portalNutricionistaService->listarEstoqueComparativo($request->all()),
            'alertasValidade' => $this->portalNutricionistaService->obterAlertasValidade(),
            ...$this->portalNutricionistaService->obterOpcoesFiltros(),
            'filtros' => $request->all(),
            'tituloPagina' => 'Estoque e Validade',
            'subtituloPagina' => 'Monitoramento comparativo de saldo, estoque minimo e alertas de vencimento.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Estoque e Validade'],
            ]),
        ]);
    }

    public function movimentacoes(Request $request)
    {
        return view('nutricionista.movimentacoes.index', [
            'movimentacoes' => $this->portalNutricionistaService->listarMovimentacoes($request->all()),
            ...$this->portalNutricionistaService->obterOpcoesFiltros(),
            'filtros' => $request->all(),
            'tituloPagina' => 'Entradas e Saidas',
            'subtituloPagina' => 'Leitura gerencial das movimentacoes realizadas pelas escolas.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Entradas e Saidas'],
            ]),
        ]);
    }

    public function createMovimentacao(Request $request)
    {
        if (
            ! $request->user()->can('registrar entrada de alimentos') &&
            ! $request->user()->can('registrar saida de alimentos')
        ) {
            abort(403);
        }

        return view('nutricionista.movimentacoes.create', [
            'tipoPadrao' => $request->get('tipo', 'entrada'),
            ...$this->alimentacaoEscolarService->opcoesFormulario($request->user()),
            'tituloPagina' => 'Registrar Movimentacao',
            'subtituloPagina' => 'Lance entrada ou saida com visao tecnica e por escola.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Entradas e Saidas', 'url' => route('nutricionista.movimentacoes.index')],
                ['label' => 'Nova movimentacao'],
            ]),
        ]);
    }

    public function storeMovimentacao(StoreMovimentacaoAlimentoRequest $request)
    {
        $this->alimentacaoEscolarService->registrarMovimentacao($request->user(), $request->validated());

        return redirect()->route('nutricionista.movimentacoes.index')
            ->with('success', 'Movimentacao registrada com sucesso.');
    }

    public function relatorios()
    {
        return view('nutricionista.relatorios.index', [
            ...$this->portalNutricionistaService->obterRelatoriosGerenciais(),
            'tituloPagina' => 'Relatorios Gerenciais',
            'subtituloPagina' => 'Painel inicial de comparativos e indicadores da alimentacao escolar.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Relatorios'],
            ]),
        ]);
    }
}
