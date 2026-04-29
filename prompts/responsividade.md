Atue como um Desenvolvedor Full Stack Sênior e UI/UX Designer especialista em sistemas educacionais.

Preciso ajustar a estratégia responsiva dos portais do meu sistema **SUE — Sistema Unificado Educacional**, especificamente dentro do **sistema-escolar**.

## Portais existentes

No sistema-escolar, os portais são:

1. Portal da Secretaria de Educação
2. Portal da Secretaria Escolar
3. Portal da Coordenação Pedagógica
4. Portal da Direção Escolar
5. Portal do Professor
6. Portal da Nutricionista
7. Portal da Psicologia/Psicopedagogia

---

## Objetivo principal

Configurar os portais com estratégias responsivas adequadas ao tipo de uso de cada perfil.

A regra será:

### Desktop-first, mas responsivos para mobile

Os portais abaixo devem seguir abordagem **desktop-first**, pois são portais administrativos, de gestão, relatórios, filtros, tabelas, dashboards e formulários mais complexos:

- Portal da Secretaria de Educação
- Portal da Secretaria Escolar
- Portal da Coordenação Pedagógica
- Portal da Direção Escolar
- Portal da Nutricionista
- Portal da Psicologia/Psicopedagogia

Esses portais devem ser pensados primeiro para notebook/desktop, **mas obrigatoriamente devem funcionar bem em celulares**.

### Mobile-first

O portal abaixo deve seguir abordagem **mobile-first**:

- Portal do Professor

Esse portal deve ser pensado primeiro para celular, pois o professor pode usar o sistema em sala de aula para chamada, lançamento de frequência, notas, registros e consultas rápidas.

---

## Regra obrigatória para todos os portais

Todos os portais devem ser **responsivos para mobile**.

Nenhum portal pode ficar quebrado, inutilizável ou dependente exclusivamente de tela grande.

Mesmo os portais desktop-first devem ter boa experiência em:

- celular pequeno;
- celular médio;
- tablet;
- notebook;
- desktop.

---

## Antes de alterar qualquer arquivo

Analise a estrutura atual do projeto e identifique:

- onde ficam os layouts base;
- onde ficam os arquivos CSS globais;
- onde ficam os CSS específicos de cada portal;
- onde ficam os componentes compartilhados;
- se há uso de Bootstrap, Tailwind, CSS puro ou outro framework;
- se os portais compartilham o mesmo layout;
- se cada portal possui classe raiz, wrapper ou arquivo de layout próprio.

Não quebre funcionalidades existentes.

Não altere regras de negócio.

Não altere consultas SQL.

Não altere permissões, login, sessão ou autenticação.

Não remova classes, scripts ou componentes sem verificar onde são usados.

Evite alterar CSS global de forma que afete todos os portais sem controle.

Sempre que possível, use escopo por portal.

---

## Classes raiz recomendadas

Se ainda não existirem, adicione ou padronize uma classe raiz no `body` ou no wrapper principal de cada portal:

```html
<body class="portal-secretaria-educacao">
<body class="portal-secretaria-escolar">
<body class="portal-coordenacao-pedagogica">
<body class="portal-direcao-escolar">
<body class="portal-professor">
<body class="portal-nutricionista">
<body class="portal-psicologia">