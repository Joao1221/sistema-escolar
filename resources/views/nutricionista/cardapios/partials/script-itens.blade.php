<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('itens-cardapio');
        const botao = document.getElementById('adicionar-item');

        if (!container || !botao) {
            return;
        }

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
