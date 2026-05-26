## Why

The project is currently a clean Laravel skeleton, while the MVP requires a robust internal API foundation before adding domain flows for brands, creators, opportunities, and collaborations. Establishing authentication, versioned routing, response conventions, documentation, and testing now prevents every later feature from inventing its own structure.

## What Changes

- Add an authenticated, versioned API foundation under `/api/v1`.
- Add Laravel Sanctum-based authentication for internal users and API clients.
- Add simple internal user roles: `admin`, `manager`, `operator`, and `viewer`.
- Define consistent JSON response shapes for successful resources, paginated lists, validation errors, and unauthorized access.
- Add project documentation scaffolding under `docs/`, including OpenAPI, auth, architecture, API, status flows, and changelog documents.
- Add testing foundations for API authentication, authorization, response shape, and documentation expectations.
- Prepare the application for PostgreSQL-backed development while keeping tests isolated and repeatable.

## Capabilities

### New Capabilities

- `api-foundation`: Versioned API routing, JSON response conventions, protected private routes, and project documentation baseline.
- `internal-auth`: Internal user authentication with Sanctum tokens and simple role-based access rules.

### Modified Capabilities

- None.

## Impact

- Affects Laravel application bootstrap/routing, API route definitions, auth configuration, user model fields, database migrations, seeders, middleware/policies, tests, and documentation.
- Adds or updates `docs/architecture.md`, `docs/api.md`, `docs/auth.md`, `docs/openapi.yaml`, `docs/status-flows.md`, and `docs/changelog.md`.
- Introduces Laravel Sanctum as a runtime dependency.
