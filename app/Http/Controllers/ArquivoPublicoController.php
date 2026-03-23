<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ArquivoPublicoController extends Controller
{
    public function show(string $path)
    {
        abort_unless(Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path), [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
