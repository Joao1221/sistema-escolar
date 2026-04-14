────────────────────
1️⃣4️⃣ FRONT-END / BLADE / UX
────────────────────
- Analise resources/views e avalie:
  - Reutilização de layouts e componentes Blade
  - Duplicação de código em views
  - Separação entre lógica e apresentação
- Avalie UX dos fluxos críticos:
  - Lançamento de notas (professor)
  - Lançamento de frequência
  - Matrícula e transferência de alunos
- Verifique:
  - Uso excessivo de lógica PHP dentro das views
  - Falta de componentes reutilizáveis
- Avalie acessibilidade básica e responsividade

────────────────────
1️⃣5️⃣ APIs E INTEGRAÇÕES
────────────────────
- Analise rotas em api.php e controllers de API:
  - Padronização de respostas (JSON consistente)
  - Uso de Resources
  - Versionamento de API
- Verifique autenticação:
  - Sanctum, Passport ou JWT
- Avalie possibilidade de integração com sistemas externos
  (INEP, MEC, sistemas municipais)

────────────────────
1️⃣6️⃣ LOGS E AUDITORIA
────────────────────
- Verifique se há logging estruturado:
  - Ações críticas (alteração de notas, frequência, matrícula)
- Avalie:
  - Rastreabilidade (quem fez o quê e quando)
  - Logs de erro e exceções
- Identifique ausência de auditoria em operações críticas

────────────────────
1️⃣7️⃣ FILAS E PROCESSAMENTO ASSÍNCRONO
────────────────────
- Verifique uso de queues:
  - Geração de relatórios
  - Processos pesados
- Avalie:
  - Driver configurado (redis, database, etc.)
  - Estratégias de retry e falha
- Identifique tarefas que deveriam ser assíncronas mas estão síncronas

────────────────────
1️⃣8️⃣ MULTITENANCY E ISOLAMENTO DE DADOS
────────────────────
- Verifique se há isolamento de dados por município/escola:
  - Uso de tenant_id, id_escola ou equivalente
- Avalie risco de vazamento de dados entre tenants
- Verifique uso de global scopes ou filtros obrigatórios
- Analise queries que podem ignorar o escopo do tenant

────────────────────
1️⃣9️⃣ PADRÃO DE DOMÍNIO E ORGANIZAÇÃO
────────────────────
- Avalie se o sistema está organizado por domínio:
  - Aluno, Matrícula, Diário, Nutrição, Psicologia
- Verifique:
  - Baixo acoplamento entre domínios
  - Possibilidade de modularização futura
- Identifique mistura de responsabilidades entre portais

────────────────────
2️⃣0️⃣ DOCUMENTAÇÃO E MANUTENIBILIDADE
────────────────────
- Verifique existência e qualidade de:
  - README.md
  - Documentação de instalação
  - Documentação de API
- Avalie facilidade de onboarding de novos desenvolvedores

────────────────────
2️⃣1️⃣ CONSISTÊNCIA DO BANCO DE DADOS
────────────────────
- Verifique padronização:
  - nomes de tabelas (plural/singular)
  - nomes de colunas (snake_case consistente)
  - chaves estrangeiras (id_aluno vs aluno_id)
- Identifique inconsistências entre tabelas relacionadas

────────────────────
2️⃣2️⃣ VALIDAÇÃO DE FLUXOS CRÍTICOS
────────────────────
- Avalie o funcionamento completo dos fluxos:
  - Matrícula → Turma → Notas → Boletim
  - Frequência → Cálculo de presença
- Verifique inconsistências entre etapas
- Identifique possíveis falhas lógicas no fluxo do sistema