## Why

The backend now exposes the core internal API for brands, campaigns, taxonomy, creator leads, creators, and social accounts, but operators need a focused interface to manage the workflow without touching raw API clients. The UI should be a decoupled Vue 3 application that consumes `/api/v1` so it can live inside the Laravel repo now and be externalized later without changing product behavior.

## What Changes

- Add a Vue 3 + Tailwind operations UI that consumes the existing authenticated API instead of using server-rendered CRUD pages.
- Add a minimal internal app shell with login, token persistence, navigation, route guards, loading/error states, and logout.
- Add an operational dashboard focused on attention and next actions, not decorative charts.
- Add CRUD/list/detail interfaces for brands, campaigns, cities, tags, creator leads, creators, and social accounts.
- Add drawer-first record inspection and compact forms to reduce navigation noise.
- Add creator lead approval UI that calls the existing API approval endpoint.
- Add reusable table, filter, empty-state, form, drawer, badge, and confirmation patterns.
- Add minimal API token login/logout endpoints if missing so operators can authenticate without manual token setup.
- Keep the UI minimal, calm, and operational: no noisy widgets, no generic SaaS dashboard clutter, and no marketplace-facing views.
- Keep opportunities, collaborations, scoring, analytics-heavy dashboards, and public creator/brand portals out of scope.

## Capabilities

### New Capabilities

- `vue-operations-shell`: Vue 3 SPA shell, API auth client, routing, layout, navigation, and shared UI primitives.
- `operations-dashboard`: Minimal operational overview driven by existing API data and focused on actionable counts.
- `catalog-management-ui`: UI for managing brands, campaigns, cities, and tags through `/api/v1` endpoints.
- `creator-network-ui`: UI for managing creator leads, approving leads, creators, and social accounts through `/api/v1` endpoints.

### Modified Capabilities

- `internal-auth`: add API token login and logout/revocation endpoints for the Vue SPA.

## Impact

- Adds frontend dependencies for Vue 3, Vue Router, and any minimal supporting packages needed for API-driven UI state.
- Updates Vite/Tailwind/frontend entrypoints under `src/resources`.
- Adds API auth endpoints and OpenAPI/docs coverage if they are not already present.
- Adds frontend documentation for API consumption, auth token handling, UI conventions, accessibility expectations, and externalization path.
- May add a non-API web fallback route that serves the Vue app, but all business data must be fetched from `/api/v1`.
