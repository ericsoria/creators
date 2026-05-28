# Architecture

This project is an API-first Laravel application for operating creator collaborations. The backend exposes versioned JSON endpoints that can later serve internal panels, web apps, automations, or external integrations.

## Foundation

- Framework: Laravel 13
- Primary API prefix: `/api/v1`
- Authentication: Laravel Sanctum token authentication
- Operations UI: Vue 3 SPA served at `/app`
- Database target: PostgreSQL for application environments
- Tests: isolated test database configuration via `phpunit.xml`

## Frontend Architecture

The internal operations UI is intentionally API-driven. Laravel serves the compiled Vue shell, but all business data and mutations go through `/api/v1`. The frontend keeps API URL handling in `resources/js/operations/config.js` and request/auth behavior in `resources/js/operations/api.js` so the app can later move to a standalone repository with minimal changes.

Auth tokens are issued by `/api/v1/auth/login`, stored by the Vue auth module, attached as bearer tokens, and revoked by `/api/v1/auth/logout`. UI conventions and design tokens are documented in `docs/ui.md`.

## Boundaries

The foundation layer owns authentication, internal users, API routing conventions, response conventions, and documentation. Domain changes will add brands, campaigns, creators, opportunities, collaborations, scores, and operational events in separate increments.

## Current Domain Status

No collaboration domain models are implemented in this foundation change. Future changes should keep controllers thin, use Form Requests for validation, API Resources for responses, and Actions or Services for important state transitions.
