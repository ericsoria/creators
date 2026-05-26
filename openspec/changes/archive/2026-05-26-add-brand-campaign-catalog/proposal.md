## Why

The API foundation is in place, but the MVP cannot represent the operational context where creator collaborations happen: brands, cities, tags, and campaigns. This change adds the first domain catalog so later creator, outreach, and collaboration flows have stable entities to attach to.

## What Changes

- Add catalog entities for `City`, `Tag`, `Brand`, and `Campaign`.
- Add relationships between brands, campaigns, cities, and tags.
- Add authenticated `/api/v1` CRUD/list endpoints for brands and campaigns.
- Add authenticated `/api/v1` read/create endpoints for supporting catalog data where appropriate.
- Add request validation, API resources, factories, seeders, tests, OpenAPI updates, and internal documentation.
- Add simple campaign statuses: `draft`, `active`, `paused`, `completed`, and `cancelled`.
- Keep this change limited to the brand/campaign catalog; no creator, opportunity, collaboration, scoring, social account, or outreach logic is introduced.

## Capabilities

### New Capabilities

- `brand-catalog`: Brand management with city and tag relationships for internal campaign operations.
- `campaign-catalog`: Campaign management within brands, including basic lifecycle status and tags.
- `taxonomy-catalog`: Shared city and tag catalog data used to classify brands and campaigns.

### Modified Capabilities

- None.

## Impact

- Adds Laravel models, migrations, relationships, factories, seeders, Form Requests, API Resources, controllers, routes, feature tests, and documentation for cities, tags, brands, and campaigns.
- Extends `docs/openapi.yaml`, `docs/domain.md`, `docs/api.md`, `docs/status-flows.md`, and `docs/changelog.md`.
- Depends on the existing API foundation: `/api/v1` routing, Sanctum authentication, response conventions, and internal users.
