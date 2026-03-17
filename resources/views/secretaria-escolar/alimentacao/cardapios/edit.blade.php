<x-secretaria-escolar-layout>
    <div class="px-8 py-6">
        <div class="mx-auto max-w-5xl rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Editar cardapio diario</h1>
                    <p class="mt-2 text-sm text-slate-500">Atualize os itens do cardapio e mantenha a escola alinhada com a oferta do dia.</p>
                </div>
                <a href="{{ route('secretaria-escolar.alimentacao.cardapios.show', $cardapio) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Voltar</a>
            </div>

            <form method="POST" action="{{ route('secretaria-escolar.alimentacao.cardapios.update', $cardapio) }}" class="mt-8 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Escola</label>
                        <input type="text" value="{{ $cardapio->escola?->nome }}" disabled class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 shadow-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Data do cardapio</label>
                        <input type="date" name="data_cardapio" value="{{ old('data_cardapio', $cardapio->data_cardapio->toDateString()) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes gerais</label>
                    <textarea name="observacoes" rows="3" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('observacoes', $cardapio->observacoes) }}</textarea>
                </div>

                <div class="rounded-2xl border border-slate-200">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Itens do cardapio</h2>
                        <button type="button" id="adicionar-item" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Adicionar item</button>
                    </div>
                    <div id="itens-cardapio" class="space-y-4 p-5">
                        @php($itensOld = old('itens', $cardapio->itens->map(fn ($item) => ['refeicao' => $item->refeicao, 'alimento_id' => $item->alimento_id, 'quantidade_prevista' => $item->quantidade_prevista, 'observacoes' => $item->observacoes])->all()))
                        @foreach ($itensOld as $indice => $item)
                            <div class="grid gap-4 rounded-2xl border border-slate-200 p-4 md:grid-cols-[1fr_1fr_140px_auto]">
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Refeicao</label>
                                    <input type="text" name="itens[{{ $indice }}][refeicao]" value="{{ $item['refeicao'] ?? '' }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Alimento</label>
                                    <select name="itens[{{ $indice }}][alimento_id]" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="">Selecione</option>
                                        @foreach ($alimentos as $alimento)
                                            <option value="{{ $alimento->id }}" @selected(($item['alimento_id'] ?? null) == $alimento->id)>{{ $alimento->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Qtd. prevista</label>
                                    <input type="number" step="0.001" min="0" name="itens[{{ $indice }}][quantidade_prevista]" value="{{ $item['quantidade_prevista'] ?? '' }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remover-item rounded-xl border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">Remover</button>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes do item</label>
                                    <input type="text" name="itens[{{ $indice }}][observacoes]" value="{{ $item['observacoes'] ?? '' }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Salvar alteracoes
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('itens-cardapio');
            const botao = document.getElementById('adicionar-item');
            let indice = container.querySelectorAll('[name*="[refeicao]"]').length;
            const opcoes = @json($alimentos->map(fn ($alimento) => ['id' => $alimento->id, 'nome' => $alimento->nome])->values());

            function montarOptions() {
                return '<option value="">Selecione</option>' + opcoes.map(item => `<option value="${item.id}">${item.nome}</option>`).join('');
            }

            botao.addEventListener('click', function () {
                const bloco = document.createElement('div');
                bloco.className = 'grid gap-4 rounded-2xl border border-slate-200 p-4 md:grid-cols-[1fr_1fr_140px_auto]';
                bloco.innerHTML = `
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Refeicao</label>
                        <input type="text" name="itens[${indice}][refeicao]" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Alimento</label>
                        <select name="itens[${indice}][alimento_id]" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">${montarOptions()}</select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Qtd. prevista</label>
                        <input type="number" step="0.001" min="0" name="itens[${indice}][quantidade_prevista]" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remover-item rounded-xl border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">Remover</button>
                    </div>
                    <div class="md:col-span-4">
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes do item</label>
                        <input type="text" name="itens[${indice}][observacoes]" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                `;
                container.appendChild(bloco);
                indice++;
            });

            container.addEventListener('click', function (event) {
                if (event.target.classList.contains('remover-item')) {
                    event.target.closest('.grid').remove();
                }
            });
        });
    </script>
</x-secretaria-escolar-layout>
