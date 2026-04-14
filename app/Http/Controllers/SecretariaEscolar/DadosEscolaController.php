<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDadosEscolaRequest;
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

        if (! $escolaId) {
            return redirect()->route('secretaria-escolar.dashboard')->with('error', 'Nenhuma escola autoral vinculada ao seu usuário.');
        }

        $escola = Escola::findOrFail($escolaId);

        return view('secretaria-escolar.escola.edit', compact('escola'));
    }

    /**
     * Update the specified school in storage.
     */
    public function update(UpdateDadosEscolaRequest $request)
    {
        $usuario = Auth::user();
        $escolaId = $usuario->escola_id;

        if (! $escolaId) {
            return redirect()->route('secretaria-escolar.dashboard')->with('error', 'Nenhuma escola vinculada ao seu usuário.');
        }

        $escola = Escola::findOrFail($escolaId);

        $validated = $request->validated();

        $escola->update($validated);

        return redirect()->route('secretaria-escolar.dados-escola.edit')->with('success', 'Dados da escola atualizados com sucesso!');
    }
}
