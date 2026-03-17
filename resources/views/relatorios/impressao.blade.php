<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $relatorio['titulo'] }}</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-white p-6 text-slate-900">
        @include('relatorios.partials.conteudo', ['relatorio' => $relatorio])
        <script>
            window.print();
        </script>
    </body>
</html>
