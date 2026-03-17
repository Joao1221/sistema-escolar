<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHorarioDirecaoRequest;
use App\Http\Requests\UpdateHorarioDirecaoRequest;
use App\Models\HorarioAula;
use App\Services\DirecaoEscolarService;
use Illuminate\Http\Request;

class DirecaoHorarioController extends Controller
{
    public function __construct(
        private readonly DirecaoEscolarService $direcaoEscolarService
    ) {
    }

    public function index(Request $request)
    {
        abort_unless($request->user()->can('consultar horarios da direcao'), 403);

        return view('secretaria-escolar.direcao.horarios.index', [
            'horarios' => $this->direcaoEscolarService->listarHorarios(
                $request->user(),
                $request->only(['escola_id', 'turma_id', 'turno', 'professor_id'])
            ),
            'filtros' => $this->direcaoEscolarService->opcoesHorarios($request->user()),
        ]);
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->can('cadastrar horarios da direcao'), 403);

        return view('secretaria-escolar.direcao.horarios.create', [
            ...$this->direcaoEscolarService->opcoesHorarios($request->user()),
        ]);
    }

    public function store(StoreHorarioDirecaoRequest $request)
    {
        $inseridos = $this->direcaoEscolarService->criarHorarios(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('secretaria-escolar.direcao.horarios.index')
            ->with('success', $inseridos . ' horario(s) cadastrados com sucesso.');
    }

    public function edit(Request $request, HorarioAula $horario)
    {
        abort_unless(
            $request->user()->can('editar horarios da direcao') || $request->user()->can('reorganizar horarios da direcao'),
            403
        );

        $this->direcaoEscolarService->garantirAcessoHorario($request->user(), $horario);

        return view('secretaria-escolar.direcao.horarios.edit', [
            'horario' => $horario->load(['escola', 'turma', 'disciplina', 'professor']),
            ...$this->direcaoEscolarService->opcoesHorarios($request->user()),
        ]);
    }

    public function update(UpdateHorarioDirecaoRequest $request, HorarioAula $horario)
    {
        $this->direcaoEscolarService->atualizarHorario(
            $request->user(),
            $horario,
            $request->validated()
        );

        return redirect()
            ->route('secretaria-escolar.direcao.horarios.index')
            ->with('success', 'Horario atualizado com sucesso.');
    }
}
