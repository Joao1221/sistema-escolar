# ✅ CHECKLIST OFICIAL UI/UX - SISTEMA-ESCOLAR

## 🎯 OBJETIVO

Garantir que TODA interface criada siga o padrão do sistema antes de ser entregue.

Se qualquer item falhar → corrigir antes de finalizar.

---

# 📱 1. RESPONSIVIDADE

### ✔ Verificações obrigatórias

* [ ] Funciona em mobile (320px+)
* [ ] Funciona em tablet (768px+)
* [ ] Funciona em desktop (1024px+)
* [ ] Nenhum elemento quebra layout
* [ ] Nenhum scroll horizontal indevido

---

## 🔹 1.1 Estratégia correta aplicada?

### Portal Professor

* [ ] Mobile-first aplicado corretamente
* [ ] Layout começa no mobile e escala para desktop
* [ ] Interface simples e rápida

### Outros Portais

* [ ] Desktop-first aplicado
* [ ] Layout adaptado para mobile corretamente
* [ ] Nenhuma perda de funcionalidade no mobile

---

# 📏 2. BREAKPOINTS

* [ ] Apenas breakpoints padrão utilizados:

  * sm (640px)
  * md (768px)
  * lg (1024px)
  * xl (1280px)
  * 2xl (1536px)

* [ ] Nenhum breakpoint custom fora do padrão

---

# 🎨 3. PADRÃO VISUAL

### ✔ Consistência

* [ ] Cores seguem padrão institucional (azul, branco, cinza)
* [ ] Tipografia legível
* [ ] Espaçamentos consistentes
* [ ] Layout limpo (sem poluição visual)

---

### ✔ Componentes

* [ ] Cards com:

  * border-radius (10–12px)
  * sombra leve
  * padding adequado

* [ ] Botões consistentes

* [ ] Inputs alinhados

* [ ] Tabelas organizadas

---

# 🧱 4. ESTRUTURA DE LAYOUT

* [ ] Possui topbar
* [ ] Possui área de conteúdo bem definida
* [ ] Grid organizado (flex/grid)
* [ ] Espaçamento padrão entre seções

---

# 🧩 5. CLASSE RAIZ DO PORTAL

* [ ] Classe correta aplicada no `<body>`

Exemplo:

* portal-professor
* portal-secretaria-escolar
* etc.

---

# ⚡ 6. PERFORMANCE

* [ ] Sem excesso de DOM
* [ ] Sem loops desnecessários no front
* [ ] Sem CSS redundante
* [ ] Carregamento leve

---

# 🚫 7. PROIBIÇÕES

* [ ] NÃO usa CSS inline desnecessário
* [ ] NÃO usa medidas fixas que quebram responsividade
* [ ] NÃO usa position absolute sem controle
* [ ] NÃO usa largura fixa em px sem fallback responsivo

---

# 🧪 8. USABILIDADE (UX)

* [ ] Interface intuitiva
* [ ] Ações claras (botões com significado)
* [ ] Feedback visual (hover, active, loading)
* [ ] Fluxo lógico (sem confusão para o usuário)

---

# 🔍 9. ACESSIBILIDADE (BÁSICO)

* [ ] Contraste adequado
* [ ] Labels em inputs
* [ ] Botões com texto claro
* [ ] Sem dependência exclusiva de cor

---

# 🧾 10. VALIDAÇÃO FINAL (OBRIGATÓRIO)

Antes de entregar:

* [ ] Segui o `/docs/ui-ux-padrao.md`
* [ ] Apliquei estratégia correta do portal
* [ ] Revisei usando este checklist
* [ ] Corrigi todos os problemas encontrados

---

# 🚨 REGRA FINAL

Se QUALQUER item estiver pendente:

👉 NÃO entregar
👉 Corrigir primeiro

---

# 🧠 MODO DE USO COM IA

Sempre incluir no prompt:

"Valide a resposta usando /docs/checklist-ui.md antes de finalizar."
