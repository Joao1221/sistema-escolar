<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Models\HorarioAula;
use Illuminate\Http\Request;
use App\Models\Escola;
use App\Models\Turma;
use App\Models\Disciplina;
use App\Models\Funcionario;
use App\Http\Requests\StoreHorarioRequest;
use App\Http\Requests\UpdateHorarioRequest;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EscolarHorarioController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:ver horarios', only: ['index', 'show']),
            new Middleware('can:gerenciar horarios', only: ['create', 'store', 'edit', 'update', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = HorarioAula::with(['escola', 'turma', 'disciplina', 'professor']);

        if ($request->filled('escola_id')) {
            $query->where('escola_id', $request->escola_id);
        }
        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }
        if ($request->filled('professor_id')) {
            $query->where('professor_id', $request->professor_id);
        }

        $usuario = auth()->user();
        
        // Se for admin, vê todas. Se for perfil de escola, vê apenas as suas escolas.
        if ($usuario->hasRole('Administrador da Rede') || $usuario->hasRole('Administrador')) {
            $escolas = Escola::active()->orderBy('nome')->get();
            $escolaIds = $escolas->pluck('id')->toArray();
        } else {
            $escolas = $usuario->escolas()->active()->orderBy('nome')->get();
            $escolaIds = $escolas->pluck('id')->toArray();
            
            // Força o filtro da query apenas para as escolas permitidas
            $query->whereIn('escola_id', $escolaIds);
        }

        $horarios = $query->orderBy('turma_id')->orderBy('dia_semana')->orderBy('horario_inicial')->paginate(20);
        $turmas = Turma::with('modalidade')->whereIn('escola_id', $escolaIds)->active()->orderBy('nome')->get();
        $professores = Funcionario::orderBy('nome')->get(); // Poderia filtrar apenas os 'professores' aqui, se o banco suportar.

        return view('secretaria-escolar.horarios.index', compact('horarios', 'escolas', 'turmas', 'professores'));
    }

    public function create()
    {
        $usuario = auth()->user();
        if ($usuario->hasRole('Administrador da Rede') || $usuario->hasRole('Administrador')) {
            $escolas = Escola::active()->orderBy('nome')->get();
            $escolaIds = $escolas->pluck('id')->toArray();
        } else {
            $escolas = $usuario->escolas()->active()->orderBy('nome')->get();
            $escolaIds = $escolas->pluck('id')->toArray();
        }

        $turmas = Turma::with('modalidade')->whereIn('escola_id', $escolaIds)->active()->orderBy('nome')->get();
        // Na view, via JS (Ajax/Alpine) podemos carregar os professores e disciplinas daquela turma/escola.
        // Aqui enviamos todos para fallback.
        $disciplinas = Disciplina::orderBy('nome')->get();
        $professores = Funcionario::orderBy('nome')->get();

        return view('secretaria-escolar.horarios.create', compact('escolas', 'turmas', 'disciplinas', 'professores'));
    }

    public function store(StoreHorarioRequest $request)
    {
        $validated = $request->validated();
        
        $inseridos = 0;
        foreach ($validated['horarios'] as $horario) {
            HorarioAula::create([
                'escola_id' => $validated['escola_id'],
                'turma_id' => $validated['turma_id'],
                'disciplina_id' => $horario['disciplina_id'],
                'professor_id' => $horario['professor_id'] ?? null,
                'dia_semana' => $horario['dia_semana'],
                'horario_inicial' => $horario['horario_inicial'],
                'horario_final' => $horario['horario_final'],
                'ordem_aula' => $horario['ordem_aula'] ?? null,
                'ativo' => true,
            ]);
            $inseridos++;
        }

        return redirect()->route('secretaria-escolar.horarios.index')
            ->with('success', "$inseridos horários salvos com sucesso!");
    }

    public function show(HorarioAula $horario)
    {
        return view('secretaria-escolar.horarios.show', compact('horario'));
    }

    public function edit(HorarioAula $horario)
    {
        $usuario = auth()->user();
        if ($usuario->hasRole('Administrador da Rede') || $usuario->hasRole('Administrador')) {
            $escolas = Escola::active()->orderBy('nome')->get();
            $escolaIds = $escolas->pluck('id')->toArray();
        } else {
            // Verifica de forma blindada se o usuário pode sequer editar esse horário (é da escola dele?)
            $escolas = $usuario->escolas()->active()->orderBy('nome')->get();
            $escolaIds = $escolas->pluck('id')->toArray();
            if (!in_array($horario->escola_id, $escolaIds)) {
                abort(403, 'Acesso Negado: Este horário pertence a outra escola.');
            }
        }

        $turmas = Turma::with('modalidade')->whereIn('escola_id', $escolaIds)->active()->orderBy('nome')->get();
        $disciplinas = Disciplina::orderBy('nome')->get();
        $professores = Funcionario::orderBy('nome')->get();

        return view('secretaria-escolar.horarios.edit', compact('horario', 'escolas', 'turmas', 'disciplinas', 'professores'));
    }

    public function update(UpdateHorarioRequest $request, HorarioAula $horario)
    {
        $horario->update($request->validated());

        return redirect()->route('secretaria-escolar.horarios.index')->with('success', 'Horário de aula atualizado com sucesso!');
    }

    public function destroy(HorarioAula $horario)
    {
        $horario->delete();
        return redirect()->route('secretaria-escolar.horarios.index')
            ->with('success', 'Horário removido com sucesso!');
    }
}
