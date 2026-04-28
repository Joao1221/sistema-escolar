# Arquitetura do Sistema

## Visao Geral

Sistema educacional municipal em Laravel com arquitetura por portais e escopo por perfil de acesso.

## Stack

- **PHP**: 8.2+
- **Laravel**: 11
- **Banco**: MySQL/MariaDB
- **Frontend**: Blade + TailwindCSS + Vite
- **Autenticacao**: Laravel Breeze (Session)
- **Permissoes**: Spatie Laravel Permission

## Estrutura de Pastas

```
app/
├── Console/
├── Events/
├── Exceptions/
├── Http/
│   ├── Controllers/      # Organizado por portal
│   │   ├── Secretaria/       # Rede municipal
│   │   ├── SecretariaEscolar/ # Escola
│   │   ├── Professor/
│   │   ├── Nutricionista/
│   │   └── PsicologiaPsicopedagogia/
│   ├── Middleware/
│   ├── Requests/        # Form Requests por dominio
│   └── Resources/
├── Models/
├── Providers/
├── Services/            # Camada de negocio
├── Enums/               # Status e tipos de dominio
└── Support/            # Traits e helpers
```

## Portais e Modulos

| Portal | Escopo | Controllers |
|--------|--------|-------------|
| Secretaria | Rede municipal | 9 |
| SecretariaEscolar | Escola | 32 |
| Professor | Diario | 4 |
| Nutricionista | Alimentacao | 3 |
| Psicologia/Psicopedagogia | Sigiloso | 2 |

## Padroes Adotados

### Controllers
- Finos (apenas orquestram requisicao/resposta)
- Injeta servicos via construtor
- Retorna `View` ou `RedirectResponse`
- Validacao via Form Requests

### Services
- Contem regra de negocio
- Injetam outros services e models
- Transacoes via `DB::transaction`
- Tipagem explícita de retornos

### Models
- Relacionamentos definidos
- Scopes para filtros comuns
- Accessors para formatacao
- casts para tipos (date, boolean, encrypted)

### Enums (Onda 3)
Localizados em `app/Enums/`:
- `StatusMatricula` — ativa, concluida, trancada, transferida, cancelada
- `TipoMatricula` — regular, aee
- `StatusDemandaPsicossocial` — aberta, em_triagem, em_atendimento, encerrada, observacao
- `StatusAtendimentoPsicossocial` — aberto, em_atendimento, encerrado
- `TipoPublicoPsicossocial` — aluno, professor, funcionario, responsavel, coletivo
- `StatusPendenciaDiario` — aberta, em_andamento, resolvida

### Views
- Blade components
- Layouts por portal
- Partials reutilizaveis
- Minima logica (accessors no model)

## Autenticacao e Autorizacao

- **Auth**: Laravel Breeze com guard `web`
- **Roles**: Spatie (Administrador da Rede, Diretor, Coordenador, etc)
- **Permissions**: Granular por acao/portal
- **Policies**: Para recursos especificos (AlunoPolicy, EscolaPolicy, etc)
- **Gates**: Para verificacoes transversais

## Banco de Dados

### Migrations
- Estrutura em `database/migrations/`
- Timestamps no formato `YYYY_MM_DD_HHMMSS`
- Foreign keys com cascade/restrict conforme necessidade

### Models
- `Usuario` — usuarios do sistema
- `Aluno` — dados do aluno
- `Matricula` — matricula regular/AEE
- `Turma` — turmas por escola
- `DiarioProfessor` — diario eletronico
- `AtendimentoPsicossocial` — atendimentos sigilosos
- `DemandaPsicossocial` — demandas psicossociais

## Testes

### Suite atual (69 testes)
- **Unit**: 17 testes (Enums, Services)
- **Feature**: 52 testes (fluxos por portal)

Executar:
```bash
php artisan test
```

## Configuracao

Variaveis de ambiente em `.env`:
- `DB_CONNECTION=mysql`
- `SESSION_DRIVER=database`
- `QUEUE_CONNECTION=database`
- `CACHE_STORE=database`

## Fluxo de Requisicao

```
Request
  → Middleware (auth, role, permission)
    → Controller
      → Service (regra de negocio)
        → Model (Eloquent)
          → Database
```

## Decisoes Tecnicas

1. **Session Auth** — sistema web interno, sem API publica
2. **Database Session** — multiplos usuarios simultaneos
3. **Database Queue** — processamentos pesados (relatorios)
4. **Database Cache** — dados que mudam com frequencia
5. **Enum como valor (string)** — manter retrocompatibilidade com dados existentes
