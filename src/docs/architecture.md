# Architecture

This project is an API-first Laravel application for operating creator collaborations. The backend exposes versioned JSON endpoints that can later serve internal panels, web apps, automations, or external integrations.

## Foundation

- Framework: Laravel 13
- Primary API prefix: `/api/v1`
- Authentication: Laravel Sanctum token authentication
- Database target: PostgreSQL for application environments
- Tests: isolated test database configuration via `phpunit.xml`

## Boundaries

The foundation layer owns authentication, internal users, API routing conventions, response conventions, and documentation. Domain changes will add brands, campaigns, creators, opportunities, collaborations, scores, and operational events in separate increments.

## Current Domain Status

No collaboration domain models are implemented in this foundation change. Future changes should keep controllers thin, use Form Requests for validation, API Resources for responses, and Actions or Services for important state transitions.
