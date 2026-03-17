<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use App\Models\Escola;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Métricas Operacionais
        $stats = [
            'total_alunos' => \App\Models\Aluno::count(),
            'matriculas_ativas' => \App\Models\Matricula::where('status', 'ativa')->count(),
            'matriculas_regular' => \App\Models\Matricula::where('status', 'ativa')->where('tipo', 'regular')->count(),
            'matriculas_aee' => \App\Models\Matricula::where('status', 'ativa')->where('tipo', 'aee')->count(),
            'turmas_ativas' => Turma::where('ativa', true)->count(),
            'ocupacao_vagas' => [
                'total' => Turma::where('ativa', true)->sum('vagas'),
                'ocupadas' => \App\Models\Matricula::where('status', 'ativa')->whereNotNull('turma_id')->count(),
            ]
        ];

        // Histórico Recente de Movimentações
        $recent_activities = \App\Models\MatriculaHistorico::with(['matricula.aluno', 'usuario'])
            ->latest()
            ->take(6)
            ->get();
        
        return view('secretaria-escolar.dashboard', compact('stats', 'recent_activities'));
    }
}
