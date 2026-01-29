## What is this
An in-game asset exchange and currency sales site like funpay.com

## Tech stack
- Backend: PHP 8.4 (swoole 6)
- Framework: Hyperf 3.1
- DB: PostgreSQL
- Auth: Keycloak (not fully implemented)
- Infra: Docker (Kubernetes planned)
- Template engine: Blade

## Project structure
- .docker/ - docker related files
- app/ - main application code
    - Domain/ — domain logic
    - Infra/ — infrastructure code
        - Endpoint/ - controllers, http endpoints (domain logic may and often leak here)
    - Shared/ — boilerplate (widely used) code
        - Middleware/ - middlewares
        - Presentation/ - UI related components
- bin - console commands
- config/ - application config (hyperf framework config)
    - routes.php - routing (maps URLs to specific Infra/Endpoint/ classes)
- migrations/ — database migrations
- static/ — frontend assets
- storage/ - misc files
    - view/ - frontend templates (blade)
- test/ - tests (not implemented yet)

More details: .junie/guidelines.md

