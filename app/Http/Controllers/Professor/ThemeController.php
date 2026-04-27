<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateThemeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class ThemeController extends Controller
{
    public function update(UpdateThemeRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        if (! Schema::hasColumn('usuarios', 'theme')) {
            return back()->with('error', 'Não foi possível salvar o tema: coluna ausente. Execute as migrações.');
        }

        $user = $request->user();
        $user->theme = $dados['theme'];
        $user->save();

        $redirectTo = $dados['redirect_to'] ?? route('professor.dashboard');
        return redirect($redirectTo)->with('success', 'Tema atualizado com sucesso.');
    }
}
