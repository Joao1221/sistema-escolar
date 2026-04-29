# 🎯 PADRÃO OFICIAL UI/UX - SISTEMA-ESCOLAR

## 📌 REGRA GLOBAL (OBRIGATÓRIA)

Antes de criar QUALQUER tela, página ou componente, o agente DEVE:

1. Ler completamente este arquivo
2. Aplicar TODAS as regras aqui definidas
3. Confirmar que aplicou o padrão antes de gerar código

Caso não siga → a resposta é inválida

---

# 📱 RESPONSIVIDADE (REGRA PRINCIPAL)

## 🔹 Regra geral do sistema

* Todo o sistema é RESPONSIVO
* Layout deve funcionar perfeitamente em:

  * Mobile (prioridade)
  * Tablet
  * Desktop

---

## 🔹 Estratégia por portal

### 🟢 Portal Professor

➡️ MOBILE-FIRST (OBRIGATÓRIO)

Motivo:

* Uso principal em sala de aula
* Celular é o dispositivo dominante

Regras:

* Começar sempre pelo mobile
* Depois adaptar para telas maiores
* Interface simples, rápida e direta

---

### 🔵 Demais portais

➡️ DESKTOP-FIRST ADAPTADO PARA MOBILE

Portais:

* Secretaria Escolar
* Secretaria de Educação
* Coordenação
* Direção
* Nutrição
* Psicologia

Regras:

* Priorizar produtividade em desktop
* Adaptar para mobile SEM quebrar UX

---

# 📏 BREAKPOINTS PADRÃO

NUNCA usar valores aleatórios

```css
sm: 640px
md: 768px
lg: 1024px
xl: 1280px
2xl: 1536px
```

---

# 🎨 PADRÃO VISUAL

## 🎯 Estilo

* Clean institucional
* Baseado em sistemas governamentais modernos
* Foco em clareza e legibilidade

## 🎨 Cores

* Azul (principal)
* Branco (fundo)
* Cinza (apoio)

## 🔲 Componentes

* Cards com borda suave
* radius: 10px a 12px
* sombra leve

---

# 🧱 ESTRUTURA DE LAYOUT

## Obrigatório em TODAS as páginas:

* Topbar
* Área de conteúdo
* Espaçamento consistente
* Grid organizado

---

# 📦 CLASSES RAIZ POR PORTAL

DEVEM estar no <body>

```html
<body class="portal-professor">
<body class="portal-secretaria-escolar">
<body class="portal-secretaria-educacao">
<body class="portal-coordenacao">
<body class="portal-direcao">
<body class="portal-nutricao">
<body class="portal-psicologia">
```

---

# ⚠️ PROIBIÇÕES

❌ Não usar:

* CSS inline sem necessidade
* Breakpoints fora do padrão
* Layout fixo não responsivo
* Componentes desalinhados

---

# ✅ BOAS PRÁTICAS

✔ Usar Tailwind sempre que possível
✔ Criar componentes reutilizáveis
✔ Manter consistência visual
✔ Priorizar performance

---

# 🚨 VALIDAÇÃO FINAL (OBRIGATÓRIO)

Antes de entregar qualquer código, o agente deve verificar:

* Está responsivo?
* Seguiu mobile-first (quando necessário)?
* Está dentro do padrão visual?
* Usou os breakpoints corretos?

Se alguma resposta for NÃO → corrigir antes de entregar


# 🖨️ SUPORTE A IMPRESSÃO (OBRIGATÓRIO)

## 📌 REGRA GLOBAL

Todas as páginas administrativas do sistema-escolar DEVEM possuir versão otimizada para impressão.

Isso inclui obrigatoriamente:

* Boletins
* Relatórios
* Fichas de matrícula
* Cardápios
* Qualquer tela com dados institucionais

Se não houver versão de impressão → implementação incompleta

---

## 📄 PADRÃO DE IMPRESSÃO

### ✔ Formato

* Papel A4
* Orientação:

  * Retrato (padrão)
  * Paisagem (quando necessário, ex: tabelas grandes)

---

### ✔ Estrutura obrigatória

A versão de impressão DEVE conter:

1. Cabeçalho institucional:

   * Brasão do município
   * Nome da prefeitura
   * Nome da secretaria
   * Nome da escola (quando aplicável)

2. Título do documento

3. Área de conteúdo organizada

4. Rodapé opcional com:

   * Data de geração
   * Assinatura/responsável

---

## 🎨 COMPORTAMENTO VISUAL

### ✔ Na impressão:

* Fundo branco obrigatório
* Texto preto ou cinza escuro
* Remover sombras, efeitos e cores fortes
* Layout limpo e legível

---

## 🚫 ELEMENTOS QUE NÃO DEVEM APARECER

Devem ser ocultados na impressão:

* Botões
* Menus laterais
* Topbar de navegação
* Inputs interativos
* Elementos decorativos

---

## 🧩 CLASSES OBRIGATÓRIAS

Devem existir no sistema:

```css
.no-print { display: none !important; }

.print-only { display: none; }

@media print {
  .print-only { display: block; }
}
```

---

## ⚙️ USO COM TAILWIND

Preferir uso de utilitários:

* print:hidden
* print:block
* print:text-black
* print:bg-white

---

## 📐 LAYOUT

* Evitar quebra de tabelas no meio
* Usar:

  * break-inside: avoid;
  * page-break-inside: avoid;

---

# ♿ ACESSIBILIDADE (NÍVEL BÁSICO - OBRIGATÓRIO)

## 📌 REGRA GLOBAL

Toda interface deve atender requisitos mínimos de acessibilidade.

Se não atender → implementação incompleta

---

## ✔ FORMULÁRIOS

* Todo input DEVE possuir label associado
* Placeholder NÃO substitui label

---

## ✔ FOCO

* Elementos interativos devem possuir foco visível
* Navegação por teclado deve ser possível

---

## ✔ CONTRASTE

* Texto deve ser legível
* Evitar combinações com baixo contraste

---

## ✔ BOTÕES

* Devem possuir texto claro
* Não depender apenas de cor para significado

---

# 🌙 DARK MODE (ADIADO)

## 📌 REGRA GLOBAL

Dark mode NÃO deve ser implementado neste momento.

---

## ✔ MOTIVO

* Aumenta complexidade do sistema
* Não é prioridade para ambiente administrativo
* Pode ser implementado futuramente com planejamento adequado

---

## 🚫 PROIBIDO

* Não criar variações de tema escuro
* Não adicionar classes condicionais para dark mode

---

# 🚨 VALIDAÇÃO FINAL ADICIONAL

Antes de entregar qualquer página, o agente deve verificar:

* A página possui versão de impressão funcional?
* Elementos desnecessários são ocultados no print?
* Acessibilidade básica foi aplicada?
* Não há implementação de dark mode?

Se alguma resposta for NÃO → corrigir antes de entregar
