## Context

The application is currently a fresh Laravel 13 project located under `src/` with only the default `User` model, initial migrations, web routing, and example tests. The MVP document defines a backend-first internal API for managing brands, campaigns, creators, opportunities, and collaborations, but those domain features need a shared foundation before they are built.

This change establishes the API baseline that later domain changes will reuse: authenticated `/api/v1` routing, internal users, simple roles, consistent JSON responses, OpenAPI documentation, and repeatable tests.

## Goals / Non-Goals

**Goals:**

- Provide an authenticated API foundation for all future private endpoints.
- Add simple internal role support without introducing a full permissions package.
- Establish `/api/v1` route organization and JSON response conventions.
- Add documentation scaffolding required by the MVP: architecture, API, auth, OpenAPI, status flows, and changelog.
- Add tests that prove private routes require authentication and responses follow the expected API shape.
- Align local development with PostgreSQL as the intended application database.

**Non-Goals:**

- No brand, campaign, creator, opportunity, collaboration, scoring, or social account domain models.
- No granular permission system beyond simple roles.
- No OAuth2, Passport, third-party identity provider, or public API client flow.
- No frontend, admin panel, or UI work.
- No production deployment automation.

## Decisions

### Use Laravel Sanctum for internal API auth

Sanctum is the smallest Laravel-native authentication layer that fits an internal API and token-based clients. It avoids the complexity of OAuth2 while keeping the option open to add Passport later if external third-party clients become a real requirement.

Alternative considered: Laravel Passport. Rejected for MVP because OAuth2 adds client management and token flow complexity before there are external integrations.

### Keep roles as a user column for MVP

The initial roles are `admin`, `manager`, `operator`, and `viewer`. A simple `role` column is enough for coarse internal authorization and avoids adding a permissions package before access rules are proven by real operations.

Alternative considered: a role/permission package. Rejected for this phase because the MVP only needs simple role gates and the document explicitly allows roles before granular policies or permissions.

### Introduce `routes/api.php` with `/api/v1` grouping

Laravel's API routes should be separated from `routes/web.php`, mounted through application routing, and grouped under `/api/v1`. All private v1 routes should live behind `auth:sanctum` unless explicitly documented as public.

Alternative considered: define API routes in `web.php`. Rejected because it blurs web and API concerns and conflicts with the API-first direction.

### Use API Resources and standard JSON shapes

Responses should return resources as `{ "data": ... }`, paginated lists as `{ "data": [], "meta": {}, "links": {} }`, and errors using Laravel-compatible `{ "message": ..., "errors": ... }` structures. The foundation should include examples and helper conventions, but domain-specific resources arrive in later changes.

Alternative considered: custom response envelope everywhere. Rejected because Laravel API Resources already provide the expected structure with less custom code.

### Start documentation before domain features

The MVP treats OpenAPI and internal docs as part of the definition of done. This change creates the documentation skeleton and documents only the foundation endpoints initially, especially auth and health/current-user style endpoints.

Alternative considered: add docs after the first domain feature. Rejected because delayed documentation tends to diverge from the API shape.

### Target PostgreSQL for development while allowing isolated tests

The product database should be PostgreSQL because the domain is relational and will use foreign keys, timestamps, and JSON metadata. Tests may use an isolated test database configuration as long as behavior remains compatible with PostgreSQL constraints.

Alternative considered: SQLite-only MVP. Rejected as the main development database because it can hide differences in constraints and JSON handling that matter for this domain.

## Risks / Trade-offs

- Sanctum setup differs depending on token-based vs SPA-cookie auth → Mitigate by documenting the MVP mode explicitly as internal API token auth.
- Simple roles may become too coarse → Mitigate by keeping authorization checks behind policies, gates, or middleware so a later permissions system can replace internals without rewriting controllers.
- Documentation can become stale → Mitigate by adding changelog and OpenAPI updates as explicit tasks and test/review criteria for each later feature.
- PostgreSQL setup adds local friction → Mitigate with clear `.env.example` defaults and documentation for required database variables.
- Response conventions may be interpreted inconsistently by later features → Mitigate by documenting examples and adding tests around representative foundation endpoints.

## Migration Plan

1. Add the dependency and configuration needed for Sanctum.
2. Add or update database schema for user roles and API tokens.
3. Add API route mounting and `/api/v1` grouping.
4. Add private foundation endpoints and authentication tests.
5. Add docs and OpenAPI scaffolding.
6. Keep rollback simple by reverting the change before domain features depend on it.

## Open Questions

- Should local tests use PostgreSQL as well, or use SQLite for faster isolated test runs until PostgreSQL-specific behavior appears?
- Should the MVP expose a `POST /api/v1/auth/logout` endpoint immediately, or is token revocation through a current-user token endpoint enough for the first internal API?
- Should `viewer` be allowed to access every read endpoint by default, or should this be decided per domain feature as those endpoints are introduced?
