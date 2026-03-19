<aside class="fixed inset-y-0 left-0 w-64 bg-[#163229] text-white flex flex-col flex-shrink-0 overflow-y-auto shadow-xl z-30">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(241,185,94,0.22),_transparent_40%),linear-gradient(180deg,_rgba(18,50,41,1)_0%,_rgba(8,25,21,1)_100%)]"></div>
    <div class="relative flex min-h-full flex-col">
        <div class="border-b border-white/10 px-6 py-7">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-200">Rede Municipal</p>
            <h2 class="mt-3 text-3xl font-bold font-fraunces text-white">Portal da Nutricionista</h2>
            <p class="mt-2 text-sm text-emerald-100/80">Analise tecnica, comparativos entre escolas e visao gerencial da alimentacao escolar.</p>
        </div>

        <div class="flex-1 px-4 py-6">
            <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.35em] text-emerald-200/70">Painel Tecnico</p>
            <nav class="space-y-1">
                @php
                    $links = [
                        ['rota' => 'nutricionista.dashboard', 'label' => 'Dashboard'],
                        ['rota' => 'nutricionista.estoque.index', 'label' => 'Estoque e Validade'],
                        ['rota' => 'nutricionista.cardapios.index', 'label' => 'Cardapios'],
                        ['rota' => 'nutricionista.movimentacoes.index', 'label' => 'Entradas e Saidas'],
                        ['rota' => 'nutricionista.alimentos.index', 'label' => 'Alimentos'],
                        ['rota' => 'nutricionista.categorias.index', 'label' => 'Categorias'],
                        ['rota' => 'nutricionista.fornecedores.index', 'label' => 'Fornecedores'],
                        ['rota' => 'nutricionista.relatorios.index', 'label' => 'Relatorios'],
                        ['rota' => 'nutricionista.auditoria.index', 'label' => 'Auditoria'],
                    ];
                @endphp

                @foreach ($links as $link)
                    <a href="{{ route($link['rota']) }}" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs(str_replace('.index', '.*', $link['rota'])) || request()->routeIs($link['rota']) ? 'bg-white text-[#17332a] shadow-lg' : 'text-emerald-100/80 hover:bg-white/10 hover:text-white' }}">
                        <span>{{ $link['label'] }}</span>
                        <span class="text-[10px] uppercase tracking-[0.25em] {{ request()->routeIs(str_replace('.index', '.*', $link['rota'])) || request()->routeIs($link['rota']) ? 'text-amber-600' : 'text-emerald-200/60' }}">Abrir</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="border-t border-white/10 px-6 py-5">
            <div class="rounded-2xl bg-white/8 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-200">Operacao da escola</p>
                <p class="mt-2 text-sm text-emerald-100/80">A escola mantém o registro diário do cardápio.</p>
            </div>
        </div>
    </div>
</aside>
