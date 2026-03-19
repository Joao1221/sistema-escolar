<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ThemeController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => ['required', 'in:lilas,grafite,verde'],
        ]);

        if (!Schema::hasColumn('usuarios', 'theme')) {
            return back()->with('error', 'Não foi possível salvar o tema: coluna ausente. Execute as migrações.');
        }

        $user = $request->user();
        $user->theme = $request->input('theme');
        $user->save();

        return back()->with('success', 'Tema atualizado com sucesso.');
    }
}
