# Sistema Escolar — Prompts em 24 Etapas

## Prompt-mestre de alinhamento

```text
Vamos construir este sistema educacional em etapas pequenas, cumulativas e testáveis.

Você deve considerar desde já que este projeto será um Sistema Educacional completo para Rede Pública Municipal de Ensino, com múltiplos portais e módulos, incluindo:

- Portal da Secretaria de Educação
- Portal da Secretaria Escolar
- Portal da Direção Escolar
- Portal da Coordenação Pedagógica
- Portal do Professor
- Portal da Nutricionista
- Portal da Psicologia/Psicopedagogia

O sistema final terá, entre outros, os seguintes módulos:
- cadastro institucional da rede;
- gestão da Secretaria Municipal de Educação;
- gestão das escolas;
- usuários, perfis e permissões;
- funcionários;
- alunos;
- matrículas, rematrículas, transferências e enturmação;
- histórico escolar;
- diário eletrônico;
- planejamento pedagógico;
- frequência;
- notas e conceitos;
- AEE;
- alimentação escolar;
- atendimento psicológico e psicopedagógico;
- documentos escolares;
- relatórios;
- auditoria;
- uploads e anexos.

## Regras permanentes para todas as próximas entregas
1. Não tente construir o sistema inteiro de uma vez.
2. Trabalhe apenas na etapa que eu pedir.
3. Não misture funcionalidades de etapas futuras.
4. Não reestruture o que já foi feito sem necessidade real.
5. A arquitetura deve nascer preparada para expansão para todos os módulos e portais acima, mas a implementação deve respeitar apenas o escopo da etapa atual.
6. Use obrigatoriamente:
   - Laravel 11
   - PHP 8.3
   - MySQL/MariaDB
   - Blade
   - Tailwind
   - Spatie Laravel Permission
   - Form Requests
   - Policies
   - Migrations
   - Seeders
   - Factories
   - PHPUnit
7. Use nomes em português do Brasil para:
   - banco de dados
   - tabelas
   - colunas
   - classes
   - métodos
   - variáveis
   - controllers
   - services
   - requests
   - policies
   - views Blade
   - componentes
   - rotas nomeadas
   - arquivos, quando viável
8. Use outro idioma apenas quando for inevitável por convenções internas do Laravel ou bibliotecas.
9. Não use pseudocódigo.
10. Não diga “omiti por brevidade”, “continue o padrão” ou frases equivalentes.
11. Sempre informe o caminho completo dos arquivos criados/alterados.
12. Toda regra importante deve ser validada no backend.
13. Use transações nas operações críticas.
14. Use índices, foreign keys e unique constraints corretamente.
15. Não misture dados permanentes do aluno com dados anuais da matrícula.
16. Não sobrescreva histórico.
17. Não trate AEE como simples booleano.
18. Não trate AEE como simples complemento informal.
19. O AEE deve ser modelado como matrícula própria.
20. Regular + AEE contam como duas matrículas.
21. O sistema deve permitir também somente matrícula AEE.
22. O sistema não pode ser um dashboard genérico único.
23. O sistema não pode usar apenas um menu genérico com ocultação de itens por perfil.
24. Cada portal deve ter layout, navegação e fluxos próprios quando chegar sua vez de implementação.
25. Ao final de cada etapa:
   - liste os arquivos criados/alterados;
   - explique o que foi feito;
   - explique como testar manualmente;
   - informe pendências, se houver.
26. Não avance para a próxima etapa sem concluir totalmente a etapa atual.

A partir de agora, execute apenas a etapa que eu solicitar.
```

## Visão resumida do projeto

```text
Contexto global do projeto:

Este sistema educacional deve suportar:
- Educação Infantil
- Ensino Fundamental
- EJA
- AEE

## Regra de matrícula
- matrícula regular = 1 matrícula
- matrícula AEE = 1 matrícula distinta
- regular + AEE = matrícula dupla
- o sistema deve permitir:
  1. somente matrícula regular
  2. matrícula regular + matrícula AEE
  3. somente matrícula AEE

## Regras pedagógicas principais
- Infantil usa conceitos configuráveis
- Fundamental usa notas com estrutura avaliativa configurável
- EJA possui regra semestral própria
- disciplinas podem variar por modalidade, série, turma e escola

## Dados institucionais
O sistema precisa armazenar:
- nome da prefeitura
- CNPJ da prefeitura
- nome do prefeito
- nome da secretaria de educação
- sigla da secretaria
- nome do secretário de educação
- endereço institucional
- telefone
- email
- município
- UF
- CEP
- brasão
- logomarca da prefeitura
- logomarca da secretaria
- textos institucionais
- assinaturas e cargos

## Portais futuros
- Secretaria de Educação
- Secretaria Escolar
- Direção Escolar
- Coordenação Pedagógica
- Professor
- Nutricionista
- Psicologia/Psicopedagogia

## Regra arquitetural
Mesmo sabendo que o sistema completo terá todos esses módulos e portais, implemente apenas o escopo da etapa atual.
```

## Etapa 1 — Fundação técnica

```text
Execute apenas a ETAPA 1 do projeto.

CONTEXTO:
Este sistema educacional terá vários portais e módulos no futuro, mas nesta etapa quero apenas a fundação técnica do projeto.

OBJETIVO:
Criar a base técnica mínima, estável e organizada, pronta para receber os módulos futuros.

ESCOPO DESTA ETAPA:
1. Configurar a base do Laravel 11 com PHP 8.3.
2. Configurar MySQL/MariaDB.
3. Configurar Tailwind.
4. Configurar autenticação.
5. Configurar Spatie Laravel Permission.
6. Criar estrutura inicial do banco para:
   - usuarios
   - perfis/permissoes
   - escolas
   - usuarios_escolas
7. Criar seeders iniciais:
   - perfil administrador da rede
   - usuário administrador inicial
8. Criar layout base inicial.
9. Criar tela de login funcional.
10. Criar tela inicial autenticada simples, apenas para validar autenticação e permissões.
11. Criar middleware e policies básicas para controle inicial de acesso.

REGRAS:
- Não criar ainda módulos pedagógicos.
- Não criar ainda aluno, matrícula, diário, documentos ou relatórios complexos.
- Não criar ainda dashboards específicos dos portais.
- Não criar ainda telas completas dos módulos futuros.
- Foque em uma base sólida e funcional.
- Use nomes em português do Brasil.
- Use Blade + Tailwind.

ENTREGÁVEIS:
- migrations iniciais
- models iniciais
- autenticação configurada
- permissões/perfis configurados
- seeders
- layout base inicial
- tela de login
- tela inicial autenticada simples
- instruções de teste manual

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. rodar migrations e seeders;
2. acessar a tela de login;
3. entrar com o usuário administrador;
4. visualizar uma tela autenticada simples;
5. confirmar que o perfil do usuário está sendo reconhecido.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 2 — Gestão de usuários

```text
Execute apenas a ETAPA 2 do projeto.

CONTEXTO:
A base técnica, autenticação e permissões iniciais já foram criadas.
Agora quero o primeiro módulo funcional completo e testável.

OBJETIVO:
Criar o módulo de gestão de usuários.

ESCOPO DESTA ETAPA:
1. Criar listagem de usuários.
2. Criar cadastro de usuário.
3. Criar edição de usuário.
4. Criar ativação/inativação.
5. Vincular usuário a perfil.
6. Permitir vínculo com uma ou mais escolas, quando aplicável.
7. Criar validações com Form Requests.
8. Criar permissões por ação:
   - visualizar usuários
   - criar usuário
   - editar usuário
   - ativar/inativar usuário
9. Criar telas Blade + Tailwind do módulo.
10. Criar rotas web.
11. Criar controller, requests, service e views necessários.
12. Criar testes básicos do módulo.

REGRAS:
- Não criar ainda outros módulos grandes.
- Não criar ainda gestão institucional da rede.
- Não criar ainda aluno, matrícula, diário, documentos ou relatórios complexos.
- Não mexer sem necessidade no que já foi feito.
- Criar telas reais, simples e funcionais.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations complementares, se necessário
- models e relacionamentos necessários
- controller
- requests
- service
- views
- rotas
- permissões
- testes básicos
- instruções de teste manual

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. listar usuários;
2. cadastrar novo usuário;
3. editar usuário;
4. ativar/inativar usuário;
5. vincular perfil;
6. testar restrição por permissão.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 3 — Dados institucionais da rede

```text
Execute apenas a ETAPA 3 do projeto.

CONTEXTO:
A base técnica e o módulo de usuários já existem.
Agora quero o módulo institucional da rede.

OBJETIVO:
Criar o módulo de dados institucionais da rede.

ESCOPO DESTA ETAPA:
1. Criar estrutura para armazenar:
   - nome da prefeitura
   - CNPJ da prefeitura
   - nome do prefeito
   - nome da secretaria de educação
   - sigla da secretaria
   - nome do secretário de educação
   - endereço
   - telefone
   - email
   - município
   - UF
   - CEP
   - brasão
   - logomarca da prefeitura
   - logomarca da secretaria
   - textos institucionais
   - assinaturas e cargos
2. Criar tela de edição desses dados.
3. Criar tela de visualização institucional.
4. Criar validações.
5. Criar upload de imagens institucionais.
6. Restringir o acesso por permissão.
7. Preparar esse módulo para uso futuro em documentos oficiais.

REGRAS:
- Não criar ainda geração completa de documentos.
- Não criar ainda módulos pedagógicos.
- Não criar ainda relatórios complexos.
- Criar telas reais e funcionais.
- Usar storage adequado para as imagens.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migration
- model
- requests
- service
- controller
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. preencher os dados da prefeitura e secretaria;
2. fazer upload de logos/brasão;
3. editar e salvar;
4. visualizar os dados cadastrados.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 4 — Configurações globais da rede

```text
Execute apenas a ETAPA 4 do projeto.

CONTEXTO:
A base técnica, autenticação, usuários e dados institucionais já existem.

OBJETIVO:
Criar o módulo de configurações globais da rede.

ESCOPO DESTA ETAPA:
1. Criar configuração do ano letivo.
2. Criar configuração da quantidade mínima de dias letivos.
3. Criar configuração das modalidades de ensino.
4. Criar configuração da estrutura avaliativa por modalidade.
5. Criar configuração de média mínima.
6. Criar configuração de carga horária de disciplinas.
7. Criar configuração de frequência mínima.
8. Criar configuração de parâmetros de documentos.
9. Criar configuração de parâmetros de upload.
10. Criar tela de gestão dessas configurações.
11. Criar validações e regras de negócio.
12. Preparar essas configurações para uso futuro pelos módulos do sistema.

REGRAS:
- Não criar ainda diário, notas, aluno ou matrícula.
- Não criar ainda geração documental completa.
- Não criar ainda os demais portais.
- Criar primeiro a base global da rede.
- Usar nomes em português do Brasil.
- Criar telas reais e funcionais.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- seeders básicos, se necessário
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. definir ano letivo;
2. definir dias letivos;
3. definir modalidades;
4. definir média mínima;
5. definir estrutura avaliativa inicial;
6. definir parâmetros globais da rede;
7. salvar e editar esses parâmetros.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 5 — Escolas

```text
Execute apenas a ETAPA 5 do projeto.

CONTEXTO:
A base técnica, usuários, dados institucionais e configurações globais já existem.

OBJETIVO:
Criar o módulo de escolas.

ESCOPO DESTA ETAPA:
1. Criar cadastro de escolas.
2. Criar edição de escolas.
3. Criar ativação/inativação.
4. Criar listagem com filtros.
5. Criar vínculo de dados básicos da gestora da escola.
6. Criar permissões por ação.
7. Criar telas Blade + Tailwind reais e funcionais.

REGRAS:
- Não criar ainda alunos, turmas ou matrículas.
- Não criar ainda os portais completos.
- Não reestruturar os módulos anteriores sem necessidade.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations complementares, se necessário
- model
- request
- service
- controller
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. cadastrar escola;
2. editar escola;
3. ativar/inativar;
4. filtrar escolas;
5. visualizar dados da escola e da gestora.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 6 — Funcionários

```text
Execute apenas a ETAPA 6 do projeto.

CONTEXTO:
A base do sistema e o módulo de escolas já existem.

OBJETIVO:
Criar o módulo de funcionários.

ESCOPO DESTA ETAPA:
1. Criar cadastro de funcionários.
2. Criar edição.
3. Criar vínculo com uma ou mais escolas.
4. Criar cargo/função.
5. Permitir identificar professor, gestora, coordenadora, secretaria escolar, nutricionista e outros perfis funcionais.
6. Criar listagem e filtros.
7. Criar permissões do módulo.
8. Criar telas Blade + Tailwind reais.

REGRAS:
- Não criar ainda aluno, matrícula, diário ou documentos escolares.
- Não criar ainda o portal do professor.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations, se necessário
- model
- request
- service
- controller
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. cadastrar funcionário;
2. editar funcionário;
3. vincular a escola;
4. definir cargo/função;
5. listar e filtrar funcionários.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 7 — Portal da Secretaria de Educação

```text
Execute apenas a ETAPA 7 do projeto.

CONTEXTO:
Os módulos base já existem: autenticação, usuários, dados institucionais, configurações globais, escolas e funcionários.

OBJETIVO:
Criar o primeiro portal real do sistema: o Portal da Secretaria de Educação.

ESCOPO DESTA ETAPA:
1. Criar layout próprio do portal da Secretaria de Educação.
2. Criar menu lateral próprio.
3. Criar dashboard próprio.
4. Integrar no portal os módulos já existentes:
   - dados institucionais
   - configurações globais
   - escolas
   - usuários
   - funcionários
5. Criar navegação própria deste portal.
6. Aplicar permissões adequadas.
7. Criar breadcrumbs e estrutura visual coerente.

REGRAS:
- Não criar ainda os demais portais.
- Não criar ainda aluno, matrícula, diário ou relatórios complexos.
- Não usar dashboard genérico único.
- O portal deve ter identidade própria.
- Usar Blade + Tailwind.

ENTREGÁVEIS:
- layout do portal
- menu lateral
- dashboard inicial
- views integradas
- rotas organizadas
- permissões aplicadas
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. entrar no portal da Secretaria de Educação;
2. ver layout e menu próprios;
3. acessar os módulos já existentes por esse portal;
4. confirmar restrições de acesso por perfil/permissão.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 8 — Alunos

```text
Execute apenas a ETAPA 8 do projeto.

CONTEXTO:
A base do sistema e o Portal da Secretaria de Educação já existem.

OBJETIVO:
Criar o módulo completo de alunos.

ESCOPO DESTA ETAPA:
1. Criar cadastro do aluno.
2. Criar edição do aluno.
3. Criar dados pessoais.
4. Criar endereço do aluno.
5. Criar saúde do aluno.
6. Criar documentos do aluno.
7. Criar tela de detalhe do aluno.
8. Criar listagem com filtros.
9. Criar permissões do módulo.
10. Criar telas Blade + Tailwind reais e completas.

REGRAS:
- Não criar ainda matrícula.
- Não criar ainda histórico escolar completo.
- Não criar ainda diário.
- Não misturar dados de matrícula na tabela principal do aluno.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. cadastrar aluno;
2. editar aluno;
3. registrar endereço, saúde e documentos;
4. ver tela de detalhe;
5. listar e filtrar alunos.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 9 — Turmas

```text
Execute apenas a ETAPA 9 do projeto.

CONTEXTO:
O módulo de alunos já existe.
Agora quero criar o módulo de turmas.

REGRA ARQUITETURAL IMPORTANTE:
As turmas pertencem operacionalmente ao Portal da Secretaria Escolar.
Portanto, as ações de cadastrar, editar e gerenciar turmas devem ser implementadas dentro do Portal da Secretaria Escolar.

O Portal da Secretaria de Educação deve possuir apenas consulta de turmas, com:
- listagem;
- filtros;
- visualização de detalhes;
- sem permissão para cadastrar;
- sem permissão para editar;
- sem permissão para excluir.

OBJETIVO:
Criar o módulo de turmas com separação correta por portal e permissão.

ESCOPO DESTA ETAPA:
1. Criar cadastro de turmas no Portal da Secretaria Escolar.
2. Criar edição de turmas no Portal da Secretaria Escolar.
3. Permitir definir:
   - escola
   - modalidade
   - etapa/série
   - turma
   - turno
   - ano letivo
   - multisseriada, quando aplicável
4. Criar listagem e filtros no Portal da Secretaria Escolar.
5. Criar visualização/listagem de turmas no Portal da Secretaria de Educação, apenas para consulta.
6. Criar permissões separadas por ação, no mínimo:
   - consultar turmas
   - cadastrar turmas
   - editar turmas
   - visualizar detalhes da turma
7. Criar telas reais e funcionais em Blade + Tailwind.
8. Garantir que:
   - Secretaria Escolar gerencia turmas;
   - Secretaria de Educação apenas consulta.

REGRAS:
- Não criar ainda matrícula.
- Não criar ainda diário.
- Não criar ainda horários completos.
- Preparar estrutura para futura expansão.
- Usar nomes em português do Brasil.
- Não usar painel genérico único.
- Respeitar a separação entre portais.
- Não colocar criação/edição de turmas dentro do Portal da Secretaria de Educação.
- Criar navegação correta por portal.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. cadastrar turma no Portal da Secretaria Escolar;
2. editar turma no Portal da Secretaria Escolar;
3. listar e filtrar turmas no Portal da Secretaria Escolar;
4. vincular turma à escola e modalidade;
5. consultar turmas no Portal da Secretaria de Educação, sem conseguir cadastrar ou editar;
6. validar que as permissões estão separadas corretamente entre os dois portais.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 10 — Matrículas

```text
Execute apenas a ETAPA 10 do projeto.

CONTEXTO:
Já existem alunos e turmas.
Agora quero criar o módulo de matrículas.

REGRA ARQUITETURAL IMPORTANTE:
As matrículas pertencem operacionalmente ao Portal da Secretaria Escolar.
Portanto, as ações de cadastrar, editar, rematricular, transferir, enturmar e consultar histórico de matrícula devem ser implementadas dentro do Portal da Secretaria Escolar.

O Portal da Secretaria de Educação deve possuir apenas consulta de matrículas, com:
- listagem;
- filtros;
- visualização de detalhes;
- visualização de histórico;
- sem permissão para cadastrar;
- sem permissão para editar;
- sem permissão para rematricular;
- sem permissão para transferir;
- sem permissão para enturmar.

OBJETIVO:
Criar o módulo de matrículas com separação correta por portal e permissão, com suporte a:
- matrícula regular;
- matrícula AEE;
- matrícula dupla;
- matrícula somente AEE.

ESCOPO DESTA ETAPA:
1. Criar estrutura de matrícula como entidade própria.
2. Criar matrícula regular no Portal da Secretaria Escolar.
3. Criar matrícula AEE como matrícula própria no Portal da Secretaria Escolar.
4. Permitir vínculo opcional entre matrícula AEE e matrícula regular.
5. Permitir os cenários:
   - somente matrícula regular
   - matrícula regular + matrícula AEE
   - somente matrícula AEE
6. Tratar regular e AEE como matrículas distintas.
7. Contabilizar regular e AEE separadamente.
8. Criar rematrícula no Portal da Secretaria Escolar.
9. Criar transferência no Portal da Secretaria Escolar.
10. Criar enturmação no Portal da Secretaria Escolar.
11. Criar histórico de matrícula.
12. Criar visualização/listagem de matrículas no Portal da Secretaria de Educação, apenas para consulta.
13. Criar permissões separadas por ação, no mínimo:
   - consultar matrículas
   - visualizar detalhes da matrícula
   - cadastrar matrícula
   - editar matrícula, se aplicável
   - rematricular
   - transferir
   - enturmar
   - consultar histórico de matrícula
14. Criar telas Blade + Tailwind reais e funcionais.

REGRAS:
- Não criar ainda histórico escolar completo.
- Não criar ainda diário do professor.
- Não misturar dados permanentes do aluno com dados da matrícula.
- Não tratar AEE como simples complemento informal.
- Tratar o AEE como matrícula própria.
- Respeitar a regra de matrícula dupla quando houver regular + AEE.
- Permitir matrícula somente AEE.
- Usar nomes em português do Brasil.
- Não usar painel genérico único.
- Respeitar a separação entre portais.
- Não colocar criação/edição de matrículas dentro do Portal da Secretaria de Educação.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. criar matrícula regular no Portal da Secretaria Escolar;
2. criar matrícula AEE no Portal da Secretaria Escolar;
3. criar matrícula dupla no Portal da Secretaria Escolar;
4. criar matrícula somente AEE no Portal da Secretaria Escolar;
5. rematricular;
6. transferir;
7. enturmar;
8. ver histórico de matrícula;
9. consultar matrículas no Portal da Secretaria de Educação, sem conseguir cadastrar, editar, rematricular, transferir ou enturmar;
10. validar que as permissões estão separadas corretamente entre os dois portais.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 11 — Portal da Secretaria Escolar

```text
Execute apenas a ETAPA 11 do projeto.

CONTEXTO:
Já existem os módulos de alunos, turmas, matrículas e horários.
Agora quero consolidar o Portal da Secretaria Escolar como área de trabalho real da escola.

OBJETIVO:
Criar o Portal da Secretaria Escolar com layout, navegação, dashboard e integração dos módulos que pertencem à rotina administrativa e operacional da escola.

ESCOPO DESTA ETAPA:
1. Criar layout próprio do Portal da Secretaria Escolar.
2. Criar menu lateral próprio.
3. Criar dashboard próprio da secretaria escolar.
4. Integrar neste portal os módulos já existentes e que pertencem à rotina da escola:
   - alunos
   - turmas
   - matrículas
   - horários
5. Organizar a navegação da secretaria escolar.
6. Criar breadcrumbs e estrutura visual coerente.
7. Criar permissões corretas para:
   - consulta
   - cadastro
   - edição
   - operações administrativas dos módulos autorizados
8. Garantir que este portal seja operacionalmente separado do Portal da Secretaria de Educação.
9. Preparar espaço no menu para futuras entradas:
   - documentos escolares
   - alimentação escolar
   - histórico escolar
   - relatórios administrativos escolares
10. Preparar também a navegação para visualização futura de notas/conceitos, deixando claro que:
   - Secretaria Escolar poderá visualizar notas/conceitos;
   - Secretaria Escolar não poderá alterar notas/conceitos.
11. Garantir que a Secretaria Escolar possa operar horários no contexto escolar, conforme permissões definidas.
12. Garantir que a Secretaria Escolar não tenha acesso a módulos estritamente pedagógicos que não sejam do seu escopo operacional, salvo consulta quando definido.

REGRAS:
- Não criar ainda documentos escolares completos.
- Não criar ainda alimentação escolar completa.
- Não criar ainda histórico escolar completo.
- Não criar ainda diário do professor.
- Não criar ainda edição de notas/conceitos pela Secretaria Escolar.
- Não usar dashboard genérico único.
- O portal deve ter identidade própria.
- Usar Blade + Tailwind.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- layout do Portal da Secretaria Escolar
- menu lateral
- dashboard inicial
- views integradas
- rotas organizadas
- permissões aplicadas
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. entrar no Portal da Secretaria Escolar;
2. ver layout e menu próprios;
3. acessar os módulos de alunos, turmas, matrículas e horários por esse portal;
4. confirmar que a Secretaria Escolar consegue operar esses módulos;
5. confirmar que a Secretaria Escolar está preparada para futuramente visualizar notas/conceitos, mas sem alterar;
6. confirmar que a Secretaria de Educação continua apenas com consulta onde definido;
7. validar que esse portal está realmente separado dos demais.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 12 — Disciplinas e matriz curricular

```text
Execute apenas a ETAPA 12 do projeto.

CONTEXTO:
Já existem base técnica, escolas, funcionários, alunos, turmas, matrículas e o Portal da Secretaria Escolar.
Agora quero criar a base curricular do sistema.

OBJETIVO:
Criar o módulo de disciplinas e matriz curricular.

ESCOPO DESTA ETAPA:
1. Criar cadastro de disciplinas.
2. Criar edição de disciplinas.
3. Permitir definir:
   - nome da disciplina
   - código, se aplicável
   - carga horária
   - modalidade de ensino
   - etapa/série/fase
   - obrigatoriedade
   - situação ativa/inativa
4. Criar estrutura de matriz curricular.
5. Permitir vincular disciplinas por:
   - modalidade
   - etapa/série/fase
   - turma, quando necessário
   - escola, quando necessário
6. Preparar a estrutura para:
   - Educação Infantil
   - Ensino Fundamental
   - EJA
   - AEE, quando aplicável
7. Criar listagens e filtros.
8. Criar permissões por ação.
9. Criar telas Blade + Tailwind reais e funcionais.
10. Permitir consulta pelo Portal da Secretaria de Educação.
11. Permitir gestão curricular pelos perfis autorizados.

REGRAS:
- Não criar ainda diário do professor.
- Não criar ainda horários completos.
- Não criar ainda lançamento de notas.
- Não engessar disciplinas em colunas fixas.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. cadastrar disciplina;
2. editar disciplina;
3. criar matriz curricular;
4. vincular disciplinas à modalidade/série/turma;
5. listar e filtrar disciplinas e matrizes;
6. consultar pelo portal autorizado.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 13 — Horários

```text
Execute apenas a ETAPA 13 do projeto.

CONTEXTO:
As turmas e a matriz curricular já existem.
Agora quero criar o módulo de horários.

OBJETIVO:
Criar o módulo de horários de turmas e aulas, com gestão exclusiva da escola.

REGRA ARQUITETURAL IMPORTANTE:
Os horários são definidos exclusivamente pelas escolas.
Portanto:
- o módulo de horários deve pertencer ao contexto operacional da escola;
- a Secretaria de Educação não precisa ter acesso a esse módulo;
- professores devem apenas consultar seus horários e suas aulas;
- a gestão dos horários deve ficar com os perfis autorizados da escola, especialmente:
  - Secretaria Escolar
  - Direção Escolar
  - Coordenação Pedagógica

ESCOPO DESTA ETAPA:
1. Criar estrutura de horários por turma.
2. Permitir definir:
   - escola
   - turma
   - dia da semana
   - horário inicial
   - horário final
   - disciplina
   - professor responsável
   - ordem/aula
3. Permitir edição e exclusão lógica, se necessário.
4. Criar listagem e filtros por:
   - escola
   - turma
   - turno
   - professor
5. Criar permissões por ação, no mínimo:
   - consultar horários
   - cadastrar horários
   - editar horários
   - excluir/inativar horários, se aplicável
6. Garantir que:
   - Secretaria Escolar possa gerir horários, se autorizada;
   - Direção Escolar possa gerir horários, se autorizada;
   - Coordenação Pedagógica possa consultar e gerir horários, se autorizada;
   - Professor possa apenas consultar seus horários e suas aulas;
   - Secretaria de Educação não tenha acesso a esse módulo, salvo se você criar uma permissão administrativa excepcional, o que não deve ser o padrão.
7. Criar telas reais em Blade + Tailwind.
8. Preparar esse módulo para uso futuro no:
   - Portal da Secretaria Escolar
   - Portal da Direção Escolar
   - Portal da Coordenação Pedagógica
   - Portal do Professor

REGRAS:
- Não criar ainda diário do professor.
- Não criar ainda validação pedagógica.
- Não criar ainda controle de aula dada.
- Não disponibilizar esse módulo no Portal da Secretaria de Educação.
- Não criar painel genérico.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. cadastrar horário para uma turma por um perfil autorizado da escola;
2. editar horário;
3. listar e filtrar horários;
4. vincular professor e disciplina;
5. permitir que o professor consulte apenas seus horários e suas aulas;
6. confirmar que a Secretaria de Educação não acessa esse módulo no fluxo padrão;
7. validar que a gestão dos horários está restrita ao contexto da escola.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 14 — Diário do professor

```text
Execute apenas a ETAPA 14 do projeto.

CONTEXTO:
Já existem turmas, matrículas, disciplinas, matriz curricular e horários.
Agora quero criar o núcleo do diário do professor.

OBJETIVO:
Criar o módulo de diário do professor, incluindo planejamento, registro de aulas, frequência e lançamento de notas/conceitos.

ESCOPO DESTA ETAPA:
1. Criar estrutura do diário eletrônico por turma/disciplina/professor/período.
2. Criar registro de aula.
3. Criar lançamento de frequência.
4. Criar módulo de planejamento docente.
5. Permitir que o professor escolha o tipo de planejamento entre:
   - semanal
   - quinzenal
   - mensal
   - semestral
   - anual
6. O sistema deve montar dinamicamente a tela do planejamento conforme o tipo escolhido pelo professor.
7. O planejamento deve conter todos os campos pedagógicos exigidos pela rede e deve ser preparado para contemplar os campos exigidos pelo MEC/FNDE, incluindo, quando aplicável:
   - identificação da escola
   - ano letivo
   - modalidade
   - etapa/série/turma
   - componente curricular/disciplina
   - professor responsável
   - período de vigência do planejamento
   - objetivos de aprendizagem
   - habilidades/competências
   - conteúdos
   - metodologia
   - recursos didáticos
   - estratégias pedagógicas
   - instrumentos de avaliação
   - observações
   - adequações para inclusão, quando necessário
8. Criar lançamento de notas/conceitos pelo professor.
9. O sistema deve suportar:
   - notas numéricas
   - conceitos
   - regras configuráveis por modalidade
10. O lançamento de notas/conceitos deve respeitar:
   - modalidade de ensino
   - estrutura avaliativa da rede
   - turma
   - disciplina
   - período avaliativo
11. Criar observações sobre aluno.
12. Criar ocorrências.
13. Criar pendências do professor.
14. Preparar integração futura com:
   - validação da coordenação
   - validação da direção
   - liberação de prazo
   - fechamento letivo
15. Criar telas Blade + Tailwind reais e funcionais.
16. Restringir acesso do professor apenas às suas turmas e disciplinas.
17. Permitir consulta pedagógica para perfis autorizados, sem ainda construir todos os fluxos completos dos demais portais nesta etapa.

REGRAS:
- Não criar ainda fechamento letivo completo.
- Não criar ainda relatórios pedagógicos finais complexos.
- Não criar ainda histórico escolar completo.
- Não criar painel genérico.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. registrar aula;
2. lançar frequência;
3. criar planejamento anual;
4. criar planejamento semestral;
5. criar planejamento mensal;
6. criar planejamento quinzenal;
7. criar planejamento semanal;
8. ver a tela mudar dinamicamente conforme o tipo de planejamento escolhido;
9. lançar notas ou conceitos conforme a modalidade e regra configurada;
10. registrar observações;
11. registrar ocorrências;
12. confirmar que o professor vê apenas suas turmas e disciplinas.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 15 — Portal do Professor

```text
Execute apenas a ETAPA 15 do projeto.

CONTEXTO:
O núcleo do diário do professor já existe.
Agora quero consolidar o Portal do Professor.

OBJETIVO:
Criar o Portal do Professor com layout, navegação e integração completa do diário, incluindo consulta de horários e aulas, planejamentos, frequência e lançamento de notas/conceitos.

ESCOPO DESTA ETAPA:
1. Criar layout próprio do Portal do Professor.
2. Criar menu lateral próprio.
3. Criar dashboard próprio do professor.
4. Integrar neste portal:
   - minhas turmas
   - meu horário
   - minhas aulas
   - diário eletrônico
   - registrar aula
   - registrar frequência
   - lançar notas
   - lançar conceitos
   - planejamentos
   - observações
   - ocorrências
   - pendências
5. Dentro do item de planejamento, permitir acesso aos tipos:
   - semanal
   - quinzenal
   - mensal
   - semestral
   - anual
6. Permitir que o professor apenas consulte:
   - seus horários
   - suas aulas
7. Não permitir que o professor gerencie horários gerais da escola.
8. Criar breadcrumbs e navegação coerente.
9. Aplicar permissões corretas.
10. Garantir que a experiência final seja claramente diferente dos demais portais.

REGRAS:
- O professor deve apenas consultar seus horários e suas aulas.
- O professor não deve criar, editar ou excluir horários.
- O professor deve lançar aulas, frequência, notas e conceitos apenas nas turmas/disciplinas sob sua responsabilidade.
- Não criar ainda o fechamento letivo completo.
- Não criar ainda todos os relatórios finais.
- Não usar dashboard genérico único.
- Usar Blade + Tailwind.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- layout do Portal do Professor
- menu lateral
- dashboard
- views integradas
- rotas organizadas
- permissões aplicadas
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. entrar no Portal do Professor;
2. ver layout e menu próprios;
3. acessar diário, frequência, planejamentos e lançamento de notas/conceitos;
4. consultar apenas seus horários;
5. consultar apenas suas aulas;
6. confirmar que o professor não consegue alterar horários;
7. confirmar que o professor só acessa sua área;
8. confirmar separação real entre esse portal e os demais.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 16 — Coordenação Pedagógica

```text
Execute apenas a ETAPA 16 do projeto.

CONTEXTO:
O diário do professor e o Portal do Professor já existem.
Agora quero criar as funcionalidades de acompanhamento e validação da Coordenação Pedagógica.

OBJETIVO:
Criar as funcionalidades de acompanhamento pedagógico da coordenação, incluindo consulta e alteração de notas/conceitos, além de controle total sobre horários e aulas.

ESCOPO DESTA ETAPA:
1. Criar visualização do diário dos professores para a coordenação.
2. Criar validação dos planejamentos:
   - semanal
   - quinzenal
   - mensal
   - semestral
   - anual
3. Criar validação de aulas registradas.
4. Criar acompanhamento de frequência.
5. Criar consulta de notas/conceitos lançados pelos professores.
6. Permitir alteração de notas/conceitos pela coordenação, conforme permissão.
7. Criar acompanhamento de rendimento.
8. Criar acompanhamento de alunos em risco.
9. Criar pendências docentes.
10. Permitir à coordenação controle total pedagógico sobre:
   - horários
   - aulas
11. Permitir à coordenação:
   - consultar horários
   - cadastrar horários
   - editar horários
   - reorganizar horários
   - consultar aulas
   - ajustar aulas, quando a regra permitir
12. Criar permissões por ação.
13. Criar telas Blade + Tailwind reais e funcionais.
14. Preparar base para o Portal da Coordenação Pedagógica.

REGRAS:
- A coordenação deve poder consultar e alterar notas/conceitos, conforme permissão.
- A coordenação deve ter controle total pedagógico sobre horários e aulas.
- A coordenação pode ajustar horários e aulas, conforme as regras do sistema.
- Não criar ainda o fechamento do ano letivo completo.
- Não criar ainda relatórios finais complexos.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations, se necessário
- models, se necessário
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. a coordenação visualizar o diário dos professores;
2. validar planejamentos;
3. validar aulas;
4. acompanhar frequência;
5. consultar notas/conceitos;
6. alterar notas/conceitos, quando tiver permissão;
7. consultar horários;
8. cadastrar/editar/reorganizar horários;
9. consultar e ajustar aulas, quando permitido;
10. ver pendências pedagógicas;
11. identificar alunos em risco;
12. confirmar que a coordenação possui controle total pedagógico sobre horários e aulas.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 17 — Direção Escolar

```text
Execute apenas a ETAPA 17 do projeto.

CONTEXTO:
Já existem diário do professor e funcionalidades pedagógicas básicas da coordenação.
Agora quero criar as funcionalidades da Direção Escolar.

OBJETIVO:
Criar as funcionalidades administrativas e pedagógicas da Direção Escolar, com controle amplo sobre a escola, incluindo consulta e alteração de notas/conceitos, horários, aulas e processos escolares.

ESCOPO DESTA ETAPA:
1. Criar acesso da direção ao diário dos professores.
2. Criar validação de aulas e planejamentos, quando aplicável.
3. Criar consulta de notas/conceitos.
4. Permitir alteração de notas/conceitos pela direção, conforme permissão.
5. Criar justificativa de faltas de alunos, conforme regra.
6. Criar liberação de prazo para professor registrar:
   - aula
   - frequência
   - notas/conceitos
7. Criar gerenciamento de horários de turmas.
8. Criar gerenciamento de aulas, quando aplicável.
9. Criar registro de faltas de professores e funcionários.
10. Criar fechamento do ano letivo em nível inicial.
11. Permitir à direção gerir tudo no contexto escolar, respeitando permissões administrativas.
12. Criar telas Blade + Tailwind reais e funcionais.
13. Preparar a consolidação futura do Portal da Direção Escolar.

REGRAS:
- A direção deve poder consultar e alterar notas/conceitos, conforme permissão.
- A direção deve poder gerir horários.
- A direção deve poder gerir aulas.
- A direção deve ter mais poderes administrativos do que a coordenação.
- Não misturar papel da direção com o da coordenação, embora a direção tenha alcance superior.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations, se necessário
- models, se necessário
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. direção acessar diário dos professores;
2. consultar notas/conceitos;
3. alterar notas/conceitos, quando permitido;
4. justificar faltas de alunos;
5. liberar prazo de lançamento;
6. gerir horários;
7. gerir aulas;
8. registrar faltas de professores/funcionários;
9. executar fluxo inicial de fechamento letivo;
10. validar que a direção possui alcance superior ao da coordenação;
11. confirmar que a direção consegue gerir amplamente o contexto escolar.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 18 — Alimentação escolar

```text
Execute apenas a ETAPA 18 do projeto.

CONTEXTO:
Agora quero criar o núcleo funcional da alimentação escolar.

OBJETIVO:
Criar o módulo de alimentação escolar com operação principal no contexto da escola, especialmente pela Secretaria Escolar, e com preparação para visão técnica e gerencial da Nutricionista.

ESCOPO DESTA ETAPA:
1. Criar cadastro de alimentos.
2. Criar cadastro de categorias de alimentos.
3. Criar cadastro de fornecedores.
4. Criar controle de entrada de alimentos.
5. Criar controle de saída de alimentos.
6. Criar controle de estoque.
7. Criar controle de validade.
8. Criar cardápio diário.
9. Permitir que a Secretaria Escolar lance:
   - cardápio diário
   - entrada de alimentos
   - saída de alimentos
10. Permitir que a Secretaria Escolar consulte:
   - estoque
   - validade
   - movimentações
11. Preparar o módulo para visão gerencial futura da Nutricionista.
12. Criar permissões por ação, no mínimo:
   - consultar alimentação escolar
   - cadastrar alimentos
   - editar alimentos
   - registrar entrada
   - registrar saída
   - lançar cardápio
   - consultar estoque
13. Criar telas Blade + Tailwind reais e funcionais.
14. Garantir que este módulo pertença ao contexto operacional da escola, sem depender do Portal da Secretaria de Educação.

REGRAS:
- A operação diária da alimentação escolar deve ficar com a Secretaria Escolar, no contexto da escola.
- A Nutricionista terá depois visão técnica e gerencial mais ampla.
- Não criar ainda o Portal da Nutricionista completo.
- Não criar ainda relatórios gerenciais comparativos complexos.
- Não criar ainda indicadores avançados.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. cadastrar alimentos;
2. cadastrar categorias;
3. cadastrar fornecedores;
4. registrar entrada;
5. registrar saída;
6. acompanhar estoque;
7. lançar cardápio diário;
8. validar que a Secretaria Escolar consegue operar esse módulo no contexto da escola.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 19 — Portal da Nutricionista

```text
Execute apenas a ETAPA 19 do projeto.

CONTEXTO:
O módulo de alimentação escolar já existe.
Agora quero consolidar o Portal da Nutricionista.

OBJETIVO:
Criar o Portal da Nutricionista com visão técnica e gerencial da alimentação escolar, sem retirar da Secretaria Escolar a operação diária no contexto da escola.

ESCOPO DESTA ETAPA:
1. Criar layout próprio do Portal da Nutricionista.
2. Criar menu lateral próprio.
3. Criar dashboard próprio.
4. Integrar neste portal:
   - alimentos
   - categorias
   - fornecedores
   - cardápios
   - estoque
   - validade
   - entrada de alimentos
   - saída de alimentos
5. Criar visão comparativa inicial entre escolas.
6. Criar relatórios gerenciais iniciais.
7. Aplicar permissões corretas.
8. Garantir separação visual e funcional deste portal.
9. Garantir que:
   - Secretaria Escolar continua operando o dia a dia da alimentação da escola;
   - Nutricionista possui visão técnica, acompanhamento, análise e gestão gerencial mais ampla.

REGRAS:
- A Nutricionista não substitui a Secretaria Escolar na operação diária da escola.
- A Nutricionista deve ter visão mais ampla, técnica e comparativa.
- Não usar painel genérico.
- O portal deve ter identidade própria.
- Usar Blade + Tailwind.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- layout do Portal da Nutricionista
- menu lateral
- dashboard
- views integradas
- rotas organizadas
- permissões aplicadas
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. entrar no Portal da Nutricionista;
2. ver layout e menu próprios;
3. acessar os módulos da alimentação;
4. ver visão gerencial inicial;
5. comparar dados entre escolas, quando aplicável;
6. validar a separação entre Nutricionista e Secretaria Escolar;
7. confirmar que a Secretaria Escolar continua operando a alimentação no nível da escola.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 20 — Psicologia/Psicopedagogia (módulo)

```text
Execute apenas a ETAPA 20 do projeto.

CONTEXTO:
Agora quero criar o módulo de atendimentos psicossociais e psicopedagógicos.

OBJETIVO:
Criar o módulo de Psicologia/Psicopedagogia com controle de sigilo.

ESCOPO DESTA ETAPA:
1. Criar cadastro de atendimentos.
2. Permitir atendimento a:
   - alunos
   - professores
   - funcionários
   - pais/responsáveis
3. Criar agenda de atendimentos.
4. Criar histórico de atendimentos.
5. Criar planos de intervenção.
6. Criar encaminhamentos internos.
7. Criar encaminhamentos externos.
8. Criar registro de casos disciplinares, quando aplicável.
9. Criar relatórios técnicos iniciais.
10. Criar controle de acesso sigiloso.
11. Criar telas Blade + Tailwind reais e funcionais.
12. Preparar base para o futuro portal específico consolidado.

REGRAS:
- Restringir fortemente acesso por permissão.
- Não expor dados sigilosos a perfis não autorizados.
- Não criar ainda todos os relatórios finais complexos.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- migrations
- models
- requests
- services
- controllers
- views
- rotas
- permissões
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. registrar atendimento;
2. registrar agenda;
3. ver histórico;
4. criar plano de intervenção;
5. criar encaminhamento;
6. validar que o acesso é sigiloso e restrito.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 20-A — Portal da Psicologia/Psicopedagogia

```text
IMPORTANTE:
O módulo de atendimentos psicológicos e psicopedagógicos já existe.
Nesta etapa, não recrie o módulo nem reestruture sem necessidade.
Apenas consolide o Portal da Psicologia/Psicopedagogia, integrando o que já existe.

Execute apenas a ETAPA 20-A do projeto.

CONTEXTO:
O módulo de atendimentos psicológicos e psicopedagógicos já existe ou está em construção.
Agora quero consolidar o Portal da Psicologia/Psicopedagogia como área própria, separada dos demais portais.

OBJETIVO:
Criar o Portal da Psicologia/Psicopedagogia com layout, navegação, dashboard e integração completa dos atendimentos, respeitando sigilo, restrição de acesso e contexto técnico.

ESCOPO DESTA ETAPA:
1. Criar layout próprio do Portal da Psicologia/Psicopedagogia.
2. Criar menu lateral próprio.
3. Criar dashboard próprio.
4. Integrar neste portal, no mínimo:
   - agenda de atendimentos
   - atendimento a alunos
   - atendimento a professores
   - atendimento a funcionários
   - atendimento a pais/responsáveis
   - histórico de atendimentos
   - planos de intervenção
   - encaminhamentos internos
   - encaminhamentos externos
   - casos disciplinares, quando aplicável
   - relatórios técnicos restritos
5. Criar navegação coerente com o fluxo real de trabalho da psicologia/psicopedagogia.
6. Criar breadcrumbs e estrutura visual coerente.
7. Aplicar permissões corretas por ação.
8. Garantir sigilo e restrição forte de acesso.
9. Garantir que este portal seja visual e funcionalmente separado dos demais.
10. Preparar espaço no menu para futuras integrações, quando necessário, com:
   - alunos
   - AEE
   - relatórios técnicos
   - auditoria técnica restrita

REGRAS:
- Este portal deve ser independente dos demais.
- Não usar painel genérico único.
- Não colocar esse módulo como submenu solto dentro de outro portal.
- O acesso deve ser altamente restrito.
- O usuário deste portal deve ver apenas dados compatíveis com sua função e permissão.
- Não expor dados sigilosos a perfis não autorizados.
- Usar Blade + Tailwind.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- layout do Portal da Psicologia/Psicopedagogia
- menu lateral
- dashboard
- views integradas
- rotas organizadas
- permissões aplicadas
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. entrar no Portal da Psicologia/Psicopedagogia;
2. ver layout e menu próprios;
3. acessar agenda, atendimentos, histórico, planos de intervenção e encaminhamentos;
4. validar que o acesso é sigiloso e restrito;
5. confirmar que esse portal está realmente separado dos demais;
6. confirmar que apenas perfis autorizados conseguem acessar esse portal.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 21 — Documentos escolares por portal

```text
Execute apenas a ETAPA 21 do projeto.

CONTEXTO:
Já existem dados institucionais, alunos, matrículas e módulos escolares.
Agora quero criar os documentos escolares e institucionais do sistema.

OBJETIVO:
Criar o módulo de documentos escolares com emissão real, distribuindo cada documento no portal correspondente ao perfil que o utiliza.

REGRA ARQUITETURAL IMPORTANTE:
Os documentos não devem ficar todos concentrados em um único módulo genérico de emissão documental sem contexto.
Cada documento deve ser inserido no portal correspondente, com:
- menu próprio;
- rotas próprias;
- views próprias;
- permissões próprias;
- layout do portal correspondente.

Pode haver reaproveitamento interno de services, templates base e regras de geração, mas a experiência final deve respeitar o portal de destino.

ESCOPO DESTA ETAPA:
1. Criar estrutura de emissão documental por portal.
2. Criar visualização prévia em tela.
3. Criar layouts prontos para impressão.
4. Preparar geração de PDF.
5. Criar permissões por documento e por ação.
6. Usar dados institucionais da rede e da escola.
7. Usar assinaturas e cargos configuráveis.
8. Distribuir os documentos por portal da seguinte forma:

## A. Portal da Secretaria Escolar
Criar neste portal os documentos operacionais da escola, no mínimo:
- guia de transferência;
- atas;
- ficha individual do aluno;
- declarações;
- ofícios escolares;
- histórico escolar, quando já houver base suficiente;
- declaração de matrícula;
- declaração de frequência;
- comprovante de matrícula;
- ficha cadastral do aluno.

### Regras do Portal da Secretaria Escolar
- este deve ser o principal portal operacional de emissão documental da escola;
- a Secretaria Escolar deve conseguir emitir os documentos ligados à vida escolar do aluno e à rotina administrativa da escola;
- a emissão deve respeitar permissões por documento.

## B. Portal da Secretaria de Educação
Criar neste portal os documentos institucionais e administrativos da rede, no mínimo:
- ofícios institucionais da rede;
- documentos oficiais da Secretaria de Educação, quando aplicável;
- documentos consolidados de rede, quando houver base suficiente;
- modelos institucionais com dados da prefeitura e secretaria.

### Regras do Portal da Secretaria de Educação
- este portal não deve assumir a rotina documental da escola;
- deve focar na emissão institucional e administrativa da rede.

## C. Portal da Direção Escolar
Criar neste portal acesso aos documentos escolares que façam sentido para gestão escolar, no mínimo:
- consulta e emissão, conforme permissão, de documentos da escola;
- atas;
- ofícios da escola;
- declarações e documentos gerenciais, quando aplicável.

### Regras do Portal da Direção Escolar
- a direção pode ter acesso mais amplo do que a Secretaria Escolar em documentos sensíveis ou gerenciais, conforme permissão;
- a direção não substitui a rotina operacional da secretaria escolar, mas pode consultar e emitir documentos quando autorizado.

## D. Portal da Coordenação Pedagógica
Criar neste portal apenas os documentos pedagógicos compatíveis com sua função, quando aplicável, por exemplo:
- ficha individual do aluno;
- documentos pedagógicos de acompanhamento;
- consultas documentais de apoio ao acompanhamento pedagógico.

### Regras do Portal da Coordenação Pedagógica
- a coordenação não deve ter acesso indiscriminado a toda emissão documental da escola;
- acessar apenas documentos compatíveis com sua função e permissão.

## E. Portal do Professor
Criar neste portal apenas documentos ou impressões pedagógicas/operacionais compatíveis com a função docente, quando aplicável, por exemplo:
- relatórios/impressões operacionais das próprias turmas;
- consultas documentais restritas ao seu escopo.

### Regras do Portal do Professor
- o professor não deve emitir documentos administrativos da escola;
- o acesso deve ser apenas ao que for compatível com sua função e permissão.

## F. Portal da Psicologia/Psicopedagogia
Criar neste portal apenas documentos técnicos restritos, quando aplicável, por exemplo:
- relatórios técnicos;
- registros de atendimento;
- encaminhamentos;
- documentos sigilosos de acompanhamento.

### Regras do Portal da Psicologia/Psicopedagogia
- acesso altamente restrito;
- respeitar sigilo e permissões específicas.

9. Garantir que cada documento seja acessado a partir do portal correto.
10. Garantir que a emissão documental respeite:
   - perfil do usuário;
   - escola vinculada;
   - tipo de documento;
   - permissões específicas;
   - contexto institucional da rede e da escola.
11. Garantir que os documentos utilizem:
   - dados da prefeitura;
   - dados da secretaria de educação;
   - dados da escola;
   - brasão/logomarcas;
   - assinaturas e cargos configuráveis.
12. Preparar os documentos para:
   - visualização;
   - impressão;
   - exportação em PDF.

REGRAS:
- Criar documentos reais e utilizáveis.
- Não criar um centro genérico único de documentos para todos os perfis.
- Cada documento deve aparecer no menu do portal correto.
- Cada documento deve respeitar layout, permissões e navegação do portal correspondente.
- Pode reutilizar services internos, templates base e componentes.
- Usar nomes em português do Brasil.
- Respeitar escopo por portal e por permissão.

ENTREGÁVEIS:
- services de emissão documental
- controllers
- views/documentos
- rotas
- permissões
- integração por portal
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. acessar documentos por perfil autorizado;
2. encontrar cada documento no portal correspondente;
3. visualizar documento antes da impressão;
4. gerar versão pronta para impressão/PDF;
5. validar dados institucionais no documento;
6. validar permissões por documento;
7. confirmar que a Secretaria Escolar consegue operar os documentos escolares da rotina da escola;
8. confirmar que a Secretaria de Educação emite documentos institucionais da rede;
9. confirmar que a direção acessa os documentos compatíveis com sua função;
10. confirmar que a coordenação e o professor só acessam documentos compatíveis com seu escopo;
11. confirmar que não existe um módulo genérico único centralizando toda a emissão documental de todos os perfis.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 22 — Relatórios por portal

```text
Execute apenas a ETAPA 22 do projeto.

CONTEXTO:
Os principais módulos já existem.
Agora quero criar os relatórios do sistema.

OBJETIVO:
Criar o módulo de relatórios, com cada relatório localizado no portal correspondente ao perfil que o utiliza.

REGRA ARQUITETURAL IMPORTANTE:
Os relatórios não devem ficar todos concentrados em um único módulo genérico de relatórios sem contexto.
Cada relatório deve ser inserido no portal correspondente, com:
- menu próprio;
- rotas próprias;
- views próprias;
- permissões próprias;
- layout do portal correspondente.

Pode haver reaproveitamento interno de services e consultas, mas a experiência final deve respeitar o portal de destino.

ESCOPO DESTA ETAPA:
1. Criar estrutura de relatórios por portal.
2. Criar visualização em tela.
3. Criar filtros por:
   - período
   - escola
   - turma
   - modalidade
   - perfil/autorização, quando aplicável
4. Preparar impressão/exportação.
5. Criar permissões por relatório.
6. Distribuir os relatórios por portal da seguinte forma:

## A. Portal da Secretaria de Educação
Criar neste portal os relatórios consolidados e gerenciais da rede, no mínimo:
- relatório institucional da rede;
- alunos matriculados por escola/turma/ano;
- quantitativo de matrículas regulares;
- quantitativo de matrículas AEE;
- quantitativo de matrículas duplas;
- alunos com matrícula regular + AEE;
- alunos com matrícula somente AEE;
- alunos com AEE;
- mapa geral de turmas;
- professores por turma/disciplina em visão consolidada;
- indicadores gerais da rede;
- comparativos entre escolas;
- relatórios consolidados administrativos da rede;
- relatórios consolidados pedagógicos da rede, quando aplicável;
- relatórios consolidados de auditoria, quando houver base suficiente.

## B. Portal da Secretaria Escolar
Criar neste portal os relatórios administrativos e escolares operacionais, no mínimo:
- alunos matriculados por escola/turma/ano;
- ficha individual do aluno;
- histórico escolar, quando já houver base suficiente;
- declaração de matrícula;
- declaração de frequência;
- comprovante de matrícula;
- ficha cadastral;
- mapa de turmas da escola;
- entrada de alimentos;
- saída de alimentos;
- estoque da escola;
- validade de alimentos da escola;
- cardápio da escola;
- relatórios administrativos escolares;
- consulta de notas/conceitos em formato de relatório, apenas para visualização.

## C. Portal da Coordenação Pedagógica
Criar neste portal os relatórios pedagógicos, no mínimo:
- frequência consolidada;
- acompanhamento de rendimento;
- alunos em risco;
- acompanhamento por turma/disciplina;
- acompanhamento de planejamento;
- acompanhamento de aulas registradas;
- acompanhamento pedagógico do AEE;
- relatórios pedagógicos por professor;
- relatórios pedagógicos por turma;
- relatórios pedagógicos por escola, quando aplicável ao escopo da coordenação.

## D. Portal da Direção Escolar
Criar neste portal relatórios pedagógicos e administrativos da escola, no mínimo:
- frequência consolidada da escola;
- rendimento da escola;
- alunos em risco;
- mapa de turmas;
- professores por turma/disciplina;
- faltas de professores e funcionários;
- relatórios administrativos escolares;
- relatórios pedagógicos escolares;
- acompanhamento de pendências escolares;
- relatórios de fechamento letivo, quando houver base suficiente.

## E. Portal do Professor
Criar neste portal relatórios pessoais e operacionais do professor, no mínimo:
- minhas turmas;
- minhas aulas registradas;
- minha frequência lançada;
- meus lançamentos de notas/conceitos;
- acompanhamento de pendências;
- acompanhamento dos meus planejamentos;
- relatórios operacionais das minhas turmas, respeitando permissões.

## F. Portal da Nutricionista
Criar neste portal os relatórios de alimentação escolar em visão técnica e gerencial, no mínimo:
- cardápio por escola;
- entrada de alimentos;
- saída de alimentos;
- estoque;
- validade;
- consumo por escola/período;
- comparativo de consumo entre escolas;
- relatórios gerenciais de alimentação;
- indicadores nutricionais iniciais.

## G. Portal da Psicologia/Psicopedagogia
Criar neste portal os relatórios técnicos restritos, no mínimo:
- histórico de atendimentos;
- atendimentos por aluno;
- atendimentos por professor;
- atendimentos por funcionário;
- atendimentos por responsável;
- encaminhamentos internos;
- encaminhamentos externos;
- planos de intervenção;
- relatórios técnicos restritos;
- acompanhamento de casos disciplinares, quando aplicável.

7. Garantir que a Secretaria Escolar apenas visualize notas/conceitos em relatórios, sem poder alterar.
8. Garantir que coordenação e direção tenham acesso aos relatórios pedagógicos compatíveis com sua função.
9. Garantir que a Secretaria de Educação tenha visão consolidada e gerencial da rede, e não a operação cotidiana da escola.
10. Garantir que cada relatório seja acessado a partir do portal correto.

REGRAS:
- Criar relatórios reais e utilizáveis.
- Não criar um centro genérico único de relatórios para todos os perfis.
- Cada relatório deve aparecer no menu do portal correto.
- Cada relatório deve respeitar layout, permissões e navegação do portal correspondente.
- Pode reutilizar services internos, mas não a experiência visual final.
- Usar nomes em português do Brasil.
- Respeitar escopo por portal e por permissão.

ENTREGÁVEIS:
- services de relatório
- controllers
- views
- rotas
- permissões
- integração por portal
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. acessar relatórios por perfil autorizado;
2. encontrar cada relatório no portal correspondente;
3. filtrar;
4. visualizar;
5. imprimir/exportar, quando aplicável;
6. validar dados e permissões;
7. confirmar que cada portal enxerga apenas os relatórios compatíveis com sua função;
8. confirmar que a Secretaria Escolar apenas visualiza notas/conceitos em relatórios, sem alterar;
9. confirmar que não existe um painel genérico único centralizando todos os relatórios de todos os perfis.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 23 — Auditoria avançada por portal e perfil

```text
Execute apenas a ETAPA 23 do projeto.

CONTEXTO:
Os módulos principais já existem.
Agora quero fortalecer a auditoria do sistema, distribuindo a consulta dos logs conforme o portal e o perfil do usuário.

OBJETIVO:
Criar auditoria avançada e rastreabilidade, com acesso aos logs distribuído por portal e por perfil, respeitando escopo, sigilo e função de cada usuário.

REGRA ARQUITETURAL IMPORTANTE:
A auditoria não deve aparecer como um módulo genérico único e irrestrito para todos os perfis.
A consulta de logs e eventos deve respeitar:
- o portal do usuário;
- o perfil do usuário;
- a escola vinculada;
- o escopo funcional do cargo;
- o nível de sensibilidade da informação.

Pode haver reaproveitamento interno de services, eventos, observers e estrutura de armazenamento de logs, mas a experiência final de consulta deve respeitar o portal e a função de cada usuário.

ESCOPO DESTA ETAPA:
1. Criar estrutura de auditoria avançada para registrar, no mínimo:
   - quem criou;
   - quem alterou;
   - quem excluiu;
   - quem visualizou dados sensíveis, quando aplicável;
   - data e hora;
   - IP;
   - user-agent;
   - valores antes/depois em campos críticos;
   - módulo afetado;
   - ação executada;
   - contexto da operação.
2. Aplicar auditoria aos módulos mais sensíveis, no mínimo:
   - usuários;
   - dados institucionais;
   - escolas;
   - funcionários;
   - alunos;
   - matrículas;
   - AEE;
   - alimentação escolar;
   - atendimentos psicologia/psicopedagogia;
   - documentos escolares;
   - notas/conceitos, quando já existirem;
   - frequência, quando já existir;
   - planejamentos, quando já existirem.
3. Criar filtros de auditoria por:
   - período;
   - usuário;
   - escola;
   - módulo;
   - ação;
   - tipo de registro;
   - nível de sensibilidade.
4. Distribuir a consulta da auditoria por portal da seguinte forma:

## A. Portal da Secretaria de Educação
Criar neste portal a visão mais ampla de auditoria da rede, no mínimo:
- logs consolidados da rede;
- auditoria por escola;
- auditoria por usuário;
- auditoria por módulo;
- auditoria institucional;
- auditoria administrativa consolidada;
- acompanhamento de alterações críticas da rede;
- relatórios de auditoria consolidados, quando aplicável.

### Regras do Portal da Secretaria de Educação
- este deve ser o portal principal de auditoria ampla da rede;
- deve ter acesso mais abrangente, conforme permissões internas;
- mesmo dentro da Secretaria de Educação, o acesso deve continuar respeitando permissões granulares.

## B. Portal da Secretaria Escolar
Criar neste portal acesso apenas à auditoria compatível com a rotina escolar e administrativa da escola, no mínimo:
- alterações em alunos;
- alterações em matrículas;
- alterações em turmas;
- alterações em horários, quando aplicável;
- alterações em alimentação escolar;
- emissão de documentos escolares;
- ações administrativas da escola.

### Regras do Portal da Secretaria Escolar
- a Secretaria Escolar não deve ter acesso irrestrito à auditoria completa da rede;
- deve enxergar apenas a auditoria compatível com sua escola e com seus módulos operacionais.

## C. Portal da Coordenação Pedagógica
Criar neste portal acesso apenas à auditoria pedagógica compatível com sua função, no mínimo:
- alterações em planejamentos;
- alterações em aulas registradas;
- alterações em frequência;
- alterações em notas/conceitos, quando aplicável;
- ações pedagógicas da escola;
- eventos relacionados ao acompanhamento pedagógico.

### Regras do Portal da Coordenação Pedagógica
- a coordenação não deve acessar auditoria administrativa irrestrita;
- deve enxergar apenas o que fizer sentido para acompanhamento pedagógico e gestão escolar compatível com seu cargo.

## D. Portal da Direção Escolar
Criar neste portal acesso à auditoria pedagógica e administrativa da escola, no mínimo:
- alterações em alunos;
- alterações em matrículas;
- alterações em turmas;
- alterações em horários;
- alterações em aulas;
- alterações em planejamentos;
- alterações em frequência;
- alterações em notas/conceitos;
- emissão documental escolar;
- registros de faltas de professores e funcionários;
- ações críticas da gestão escolar.

### Regras do Portal da Direção Escolar
- a direção deve ter alcance maior do que a coordenação e a secretaria escolar dentro do contexto da escola;
- ainda assim, deve permanecer restrita ao escopo escolar, e não à rede inteira.

## E. Portal do Professor
Criar neste portal apenas a visualização de rastros compatíveis com seu próprio trabalho, quando fizer sentido, por exemplo:
- histórico dos próprios lançamentos;
- histórico dos próprios planejamentos;
- histórico das próprias aulas;
- histórico dos próprios lançamentos de frequência;
- histórico dos próprios lançamentos de notas/conceitos;
- status de validação/devolutiva dos seus registros.

### Regras do Portal do Professor
- o professor não deve acessar auditoria de outros usuários;
- deve enxergar apenas rastros relacionados ao próprio trabalho e ao próprio escopo.

## F. Portal da Nutricionista
Criar neste portal a auditoria compatível com alimentação escolar, no mínimo:
- alterações em alimentos;
- alterações em categorias;
- alterações em fornecedores;
- movimentações de estoque;
- alterações em cardápios;
- registros de entrada e saída;
- eventos críticos da alimentação escolar.

### Regras do Portal da Nutricionista
- a Nutricionista não deve acessar auditoria irrestrita de módulos sem relação com sua função;
- deve ter visão técnica e gerencial sobre alimentação escolar.

## G. Portal da Psicologia/Psicopedagogia
Criar neste portal apenas a auditoria altamente restrita dos módulos técnicos e sigilosos, no mínimo:
- alterações em atendimentos;
- alterações em planos de intervenção;
- alterações em encaminhamentos;
- visualizações de registros sensíveis, quando aplicável;
- eventos críticos de acesso a dados sigilosos.

### Regras do Portal da Psicologia/Psicopedagogia
- acesso altamente restrito;
- logs sensíveis devem respeitar sigilo;
- somente perfis autorizados devem visualizar esse nível de auditoria.

5. Garantir que a auditoria respeite LGPD e sigilo.
6. Garantir que cada consulta de auditoria seja acessada pelo portal correto.
7. Criar telas Blade + Tailwind reais e funcionais para consulta dos logs.
8. Criar permissões por nível de auditoria e por módulo auditado.

REGRAS:
- Não expor auditoria a perfis não autorizados.
- Não comprometer desempenho de forma desnecessária.
- Não criar um painel genérico único de auditoria para todos os perfis.
- Cada visão de auditoria deve aparecer no portal correto.
- Pode reutilizar services internos, observers, events e estrutura de armazenamento de logs.
- Usar nomes em português do Brasil.
- Respeitar escopo por portal, por escola e por perfil.

ENTREGÁVEIS:
- migrations, se necessário
- models, se necessário
- observers/events/listeners, se fizer sentido
- services
- controllers
- views
- rotas
- permissões
- integração por portal
- instruções de teste

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. ver logs dos módulos sensíveis;
2. filtrar logs;
3. identificar quem alterou o quê;
4. validar antes/depois em campos críticos;
5. confirmar que a Secretaria de Educação vê auditoria ampla da rede, conforme permissão;
6. confirmar que a Secretaria Escolar vê apenas auditoria da sua rotina escolar e da sua escola;
7. confirmar que a Coordenação vê auditoria pedagógica compatível com sua função;
8. confirmar que a Direção vê auditoria pedagógica e administrativa da escola;
9. confirmar que o Professor vê apenas rastros do próprio trabalho;
10. confirmar que a Nutricionista vê auditoria compatível com alimentação escolar;
11. confirmar que a Psicologia/Psicopedagogia vê apenas auditoria técnica e sigilosa compatível com sua função;
12. confirmar que não existe um módulo genérico único centralizando auditoria irrestrita para todos os perfis.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Etapa 24 — Testes e revisão final

```text
Execute apenas a ETAPA 24 do projeto.

CONTEXTO:
Os módulos principais do sistema já foram construídos.
Agora quero consolidar testes e revisão final.

OBJETIVO:
Fortalecer testes, revisar inconsistências e deixar a base mais estável.

ESCOPO DESTA ETAPA:
1. Revisar consistência entre:
   - banco
   - models
   - services
   - controllers
   - views
   - rotas
   - permissões
2. Criar ou complementar testes:
   - autenticação
   - autorização
   - usuários
   - dados institucionais
   - escolas
   - funcionários
   - alunos
   - turmas
   - matrículas
   - matrícula regular + AEE
   - matrícula somente AEE
   - diário
   - alimentação escolar
   - psicologia/psicopedagogia
   - documentos
   - relatórios
   - auditoria
3. Corrigir incoerências identificadas.
4. Revisar permissões por portal.
5. Revisar layouts e navegação.
6. Revisar instruções de instalação e uso.
7. Consolidar README final.

REGRAS:
- Não reescrever o sistema desnecessariamente.
- Corrigir apenas o que for preciso para deixar a base consistente.
- Usar nomes em português do Brasil.

ENTREGÁVEIS:
- testes
- ajustes de consistência
- README final
- instruções finais de setup e teste
- resumo das correções aplicadas

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. rodar o projeto com mais estabilidade;
2. validar os módulos principais;
3. rodar testes principais;
4. entender claramente como instalar e testar o sistema.

Ao final, liste os arquivos criados/alterados e explique como testar manualmente.
```

## Prompt complementar — Fluxo completo do portal da Psicologia/Psicopedagogia

```text
Execute apenas a ETAPA do Portal da Psicologia/Psicopedagogia.

IMPORTANTE:
O módulo de atendimentos psicológicos e psicopedagógicos pode já existir total ou parcialmente.
Nesta etapa, não recrie o módulo nem reestruture sem necessidade.
Apenas implemente ou complete o fluxo funcional correto do portal, organizando telas, rotas, permissões, navegação e regras de negócio conforme descrito abaixo.

CONTEXTO:
Este sistema educacional possui portais separados por perfil.
Agora quero consolidar o Portal da Psicologia/Psicopedagogia como área própria, com fluxo real de atendimento, respeitando sigilo, restrição de acesso e contexto técnico.

OBJETIVO:
Criar ou ajustar o Portal da Psicologia/Psicopedagogia para que o atendimento siga um fluxo operacional completo, do início ao encerramento do caso.

REGRA ARQUITETURAL IMPORTANTE:
Este portal deve ser independente dos demais.
Não deve ser apenas um submenu solto em outro portal.
Deve possuir:
- layout próprio;
- menu próprio;
- dashboard próprio;
- rotas próprias;
- permissões próprias;
- telas próprias;
- controle forte de sigilo.

O acesso deve ser altamente restrito e auditável.

FLUXO OBRIGATÓRIO DO ATENDIMENTO:
O atendimento deve seguir, no mínimo, esta sequência funcional:

1. Receber a demanda
2. Identificar a pessoa atendida
3. Fazer triagem inicial
4. Abrir plano de atendimento
5. Agendar atendimento
6. Registrar atendimento realizado
7. Atualizar histórico do caso
8. Fazer encaminhamentos, quando necessário
9. Registrar devolutivas
10. Reavaliar o caso periodicamente
11. Encerrar formalmente o atendimento
12. Manter histórico restrito para consulta futura

ESCOPO DESTA ETAPA:

## 1. Portal próprio
Criar:
- layout próprio do Portal da Psicologia/Psicopedagogia
- menu lateral próprio
- dashboard próprio
- navegação coerente com o trabalho técnico
- breadcrumbs
- separação visual e funcional dos demais portais

## 2. Módulo de demandas
Criar tela de abertura de demanda de atendimento com, no mínimo:
- tipo de atendimento: psicologia, psicopedagogia ou ambos
- origem da demanda
- pessoa atendida:
  - aluno
  - professor
  - funcionário
  - responsável
- escola
- data da solicitação
- motivo inicial
- prioridade
- profissional responsável
- status inicial

## 3. Vínculo com a pessoa atendida
Permitir vincular corretamente:
- aluno
- professor
- funcionário
- responsável

Quando for aluno, aproveitar dados já existentes no sistema, sem duplicação indevida.

## 4. Triagem inicial
Criar tela de triagem inicial com, no mínimo:
- resumo do caso
- sinais observados
- histórico breve
- urgência
- risco identificado
- necessidade de sigilo reforçado
- decisão inicial:
  - iniciar atendimento
  - observar
  - encaminhar externamente
  - devolver para setor pedagógico
  - encerrar sem atendimento

## 5. Plano de atendimento
Criar tela de plano de atendimento com, no mínimo:
- objetivo geral
- objetivos específicos
- tipo de intervenção
- frequência prevista
- profissional responsável
- prazo estimado
- envolvidos no caso
- estratégias
- observações técnicas
- necessidade de acompanhamento com família
- necessidade de articulação com coordenação/direção
- necessidade de encaminhamento externo
- status do caso

## 6. Agenda
Criar agenda de atendimentos com, no mínimo:
- data
- hora
- local
- profissional
- pessoa atendida
- tipo de sessão
- duração prevista
- observações logísticas

## 7. Registro de atendimento
Criar tela para registrar cada atendimento realizado com, no mínimo:
- data
- hora
- profissional
- pessoa atendida
- tipo de atendimento
- objetivo da sessão
- relato/resumo do atendimento
- estratégias utilizadas
- comportamento observado
- evolução percebida
- encaminhamentos definidos
- necessidade de retorno
- próximo passo
- anexos, se permitido
- status da sessão:
  - realizado
  - remarcado
  - faltou
  - cancelado

## 8. Histórico do caso
Criar histórico cronológico do caso, consolidando:
- demanda inicial
- triagem
- plano de atendimento
- sessões realizadas
- encaminhamentos
- devolutivas
- reavaliações
- encerramento

## 9. Encaminhamentos
Criar tela de encaminhamento com:
- tipo: interno ou externo
- destino
- motivo
- data
- observações
- retorno esperado
- status do encaminhamento

Permitir encaminhamento, quando aplicável, para:
- coordenação pedagógica
- direção
- AEE
- professor responsável
- secretaria escolar
- CAPS
- CRAS/CREAS
- unidade de saúde
- conselho tutelar
- outros serviços

## 10. Devolutivas
Criar tela de devolutiva com:
- destinatário
- data
- resumo da devolutiva
- orientações
- encaminhamentos combinados
- necessidade de acompanhamento

## 11. Reavaliação
Criar tela de reavaliação do caso com:
- data da reavaliação
- progresso observado
- dificuldades persistentes
- ajuste do plano
- nova frequência
- nova decisão

## 12. Encerramento do atendimento
Criar fluxo formal de encerramento com:
- motivo do encerramento
- resumo final
- evolução do caso
- orientações finais
- encaminhamentos finais
- data de encerramento
- possibilidade de reabertura futura

## 13. Relatórios técnicos
Criar visualização de relatórios técnicos restritos compatíveis com a função do portal.

## 14. Segurança e sigilo
Garantir:
- acesso altamente restrito
- permissões por ação
- controle de visualização de dados sigilosos
- rastreabilidade/auditoria de acessos sensíveis
- respeito ao escopo do profissional

## 15. Permissões mínimas
Criar permissões por ação, no mínimo:
- consultar demandas
- criar demanda
- editar demanda
- realizar triagem
- criar plano de atendimento
- agendar atendimento
- registrar atendimento
- registrar encaminhamento
- registrar devolutiva
- reavaliar caso
- encerrar caso
- consultar histórico
- emitir/consultar relatório técnico, se aplicável

## 16. Telas obrigatórias do portal
Criar, no mínimo:
- dashboard
- demandas
- triagem
- agenda
- atendimentos
- planos de atendimento
- encaminhamentos
- devolutivas
- histórico de casos
- relatórios técnicos
- painel sigiloso

REGRAS:
- Não usar painel genérico único.
- Não expor dados sigilosos a perfis não autorizados.
- Não transformar este portal em simples cadastro sem fluxo.
- Não recriar estruturas já prontas sem necessidade.
- Reaproveite serviços, models e tabelas já existentes, se estiverem corretos.
- Se algo existente estiver errado, ajuste apenas o necessário.
- Use nomes em português do Brasil.
- Use Laravel 11, PHP 8.3, MySQL/MariaDB, Blade e Tailwind.
- Sempre informar os arquivos criados/alterados.
- Criar telas reais e navegáveis, não apenas backend.

ENTREGÁVEIS:
- controllers
- requests
- services
- views Blade
- rotas web
- permissões
- ajustes em models/migrations somente se necessário
- instruções de teste manual

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. entrar no Portal da Psicologia/Psicopedagogia;
2. abrir uma demanda;
3. vincular a pessoa atendida;
4. fazer triagem;
5. abrir plano de atendimento;
6. agendar atendimento;
7. registrar sessões;
8. registrar encaminhamentos;
9. registrar devolutivas;
10. reavaliar o caso;
11. encerrar formalmente o atendimento;
12. consultar o histórico cronológico do caso;
13. validar que o acesso é sigiloso e restrito;
14. confirmar que esse portal está realmente separado dos demais.

Ao final:
- liste todos os arquivos criados/alterados;
- explique o que foi feito;
- explique como testar manualmente;
- informe pendências, se houver.
```


Execute apenas a ETAPA do Portal da Psicologia/Psicopedagogia.

IMPORTANTE:
O módulo de atendimentos psicológicos e psicopedagógicos pode já existir total ou parcialmente.
Nesta etapa, não recrie o módulo nem reestruture sem necessidade.
Apenas implemente ou complete o fluxo funcional correto do portal, organizando telas, rotas, permissões, navegação e regras de negócio conforme descrito abaixo.

CONTEXTO:
Este sistema educacional possui portais separados por perfil.
Agora quero consolidar o Portal da Psicologia/Psicopedagogia como área própria, com fluxo real de atendimento, respeitando sigilo, restrição de acesso e contexto técnico.

OBJETIVO:
Criar ou ajustar o Portal da Psicologia/Psicopedagogia para que o atendimento siga um fluxo operacional completo, do início ao encerramento do caso.

REGRA ARQUITETURAL IMPORTANTE:
Este portal deve ser independente dos demais.
Não deve ser apenas um submenu solto em outro portal.
Deve possuir:
- layout próprio;
- menu próprio;
- dashboard próprio;
- rotas próprias;
- permissões próprias;
- telas próprias;
- controle forte de sigilo.

O acesso deve ser altamente restrito e auditável.

FLUXO OBRIGATÓRIO DO ATENDIMENTO:
O atendimento deve seguir, no mínimo, esta sequência funcional:

1. Receber a demanda
2. Identificar a pessoa atendida
3. Fazer triagem inicial
4. Abrir plano de atendimento
5. Agendar atendimento
6. Registrar atendimento realizado
7. Atualizar histórico do caso
8. Fazer encaminhamentos, quando necessário
9. Registrar devolutivas
10. Reavaliar o caso periodicamente
11. Encerrar formalmente o atendimento
12. Manter histórico restrito para consulta futura

ESCOPO DESTA ETAPA:

## 1. Portal próprio
Criar:
- layout próprio do Portal da Psicologia/Psicopedagogia
- menu lateral próprio
- dashboard próprio
- navegação coerente com o trabalho técnico
- breadcrumbs
- separação visual e funcional dos demais portais

## 2. Módulo de demandas
Criar tela de abertura de demanda de atendimento com, no mínimo:
- tipo de atendimento: psicologia, psicopedagogia ou ambos
- origem da demanda
- pessoa atendida:
  - aluno
  - professor
  - funcionário
  - responsável
- escola
- data da solicitação
- motivo inicial
- prioridade
- profissional responsável
- status inicial

## 3. Vínculo com a pessoa atendida
Permitir vincular corretamente:
- aluno
- professor
- funcionário
- responsável

Quando for aluno, aproveitar dados já existentes no sistema, sem duplicação indevida.

## 4. Triagem inicial
Criar tela de triagem inicial com, no mínimo:
- resumo do caso
- sinais observados
- histórico breve
- urgência
- risco identificado
- necessidade de sigilo reforçado
- decisão inicial:
  - iniciar atendimento
  - observar
  - encaminhar externamente
  - devolver para setor pedagógico
  - encerrar sem atendimento

## 5. Plano de atendimento
Criar tela de plano de atendimento com, no mínimo:
- objetivo geral
- objetivos específicos
- tipo de intervenção
- frequência prevista
- profissional responsável
- prazo estimado
- envolvidos no caso
- estratégias
- observações técnicas
- necessidade de acompanhamento com família
- necessidade de articulação com coordenação/direção
- necessidade de encaminhamento externo
- status do caso

## 6. Agenda
Criar agenda de atendimentos com, no mínimo:
- data
- hora
- local
- profissional
- pessoa atendida
- tipo de sessão
- duração prevista
- observações logísticas

## 7. Registro de atendimento
Criar tela para registrar cada atendimento realizado com, no mínimo:
- data
- hora
- profissional
- pessoa atendida
- tipo de atendimento
- objetivo da sessão
- relato/resumo do atendimento
- estratégias utilizadas
- comportamento observado
- evolução percebida
- encaminhamentos definidos
- necessidade de retorno
- próximo passo
- anexos, se permitido
- status da sessão:
  - realizado
  - remarcado
  - faltou
  - cancelado

## 8. Histórico do caso
Criar histórico cronológico do caso, consolidando:
- demanda inicial
- triagem
- plano de atendimento
- sessões realizadas
- encaminhamentos
- devolutivas
- reavaliações
- encerramento

## 9. Encaminhamentos
Criar tela de encaminhamento com:
- tipo: interno ou externo
- destino
- motivo
- data
- observações
- retorno esperado
- status do encaminhamento

Permitir encaminhamento, quando aplicável, para:
- coordenação pedagógica
- direção
- AEE
- professor responsável
- secretaria escolar
- CAPS
- CRAS/CREAS
- unidade de saúde
- conselho tutelar
- outros serviços

## 10. Devolutivas
Criar tela de devolutiva com:
- destinatário
- data
- resumo da devolutiva
- orientações
- encaminhamentos combinados
- necessidade de acompanhamento

## 11. Reavaliação
Criar tela de reavaliação do caso com:
- data da reavaliação
- progresso observado
- dificuldades persistentes
- ajuste do plano
- nova frequência
- nova decisão

## 12. Encerramento do atendimento
Criar fluxo formal de encerramento com:
- motivo do encerramento
- resumo final
- evolução do caso
- orientações finais
- encaminhamentos finais
- data de encerramento
- possibilidade de reabertura futura

## 13. Relatórios técnicos
Criar visualização de relatórios técnicos restritos compatíveis com a função do portal.

## 14. Segurança e sigilo
Garantir:
- acesso altamente restrito
- permissões por ação
- controle de visualização de dados sigilosos
- rastreabilidade/auditoria de acessos sensíveis
- respeito ao escopo do profissional

## 15. Permissões mínimas
Criar permissões por ação, no mínimo:
- consultar demandas
- criar demanda
- editar demanda
- realizar triagem
- criar plano de atendimento
- agendar atendimento
- registrar atendimento
- registrar encaminhamento
- registrar devolutiva
- reavaliar caso
- encerrar caso
- consultar histórico
- emitir/consultar relatório técnico, se aplicável

## 16. Telas obrigatórias do portal
Criar, no mínimo:
- dashboard
- demandas
- triagem
- agenda
- atendimentos
- planos de atendimento
- encaminhamentos
- devolutivas
- histórico de casos
- relatórios técnicos
- painel sigiloso

REGRAS:
- Não usar painel genérico único.
- Não expor dados sigilosos a perfis não autorizados.
- Não transformar este portal em simples cadastro sem fluxo.
- Não recriar estruturas já prontas sem necessidade.
- Reaproveite serviços, models e tabelas já existentes, se estiverem corretos.
- Se algo existente estiver errado, ajuste apenas o necessário.
- Use nomes em português do Brasil.
- Use Laravel 11, PHP 8.3, MySQL/MariaDB, Blade e Tailwind.
- Sempre informar os arquivos criados/alterados.
- Criar telas reais e navegáveis, não apenas backend.

ENTREGÁVEIS:
- controllers
- requests
- services
- views Blade
- rotas web
- permissões
- ajustes em models/migrations somente se necessário
- instruções de teste manual

CRITÉRIO DE ACEITE:
Ao final eu devo conseguir:
1. entrar no Portal da Psicologia/Psicopedagogia;
2. abrir uma demanda;
3. vincular a pessoa atendida;
4. fazer triagem;
5. abrir plano de atendimento;
6. agendar atendimento;
7. registrar sessões;
8. registrar encaminhamentos;
9. registrar devolutivas;
10. reavaliar o caso;
11. encerrar formalmente o atendimento;
12. consultar o histórico cronológico do caso;
13. validar que o acesso é sigiloso e restrito;
14. confirmar que esse portal está realmente separado dos demais.

Ao final:
- liste todos os arquivos criados/alterados;
- explique o que foi feito;
- explique como testar manualmente;
- informe pendências, se houver.

## Prompt complementar — portal da Psicologia/Psicopedagogia fim ##