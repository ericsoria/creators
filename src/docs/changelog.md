# Changelog

## 2026-05-25

### Added

- Added API foundation planning and implementation baseline.
- Added Sanctum token authentication for protected `/api/v1` routes.
- Added internal user roles: `admin`, `manager`, `operator`, and `viewer`.
- Added current-user endpoint documentation for `GET /api/v1/user`.
- Added initial architecture, API, auth, OpenAPI, and status-flow documentation.

## 2026-05-26

### Added

- Added city, tag, brand, and campaign catalog models.
- Added authenticated `/api/v1` catalog endpoints for cities, tags, brands, and campaigns.
- Added brand city/tag relationships and campaign tag relationships.
- Added campaign statuses: `draft`, `active`, `paused`, `completed`, and `cancelled`.
- Added catalog filtering, pagination, tests, OpenAPI documentation, and domain documentation.

### Added

- Added creator lead recruiting, creator network, and social account models.
- Added authenticated creator lead, creator, and social account API endpoints.
- Added creator lead approval flow that creates creators and initial social accounts.
- Added creator city/tag relationships and brand/creator social account ownership.
- Added creator network filters, pagination, tests, OpenAPI documentation, and domain documentation.
- Added a Vue 3 + Tailwind operations SPA served at `/app`.
- Added API token login/logout endpoints for the SPA.
- Added drawer-first CRUD/list screens for catalog and creator network records.
- Added `src/docs/ui.md` with design tokens, accessibility rules, and externalization guidance.
- Added opportunity pipeline and opportunity event API for campaign-to-creator outreach.

## 2026-05-28

### Changed

- Replaced creator lead recruiting with shared prospect leads for creator and brand prospects.
- Replaced `/api/v1/creator-leads` with `/api/v1/prospects` and type-specific approval endpoints.
- Renamed lead classification from `niche` to `category` and added `prospect_type`.
