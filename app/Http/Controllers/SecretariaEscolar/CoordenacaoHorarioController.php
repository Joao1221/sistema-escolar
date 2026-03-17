<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHorarioCoordenacaoRequest;
use App\Http\Requests\UpdateHorarioCoordenacaoRequest;
use App\Models\HorarioAula;
use App\Services\CoordenacaoPedagogicaService;
use Illuminate\Http\Request;

class CoordenacaoHorarioController extends Controller
{
    public function __construct(
        private readonly CoordenacaoPedagogicaService $coordenacaoPedagogicaService
    ) {
    }

    public function index(Request $request)
    {
        abort_unless($request->user()->can('consultar horarios pedagogicamente'), 403);

        return view('secretaria-escolar.coordenacao.horarios.index', [
            'horarios' => $this->coordenacaoPedagogicaService->listarHorarios(
                $request->user(),
                $request->only(['escola_id', 'turma_id', 'turno', 'professor_id'])
            ),
            'filtros' => $this->coordenacaoPedagogicaService->opcoesHorarios($request->user()),
        ]);
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->can('cadastrar horarios pedagogicamente'), 403);

        return view('secretaria-escolar.coordenacao.horarios.create', [
            ...$this->coordenacaoPedagogicaService->opcoesHorarios($request->user()),
        ]);
    }

    public function store(StoreHorarioCoordenacaoRequest $request)
    {
        $inseridos = $this->coordenacaoPedagogicaService->criarHorarios(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('secretaria-escolar.coordenacao.horarios.index')
            ->with('success', $inseridos . ' horario(s) cadastrados com sucesso.');
    }

    public function edit(Request $request, HorarioAula $horario)
    {
        abort_unless(
            $request->user()->can('editar horarios pedagogicamente') || $request->user()->can('reorganizar horarios pedagogicamente'),
            403
        );

        $this->coordenacaoPedagogicaService->garantirAcessoHorario($request->user(), $horario);

        return view('secretaria-escolar.coordenacao.horarios.edit', [
            'horario' => $horario->load(['escola', 'turma', 'disciplina', 'professor']),
            ...$this->coordenacaoPedagogicaService->opcoesHorarios($request->user()),
        ]);
    }

    public function update(UpdateHorarioCoordenacaoRequest $request, HorarioAula $horario)
    {
        $this->coordenacaoPedagogicaService->atualizarHorario(
            $request->user(),
            $horario,
            $request->validated()
        );

        return redirect()
            ->route('secretaria-escolar.coordenacao.horarios.index')
            ->with('success', 'Horario atualizado com sucesso.');
    }
}
