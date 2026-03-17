<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Http\Requests\StoreDisciplinaRequest;
use App\Http\Requests\UpdateDisciplinaRequest;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function index(Request $request)
    {
        $query = Disciplina::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }

        $disciplinas = $query->orderBy('nome')->paginate(15);

        return view('secretaria.disciplinas.index', compact('disciplinas'));
    }

    public function create()
    {
        return view('secretaria.disciplinas.create');
    }

    public function store(StoreDisciplinaRequest $request)
    {
        Disciplina::create($request->validated());

        return redirect()->route('secretaria.disciplinas.index')
            ->with('success', 'Disciplina cadastrada com sucesso!');
    }

    public function edit(Disciplina $disciplina)
    {
        return view('secretaria.disciplinas.edit', compact('disciplina'));
    }

    public function update(UpdateDisciplinaRequest $request, Disciplina $disciplina)
    {
        $disciplina->update($request->validated());

        return redirect()->route('secretaria.disciplinas.index')
            ->with('success', 'Disciplina atualizada com sucesso!');
    }

    public function toggle(Disciplina $disciplina)
    {
        $disciplina->update(['ativo' => !$disciplina->ativo]);

        return back()->with('success', 'Status da disciplina atualizado!');
    }
}
