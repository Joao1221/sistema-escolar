<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDemandaPsicossocialEscolarRequest;
use App\Models\DemandaPsicossocial;
use App\Services\PsicossocialService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class DemandaPsicossocialEscolarController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly PsicossocialService $psicossocialService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar demandas psicossociais escolares', only: ['index', 'show', 'dadosEscola']),
            new Middleware('can:registrar demandas psicossociais escolares', only: ['create', 'store']),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.demandas-psicossociais.index', [
            'demandas' => $this->psicossocialService->listarDemandasEscolares($request->user(), $request->all()),
            'escolas' => $this->psicossocialService->opcoesFormulario($request->user())['escolas'],
            'filtros' => $request->all(),
        ]);
    }

    public function create(Request $request)
    {
        return view('secretaria-escolar.demandas-psicossociais.create', [
            'escolas' => $this->psicossocialService->opcoesFormulario($request->user())['escolas'],
            'origemDemandaLabel' => $this->origemDemandaLabel($request->user()),
        ]);
    }

    public function store(StoreDemandaPsicossocialEscolarRequest $request)
    {
        $validated = $request->validated();

        $demanda = $this->psicossocialService->criarDemandaEscolar($request->user(), $validated);

        return redirect()->route('secretaria-escolar.demandas-psicossociais.show', $demanda)
            ->with('success', 'Demanda psicossocial registrada com sucesso.');
    }

    public function show(Request $request, DemandaPsicossocial $demanda)
    {
        return view('secretaria-escolar.demandas-psicossociais.show', [
            ...$this->psicossocialService->carregarDemandaEscolar($request->user(), $demanda),
        ]);
    }

    public function dadosEscola(Request $request, int $escolaId)
    {
        try {
            return response()->json(
                $this->psicossocialService->dadosEscola($request->user(), $escolaId)
            );
        } catch (HttpExceptionInterface $e) {
            return response()->json([
                'error' => $e->getMessage() ?: 'Nao foi possivel acessar os dados da escola.',
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'error' => 'Erro interno ao carregar dados da escola.',
            ], 500);
        }
    }

    private function origemDemandaLabel($usuario): string
    {
        if ($usuario->hasRole('Diretor Escolar')) {
            return 'Direcao escolar';
        }

        if ($usuario->hasRole("Coordenador Pedag\u{00F3}gico")) {
            return 'Coordenacao pedagogica';
        }

        return 'Escola';
    }
}
