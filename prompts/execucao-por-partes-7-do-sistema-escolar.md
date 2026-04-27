

🧠 ESTRATÉGIA

Você vai rodar em sequência:

Parte 1 — Leitura + Arquitetura
Parte 2 — Controllers + Services + Models
Parte 3 — Banco + Multitenancy
Parte 4 — Segurança + LGPD + Acesso
Parte 5 — Performance + Filas + Cache
Parte 6 — Frontend + UX + APIs
Parte 7 — Testes + Qualidade + Avaliação final


======================================================================


🔴 PARTE 1 — LEITURA + ARQUITETURA (COMECE POR ESSA)
Atue como um ARQUITETO DE SOFTWARE sênior, especialista em Laravel 12 e PHP 8.2.

⚠️ IMPORTANTE:
Esta é a PARTE 1 de uma revisão completa.
Outras partes serão executadas em seguida.
NÃO antecipe análises futuras.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CONTEXTO DO PROJETO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
- Projeto real: SUE — Sistema Unificado Educacional
- Sistema educacional municipal com múltiplos portais:
  Escolar, Nutrição, Psicologia, Secretaria, Diário Eletrônico
- Múltiplos perfis: alunos, professores, diretores, psicólogos etc
- Possível multitenancy por município

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
PASSO 1 — LEITURA OBRIGATÓRIA
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Leia TODOS os arquivos relevantes na seguinte ordem:

1. composer.json e composer.lock
2. config/
3. routes/
4. app/Providers/
5. app/Http/Middleware/

Liste os arquivos lidos:

📂 ARQUIVOS LIDOS
- [lista organizada]

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
PASSO 2 — ARQUITETURA GERAL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Analise:

- Estrutura de pastas
- Organização por módulos/portais
- Separação de responsabilidades
- Uso do container e Service Providers
- Consistência entre portais
- Acoplamento entre camadas
- Possível necessidade de modularização

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FORMATO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

📂 ARQUIVOS LIDOS

✅ PONTOS POSITIVOS

❌ PROBLEMAS ENCONTRADOS

⚠️ MELHORIAS RECOMENDADAS

📌 PRIORIDADES


=====================================================================


🟠 PARTE 2 — CONTROLLERS + SERVICES + MODELS
Atue como arquiteto sênior.

⚠️ Esta é a PARTE 2 da revisão.
Considere que a arquitetura já foi analisada anteriormente.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ESCOPO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

- app/Http/Controllers/
- app/Services/ ou Actions
- app/Models/
- app/Http/Requests/

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ANÁLISE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1️⃣ CONTROLLERS
- Controllers estão finos?
- Há regra de negócio indevida?
- Uso correto de Form Requests?

2️⃣ SERVICES / ACTIONS
- Regra de negócio centralizada?
- Código duplicado entre portais?
- Métodos muito grandes?

3️⃣ MODELS / ELOQUENT
- Relacionamentos corretos?
- Uso de casts, scopes
- N+1 queries
- Lógica indevida no model

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FORMATO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✅ PONTOS POSITIVOS

❌ PROBLEMAS ENCONTRADOS

🔧 REFATORAÇÕES SUGERIDAS

📌 PRIORIDADES


=======================================================================


🟡 PARTE 3 — BANCO + MULTITENANCY
PARTE 3 da revisão.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ESCOPO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

- database/migrations/
- Models relacionados

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ANÁLISE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1️⃣ BANCO DE DADOS
- Tipos de coluna
- Índices
- Foreign keys
- Nullables indevidos
- Crescimento de tabelas críticas

2️⃣ CONSISTÊNCIA
- Padronização de nomes (id_aluno vs aluno_id)
- Estrutura coerente entre tabelas

3️⃣ MULTITENANCY
- Isolamento por município/escola
- Risco de vazamento de dados
- Uso de escopos globais

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FORMATO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

❌ PROBLEMAS ENCONTRADOS

⚠️ MELHORIAS

📌 PRIORIDADES


================================================================


🔵 PARTE 4 — SEGURANÇA + LGPD + ACESSO
PARTE 4 da revisão.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ANÁLISE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1️⃣ SEGURANÇA
- SQL Injection
- Mass Assignment
- Validação de inputs

2️⃣ AUTORIZAÇÃO
- Policies e Gates
- Middleware
- Separação por perfil

3️⃣ LGPD
- Dados sensíveis:
  alunos, saúde, psicologia
- Controle de acesso adequado
- Risco de exposição

4️⃣ AUTENTICAÇÃO
- Guards
- Sessão/token
- Expiração

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FORMATO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

❌ PROBLEMAS CRÍTICOS

⚠️ RISCOS LGPD

📌 PRIORIDADES (FOCO EM SEGURANÇA)


=====================================================================


🟣 PARTE 5 — PERFORMANCE + FILAS + CACHE
PARTE 5 da revisão.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ANÁLISE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1️⃣ PERFORMANCE
- Queries pesadas
- N+1
- Falta de paginação

2️⃣ CACHE
- Uso ou ausência
- Pontos críticos (alunos, turmas, calendário)

3️⃣ FILAS
- Jobs síncronos
- Uso de queue
- Driver (redis, database)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FORMATO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

❌ PROBLEMAS

⚠️ GARGALOS

📌 PRIORIDADES


=================================================================


🟢 PARTE 6 — FRONTEND + UX + APIs
PARTE 6 da revisão.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ANÁLISE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1️⃣ BLADE / FRONTEND
- Reutilização de componentes
- Lógica indevida nas views
- Duplicação

2️⃣ UX
- Fluxos críticos:
  notas, frequência, matrícula

3️⃣ APIs
- Padronização JSON
- Resources
- Versionamento
- Autenticação (Sanctum/JWT)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FORMATO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

⚠️ PROBLEMAS UX

❌ PROBLEMAS TÉCNICOS

📌 PRIORIDADES


=================================================================

⚫ PARTE 7 — TESTES + QUALIDADE + AVALIAÇÃO FINAL
PARTE FINAL da revisão.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ANÁLISE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1️⃣ TESTES
- Cobertura
- Fluxos críticos testados?

2️⃣ QUALIDADE PHP 8.2
- Tipagem
- Enums
- match
- PSR-12

3️⃣ DOCUMENTAÇÃO
- README
- Onboarding

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
AVALIAÇÃO FINAL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

📊 NOTA (0 a 10):
- Arquitetura:
- Segurança:
- Performance:
- Código:
- Testes:

📌 PRIORIDADES GERAIS

🏗️ CONCLUSÃO FINAL