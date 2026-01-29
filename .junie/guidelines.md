# Hyperf 3.1 + DDD (AI Optimized)

### 1. Core & Tech Stack
- PHP 8.4+: `strict_types=1`, PSR-12, SOLID, enums, constructor promotion.
- Code Calisthenics: 1 level indentation, no `else`, no abbreviations.
- Comments: 
    - MUST be in English only.
    - Self-documenting code first: Skip PHPDoc for simple classes where intent is obvious from naming, types, and structure (e.g., `abstract readonly class Id` with `< 1` check).
    - Add PHPDoc only when: logic is non-obvious, hidden dependencies exist, complex branching, or implicit contracts.
    - When documenting: concise technical comments, focus on "What/Why/How", avoid fluff.
    - Describe factory methods logic (e.g., source of data, validation steps).
    - Link to tests: Use `@see` with the test class or file path to help the AI find behavior specifications and usage examples quickly.
- Runtime: Coroutine (Swoole). DB: PostgreSQL (no `down()` in migrations).

### 2. DDD & CQRS Architecture
#### app/Domain (Logic)
- Models: MUST extend `App\Domain\Model\Entity`.
- Repositories: Interface in `Domain\Model\{Model}` with `Repository` suffix.
- Services: Domain services should be in `Domain\Service` if logic involves multiple entities.
- Value Objects (VO):
    - MUST use constructor promotion, NO separate attributes, NO `null` inside.
    - MUST have `private|protected` constructor and `public static of($val): self|static` ONLY for single-component VOs.
    - Composite VOs MUST use `public` constructor, NO `of()` method.
    - MUST have `public static try(mixed $val): ?static` and `public static tryKey(array $arr, string $key): ?static`.
    - MUST be atomic: NO `mixed` or `array` in constructors. Only primitives or other VOs.
    - Enums: Use `BackedEnum` for status or type fields.
    - String VO: Extend `App\Shared\Domain\Primitive\StringValue`, implement `validate(string $value): void`. Input is `trim()` automatically.
    - Boolean VO: `__toString()` returns `+` (true) / `-` (false).
    - Simple non-string VO: MUST be `readonly class`.
    - Validation/Exceptions: Throw custom exceptions inheriting from `DomainError`. Each error MUST be a separate class with a constant message.

#### app/Infra (Implementation)
- Discovery: Naming MUST follow patterns to enable instant AI discovery (e.g., `AccountRepository` -> `DatabaseAccounts`).
- Repositories: In `Storage\Repository`, NO `Repository` suffix (e.g. `DatabaseAccounts`).
- Query Services (CQRS): In `Storage\Query`, for UI/reading. MUST return DTOs/objects, NOT Entities. NO suffix (e.g. `DatabaseAccounts`). Repositories MUST NOT be used for read operations; use Query Services instead.
- Endpoints:
    - Base: `$this->request`, `$this->renderer`, `isPost()`, `render()`.
    - Admin: Extends `AdminEndpoint`, `payload(): array`.
    - Auto-mapping: `rendering() => true` maps to `admin/{class_path}.blade.php`.
    - Routes: `config/routes.php` maps URLs directly to Endpoint classes.
    - RESTful Conventions: Use standard HTTP methods for resource operations:
        - `GET /<resources>` - List collection.
        - `GET /<resources>/<id>` - Get single resource.
        - `POST /<resources>` - Create new resource.
        - `PUT /<resources>/<id>` - Replace resource.
        - `PATCH /<resources>/<id>` - Partially update resource.
        - `DELETE /<resources>/<id>` - Delete resource.

#### app/Shared
- Shared VO, Primitives (`StringValue`), Utils.
- Structural Annotations: Classes MUST have `@see` links to usage examples in `Domain` or `Infra`.
- `app/Shared/Infra`: Infrastructure-related shared code (Middlewares, RequestParams, global Functions).
- `app/Shared/Presentation`: Shared UI logic (e.g., `Grid` system).

### 3. UI & Styling
- Admin UI: Blade, MUST extend `layouts.admin`, use `x-table`.
- Styling: 
    - Always check existing CSS files (`fa.css`, `bootstrap.css`) before adding rules to `main.css`.
    - NO duplication of library styles (e.g. Font Awesome families/icons).
    - Prefer CSS + ARIA attributes (`aria-sort`) over JS DOM manipulation for visual states.
    - Touch targets for interactive elements MUST be mobile-friendly.
- Forms:
    - All form-related JavaScript MUST be placed in `static/form.js`.
    - Use modular and reusable classes or functions (e.g. `ModalFormHandler`) for form behavior.
    - Use `data-*` attributes for configuration (e.g. `data-add-title`, `data-edit-action`) instead of hardcoding values in JS.

### 4. Testing
- Tests: `final`, extend `TestCase`, namespace `HyperfTest\Unit`. Structure MUST mirror `app/`.
- MUST use `#[CoversClass(Target::class)]` for two-way linking.
- Test methods MUST use `camelCase`.
- DataProviders MUST be `public static`, return `array` with string keys, and use `camelCase`.
- Logic: One test class per production class. Use `#[Test]`, `#[DataProvider]`.
- Abstract Classes: DO NOT test abstract classes directly. Test only their concrete implementations.
- Scenarios: MUST test both positive and negative scenarios (errors, invalid input) and edge cases.
- Mocking: NEVER use mocks or Mockery. Always use actual objects, VOs, DTOs, or anonymous classes if necessary.

```php
final class MyVO extends StringValue {
    public static function validate(string $value): void {
        if ($value === '') throw new MyError();
    }
}
// Usage: MyVO::of('val');
```
