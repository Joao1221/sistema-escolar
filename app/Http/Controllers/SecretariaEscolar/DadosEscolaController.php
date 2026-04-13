<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Escola;
use Illuminate\Support\Facades\Auth;

class DadosEscolaController extends Controller
{
    /**
     * Show the form for editing the school data.
     */
    public function edit()
    {
        $usuario = Auth::user();
        $escolaId = $usuario->escola_id;
        
        if (!$escolaId) {
            return redirect()->route('secretaria-escolar.dashboard')->with('error', 'Nenhuma escola autoral vinculada ao seu usuário.');
        }

        $escola = Escola::findOrFail($escolaId);

        return view('secretaria-escolar.escola.edit', compact('escola'));
    }

    /**
     * Update the specified school in storage.
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();
        $escolaId = $usuario->escola_id;
        
        if (!$escolaId) {
            return redirect()->route('secretaria-escolar.dashboard')->with('error', 'Nenhuma escola vinculada ao seu usuário.');
        }

        $escola = Escola::findOrFail($escolaId);

        $validated = $request->validate([
            'nome_gestor' => 'required|string|max:70',
            'cpf_gestor' => 'required|string|size:11',
            'ato_posse_diretor' => 'required|string|max:30',
            'nome' => 'required|string|max:255',
            'qtd_salas' => 'required|integer|min:0',
            'email' => 'nullable|email|max:70',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:70',
            'cidade' => 'required|string|max:50',
            'uf' => 'required|string|size:2',
            'cep' => 'required|string|max:10', // Exatamente como 00000-000
            'ato_criacao' => 'nullable|string|max:30',
            'ato_autoriza' => 'nullable|string|max:30',
            'ato_recon' => 'nullable|string|max:30',
        ]);

        $escola->update($validated);

        return redirect()->route('secretaria-escolar.dados-escola.edit')->with('success', 'Dados da escola atualizados com sucesso!');
    }
}
