<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $relatorio['titulo'] }}</title>
        @vite(['resources/css/app.css'])
        <style>
            @page {
                size: A4 {{ $orientacaoPagina ?? 'portrait' }};
                margin: 12mm;
            }

            @media print {
                body {
                    background: #ffffff !important;
                    color: #111827 !important;
                    padding: 0 !important;
                }
            }
        </style>
    </head>
    <body class="bg-white p-6 text-slate-900 print:bg-white print:p-0 print:text-black">
        @include('relatorios.partials.conteudo', ['relatorio' => $relatorio])
        <script>
            window.print();
        </script>
    </body>
</html>
