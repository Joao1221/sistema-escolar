<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $documento['titulo'] }}</title>
        @vite(['resources/css/app.css'])
        <style>
            @if (! empty($orientacaoPagina ?? null))
                @page {
                    size: A4 {{ $orientacaoPagina }};
                }
            @endif

            @media print {
                .print\:border-0,
                .print\:border-0 * {
                    border: none !important;
                    border-radius: 0 !important;
                    background: transparent !important;
                    box-shadow: none !important;
                }
                .print\:rounded-none,
                .print\:rounded-none * {
                    border-radius: 0 !important;
                }
            }
        </style>
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
