<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Matricula;
use App\Models\MatriculaHistorico;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $escolaId = (int) Auth::user()->escola_id;
        $escola = Escola::findOrFail($escolaId);

        $stats = [
            'total_alunos' => Aluno::where('escola_id', $escolaId)->count(),
            'matriculas_ativas' => Matricula::where('escola_id', $escolaId)->where('status', 'ativa')->count(),
            'matriculas_regular' => Matricula::where('escola_id', $escolaId)->where('status', 'ativa')->where('tipo', 'regular')->count(),
            'matriculas_aee' => Matricula::where('escola_id', $escolaId)->where('status', 'ativa')->where('tipo', 'aee')->count(),
            'turmas_ativas' => Turma::where('escola_id', $escolaId)->where('ativa', true)->count(),
            'ocupacao_vagas' => [
                'total' => Turma::where('escola_id', $escolaId)->where('ativa', true)->sum('vagas'),
                'ocupadas' => Matricula::where('escola_id', $escolaId)->where('status', 'ativa')->whereNotNull('turma_id')->count(),
            ],
        ];

        $recent_activities = MatriculaHistorico::with(['matricula.aluno', 'usuario'])
            ->whereHas('matricula', function ($query) use ($escolaId) {
                $query->where('escola_id', $escolaId);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('secretaria-escolar.dashboard', compact('stats', 'recent_activities', 'escola'));
    }
}
