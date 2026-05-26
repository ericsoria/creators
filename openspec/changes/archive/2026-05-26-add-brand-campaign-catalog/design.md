## Context

The API foundation now provides authenticated `/api/v1` routing, internal users, JSON response conventions, OpenAPI documentation, and tests. The next MVP layer is the operational catalog that gives future creator outreach and collaboration flows a place to live: brands, campaigns, cities, and tags.

This change introduces the first domain models but intentionally avoids creators, opportunities, collaborations, scoring, and social accounts. The goal is to make it possible to create a brand, classify it by city/tag, create campaigns under that brand, and list/filter those records through the protected API.

## Goals / Non-Goals

**Goals:**

- Add persistent `City`, `Tag`, `Brand`, and `Campaign` models.
- Support many-to-many relationships for brand cities, brand tags, and campaign tags.
- Expose authenticated REST-style endpoints under `/api/v1` for brands and campaigns.
- Expose authenticated catalog endpoints for cities and tags.
- Use Form Requests, API Resources, factories, seeders, tests, OpenAPI, and docs consistently with the API foundation.
- Add basic list pagination, sorting, and common filters for catalog endpoints.

**Non-Goals:**

- No creator network, creator leads, social accounts, opportunities, collaborations, events, metrics, or scoring.
- No complex campaign state machine beyond storing and validating allowed status values.
- No public endpoints.
- No frontend or admin panel.
- No granular permissions beyond requiring authenticated internal users.

## Decisions

### Use simple Eloquent models with explicit relationships

The catalog layer should use conventional Eloquent models and relationships: `Brand hasMany Campaigns`, `Brand belongsToMany Cities`, `Brand belongsToMany Tags`, and `Campaign belongsToMany Tags`. This keeps later domain work straightforward and avoids premature abstraction.

Alternative considered: generic catalog tables for all entities. Rejected because brands and campaigns have distinct fields and will become central domain concepts.

### Use pivot tables for classification

Cities and tags are shared classification data. Brand-city, brand-tag, and campaign-tag relationships should use pivot tables rather than JSON arrays so the API can filter, validate, and enforce referential integrity.

Alternative considered: store city/tag ids as JSON on brands and campaigns. Rejected because it weakens relational integrity and makes filtering harder.

### Keep campaign status validation simple

Campaign status should be validated against `draft`, `active`, `paused`, `completed`, and `cancelled`. This change should not introduce transition endpoints yet; those can be added when operational workflow demands it.

Alternative considered: implement explicit status transition actions immediately. Rejected because early campaign catalog needs CRUD and filtering, not workflow automation.

### Soft delete primary catalog entities

Brands, campaigns, and tags should use soft deletes because future operational history may reference them. Cities can remain non-soft-deleted catalog seed data unless implementation finds a concrete need.

Alternative considered: hard-delete all catalog entities. Rejected for brands and campaigns because it can break historical reporting once opportunities and collaborations exist.

### Use slugs as stable human-readable identifiers, not route keys yet

Brands, cities, and tags should store slugs, but initial API route model binding can remain id-based for simplicity. Slugs can later be promoted to route keys if the frontend or integrations need human-readable URLs.

Alternative considered: slug-based route binding now. Rejected because internal APIs benefit more from stable numeric ids during early development.

## Risks / Trade-offs

- Broad CRUD can sprawl into domain behavior → Mitigate by keeping this change limited to catalog persistence and basic validation.
- Tag taxonomy may become inconsistent → Mitigate with a required `type` field and seed common initial tag types.
- Campaign status may need transition rules later → Mitigate by documenting this as storage-only status for now and keeping transition endpoints out of scope.
- Many filters can slow implementation → Mitigate by adding only MVP filters: status, brand, city, tag, and date range where relevant.
- Soft-deleted records may affect uniqueness → Mitigate by defining clear uniqueness expectations during implementation and adding tests for slug behavior.

## Migration Plan

1. Add migrations for cities, tags, brands, campaigns, and pivot tables.
2. Add models, relationships, factories, and seeders.
3. Add Form Requests, API Resources, and controllers.
4. Add `/api/v1` routes protected by Sanctum.
5. Add feature tests for authenticated CRUD/listing, validation, filters, relationships, and soft deletes.
6. Update OpenAPI and internal docs.

## Open Questions

- Should `City` be read-only after seeding, or should admins be able to create cities through the API immediately?
- Should tags be globally unique by slug, or unique by `type` plus slug?
- Should campaigns support city targeting directly now, or only inherit location context through brand and future opportunity selection?
