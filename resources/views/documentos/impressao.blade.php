<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $documento['titulo'] }}</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-white p-8 text-slate-900">
        @include('documentos.partials.conteudo', ['documento' => $documento])

        <script>
            window.onload = function () {
                window.print();
            };
        </script>
    </body>
</html>
