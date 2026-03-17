# Sistema Educacional Municipal

Sistema educacional em Laravel para rede publica municipal de ensino, estruturado por portais e modulos com escopo por perfil.

## Visao Geral

Base atual implementada:
- Portal da Secretaria de Educacao
- Portal da Secretaria Escolar
- Portal do Professor
- Portal da Nutricionista
- area funcional de Coordenacao Pedagogica
- area funcional de Direcao Escolar
- modulo de Psicologia/Psicopedagogia com sigilo
- documentos por portal
- relatorios por portal
- auditoria por portal

Modulos principais ja construidos:
- usuarios, perfis e permissoes
- dados institucionais
- escolas
- funcionarios
- alunos
- turmas
- matrículas regulares e AEE
- matriz curricular e disciplinas
- horarios
- diario do professor
- alimentacao escolar
- psicologia/psicopedagogia
- documentos
- relatorios
- auditoria

## Stack

- PHP 8.2+ no estado atual do projeto
- Laravel 11
- MySQL ou MariaDB
- Blade
- Tailwind + Vite
- Spatie Laravel Permission
- PHPUnit

Observacao:
- a especificacao funcional do projeto foi pensada para PHP 8.3
- o ambiente local validado nesta consolidacao estava em PHP 8.2.12

## Instalacao

1. Instale as dependencias do backend:

```bash
composer install
```

2. Instale as dependencias do frontend:

```bash
npm install
```

3. Crie o arquivo de ambiente:

```bash
copy .env.example .env
```

4. Ajuste o `.env` para seu MySQL/MariaDB.

Exemplo:

```env
APP_NAME="Sistema Educacional"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/sistema-escolar/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_escolar
DB_USERNAME=root
DB_PASSWORD=
```

5. Gere a chave da aplicacao:

```bash
php artisan key:generate
```

6. Rode migrations e seeders:

```bash
php artisan migrate --seed
```

7. Gere os assets:

```bash
npm run build
```

Durante desenvolvimento voce pode usar:

```bash
npm run dev
```

## Execucao

Se estiver usando XAMPP, a aplicacao pode ser acessada por:

```text
http://localhost/sistema-escolar/public
```

Se preferir subir pelo Artisan:

```bash
php artisan serve
```

## Usuario Inicial

O seeder padrao cria um usuario administrador inicial:

- Perfil: `Administrador da Rede`
- Login: `admin@sistema.com`
- Senha: `12345678`

Esse usuario serve para o primeiro acesso e para criar os demais perfis e usuarios do sistema.

## Fluxo Inicial Recomendado

1. Entrar com o administrador da rede.
2. Configurar dados institucionais.
3. Cadastrar escolas.
4. Cadastrar funcionarios.
5. Cadastrar usuarios e vincular perfis.
6. Configurar modalidades, disciplinas e matrizes.
7. Operar os modulos escolares a partir da Secretaria Escolar.

## Portais e Escopos

### Secretaria de Educacao
- gestao de usuarios
- dados institucionais
- configuracoes
- escolas
- funcionarios
- base curricular
- documentos institucionais
- relatorios consolidados
- auditoria da rede

### Secretaria Escolar
- alunos
- turmas
- matrículas
- horarios
- alimentacao escolar
- documentos escolares
- relatorios administrativos
- auditoria escolar

### Professor
- dashboard proprio
- minhas turmas
- meu horario
- diario eletronico
- planejamentos
- aulas
- frequencia
- notas/conceitos
- rastros do proprio trabalho

### Coordenacao Pedagogica
- validacao pedagogica
- acompanhamento do diario
- acompanhamento de frequencia e rendimento
- controle pedagogico de horarios e aulas
- documentos e relatorios pedagogicos
- auditoria pedagogica

### Direcao Escolar
- acesso pedagogico e administrativo ampliado no contexto da escola
- validacoes
- justificativas
- liberacao de prazo
- faltas de funcionarios
- fechamento letivo inicial
- documentos e relatorios da escola
- auditoria da direcao

### Nutricionista
- visao gerencial da alimentacao escolar
- comparativos entre escolas
- relatorios da alimentacao
- auditoria da alimentacao

### Psicologia/Psicopedagogia
- atendimentos
- agenda
- historico
- planos de intervencao
- encaminhamentos
- relatorios tecnicos
- documentos sigilosos
- auditoria sigilosa

## Testes

Para rodar a suite principal:

```bash
php artisan test
```

Suite validada nesta etapa:
- autenticacao
- verificacao de email
- profile
- usuarios
- dados institucionais
- escolas
- funcionarios
- matrículas regulares e AEE
- diario
- coordenacao
- direcao
- alimentacao escolar
- nutricionista
- psicologia/psicopedagogia
- documentos
- relatorios
- auditoria

Resultado da consolidacao da ETAPA 24:
- `68 passed`

## Comandos Uteis

Recriar banco local:

```bash
php artisan migrate:fresh --seed
```

Cache de views:

```bash
php artisan view:cache
```

Limpar views:

```bash
php artisan view:clear
```

Listar rotas:

```bash
php artisan route:list
```

## Teste Manual Sugerido

1. Faça login com o administrador inicial.
2. Cadastre ao menos uma escola.
3. Cadastre funcionarios e usuarios com perfis distintos.
4. Entre na Secretaria Escolar e valide alunos, turmas, matrículas e horarios.
5. Entre no Portal do Professor e valide o diario.
6. Entre no Portal da Nutricionista e valide alimentacao e relatorios.
7. Entre no modulo psicossocial e valide sigilo.
8. Emita documentos por portal.
9. Gere relatorios por portal.
10. Consulte a auditoria por portal e confirme o escopo por perfil.

## Observacoes de Ambiente

- Se o navegador continuar exibindo layout antigo depois de alteracoes no Tailwind, rode `npm run build` novamente.
- No ambiente local atual apareceram avisos de CLI sobre `mysqli` e `openssl` carregados em duplicidade. Eles nao impediram migrations, testes nem build, mas vale revisar o `php.ini` do XAMPP para remover a duplicidade.

## Estrutura de Qualidade Consolidada

A ETAPA 24 deixou a base mais consistente em quatro pontos:
- rotas e redirects de autenticacao estabilizados
- validacao de email e profile alinhados ao model `Usuario`
- autorizacao de matrículas revisada
- suite de testes consolidada com cobertura dos fluxos principais
