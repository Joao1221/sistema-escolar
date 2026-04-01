<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Models\DemandaPsicossocial;
use App\Services\PsicossocialService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class DemandaPsicossocialEscolarController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly PsicossocialService $psicossocialService
    ) {
    }

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

    public function store(Request $request)
    {
        $tipoPublico = (string) $request->input('tipo_publico');

        $validated = $request->validate([
            'escola_id' => ['required', 'exists:escolas,id'],
            'tipo_atendimento' => ['required', Rule::in(['psicologia', 'psicopedagogia', 'psicossocial'])],
            'tipo_publico' => ['required', Rule::in(['aluno', 'professor', 'funcionario', 'responsavel', 'coletivo'])],
            'aluno_id' => [Rule::requiredIf($tipoPublico === 'aluno'), 'nullable', 'exists:alunos,id'],
            'funcionario_id' => [Rule::requiredIf(in_array($tipoPublico, ['professor', 'funcionario'], true)), 'nullable', 'exists:funcionarios,id'],
            'responsavel_nome' => [Rule::requiredIf($tipoPublico === 'responsavel'), 'nullable', 'string', 'max:255'],
            'responsavel_telefone' => ['nullable', 'string', 'max:20'],
            'responsavel_vinculo' => [Rule::requiredIf($tipoPublico === 'responsavel'), 'nullable', 'string', 'max:100'],
            'motivo_inicial' => ['required', 'string'],
            'prioridade' => ['nullable', Rule::in(['baixa', 'media', 'alta', 'urgente'])],
            'data_solicitacao' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

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
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            return response()->json([
                'error' => $e->getMessage() ?: 'Nao foi possivel acessar os dados da escola.',
            ], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
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
