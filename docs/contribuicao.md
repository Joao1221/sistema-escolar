# Guia de Contribuicao

## Setup do Ambiente

1. **Clone e instale dependencias**:
   ```bash
   composer install
   npm install
   ```

2. **Copie o .env**:
   ```bash
   copy .env.example .env
   ```

3. **Configure o banco** no `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistema_escolar
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Rode migrations**:
   ```bash
   php artisan migrate --seed
   ```

5. **Build assets**:
   ```bash
   npm run build
   ```

## Execucao

Desenvolvimento:
```bash
npm run dev
```

Servidor:
```bash
php artisan serve
```

Acesso: `http://localhost:8000`

Usuario inicial: `admin@sistema.com` / `12345678`

## Padroes de Codigo

### PHP
- Use **PSR-12**
- Execute `vendor/bin/pint` antes de commit
- Tipagem explícita em metodos
- Use Enums para status/tipos

### Laravel
- Controllers finos — negocio em Services
- Form Requests para validacao
- Services para regra de negocio
- Models com scopes e accessors
- Migrations com foreign keys

### Estrutura de Arquivo
```php
<?php

namespace App\Services;

use App\Models\X;
use Illuminate\Support\Facades\DB;

class XxxService
{
    public function metodo(Type $param): ReturnType
    {
        // ...
    }
}
```

## Criando um Novo Service

1. Crie em `app/Services/NomeService.php`
2. Use tipagem de retorno
3. Documente metodos principais
4. Adicione testes unitarios

## Criando um Novo Enum

1. Crie em `app/Enums/NomeEnum.php`
2. Use backed enum (string):
   ```php
   enum Status: string
   {
       case Ativo = 'ativo';
       case Inativo = 'inativo';
       
       public function label(): string { ... }
   }
   ```
3. Use em Services, Requests, Models
4. Evite cast automatico no Model (use string no DB)

## Testes

Roda suite completa:
```bash
php artisan test
```

Roda suite especifica:
```bash
php artisan test --filter=MatriculaAeeTest
```

Crie testes em:
- `tests/Unit/` — testes de servicos e classes
- `tests/Feature/` — testes de fluxo (use RefreshDatabase)

Boas praticas:
- Nome descritivo: `test_usuario_pode_alterar_proprio_perfil`
- Use factories para dados de teste
- Teste comportamento, nao implementacao

## Git

1. Crie branch: `feature/nova-funcionalidade`
2. Execute `pint` antes de commit
3. Rode testes antes de push
4. Crie Pull Request

## Comandos Uteis

```bash
# Limpar cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Migrations
php artisan migrate
php artisan migrate:fresh --seed
php artisan migrate:rollback

# Rotas
php artisan route:list
php artisan route:list --name=admin

# Tinker (debug)
php artisan tinker
```

## Estrutura de Arquivos de Teste

```php
namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // setup roles, permissions, etc
    }

    public function test_descricao(): void
    {
        $usuario = Usuario::factory()->create();
        
        $response = $this->actingAs($usuario)
            ->get('/rota');
            
        $response->assertOk();
    }
}
```

## Dicas

- Use `dd()`, `dump()` para debug
- Use Tinker para testar queries
- Verifique queries com `DB::getQueryLog()`
- Use factories para dados consistentes
- Execute testes frequentemente
