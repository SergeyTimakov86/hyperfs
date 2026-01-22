# Project Guidelines

## Overview
This project is built using the **Hyperf 3.1** framework, running on **Swoole/Swow**. It follows a structure inspired by **Domain-Driven Design (DDD)**.

## Technical Stack
- **PHP:** 8.4+ (strict types required)
- **Framework:** Hyperf 3.1
- **Database:** PostgreSQL
- **View Engine:** Blade
- **Runtime:** Coroutine-based (Swoole or Swow)

## Directory Structure
- `app/Domain`: Core business logic, Enums, and Domain Models.
- `app/Infra`: Infrastructure layer, including HTTP Endpoints and external services.
- `app/Shared`: Common utilities, middlewares, and reusable UI components (Grid, Sort).
- `storage/view`: Blade templates.

## Coding Standards

### General Rules
- Always use `declare(strict_types=1);`.
- Use PHP 8.4+ features (enumerations, constructor property promotion, etc.).
- Follow PSR-12 coding standards.

### Endpoints
Instead of traditional Controllers, use the `Endpoint` pattern:
- Define routes in `config/routes.php` mapping to an Endpoint class.
- Endpoints should inherit from `App\Infra\Endpoint` or `App\Infra\AdminEndpoint`.
- Implement business logic in the `payload()` method.
- For Admin pages, set `rendering()` to return `true` to enable automatic Blade template rendering.

Example:
```php
final class MyEndpoint extends AdminEndpoint
{
    protected function payload(): array
    {
        return ['key' => 'value'];
    }

    protected static function rendering(): bool
    {
        return true;
    }
}
```

### Domain Models
- Use Enums for fixed sets of data (like `Game`).
- Keep business logic within the Domain layer.

### Database
- Use migrations for all schema changes (`migrations/`).

### UI & Templates
- Templates are located in `storage/view`.
- Use the `x-table` components for data grids.
- Administrative pages should extend `layouts.admin`.

## Testing
- Tests are located in `test/Cases`.
- Use `composer test` to run the suite.
- For new features, add corresponding tests in the `test/` directory.
