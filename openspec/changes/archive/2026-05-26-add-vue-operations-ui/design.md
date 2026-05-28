## Context

The backend exposes an authenticated `/api/v1` API for users, brands, campaigns, cities, tags, creator leads, creators, and social accounts. The current frontend is the default Laravel/Vite scaffold and does not provide an operator interface.

The user wants Vue 3 + Tailwind and explicitly wants the UI to consume the API so the frontend can later be externalized. This rules out Inertia or server-rendered CRUD as the primary architecture. The first UI should feel like a minimal operations cockpit: quiet, fast, focused on records and actions, with no decorative dashboard noise.

## Goals / Non-Goals

**Goals:**

- Build a Vue 3 SPA inside the current Laravel repo for now.
- Consume `/api/v1` for all business data and mutations.
- Use Tailwind for a restrained, minimal design system.
- Add token-based login/session handling compatible with the existing Sanctum API token approach.
- Provide dashboard, catalog management, creator network management, and social account management UI.
- Use drawer-first detail/edit patterns to reduce page churn and visual noise.
- Keep the UI structured so it can later move to a standalone frontend repository with minimal changes.

**Non-Goals:**

- No Inertia dependency.
- No server-rendered CRUD as the main product interface.
- No public marketplace, creator portal, or brand portal.
- No opportunities, collaborations, scoring, analytics-heavy charts, or automations in this change.
- No large component library unless a small dependency is clearly justified.

## Decisions

### Use Vue 3 SPA with Vue Router

The frontend should use Vue 3 and Vue Router to create a client-side operations app. Laravel serves the compiled app shell in this repository, but the frontend treats the backend as an API provider.

Alternative considered: Inertia + Vue. Rejected because the user wants the frontend to consume the API and remain easy to externalize.

### Use Tailwind with a custom minimal design language

Tailwind should provide layout, spacing, type, and state primitives. The UI should use a calm off-white background, restrained contrast, compact tables, precise typography, and a single controlled accent color. It should avoid generic SaaS gradients, noisy KPI grids, and decorative charts.

Initial tokens should be documented in `src/docs/ui.md` and implemented in Tailwind/app CSS:

- Background: `#F7F3EC`
- Surface: `#FFFDF8`
- Text: `#171512`
- Muted text: `#6F6A61`
- Border: `#E4DED2`
- Primary accent: `#5F6F52`
- Success: `#2F6B4F`
- Danger: `#A33A2B`
- Optional warm attention: `#9A3412`

Alternative considered: installing a full UI kit. Rejected initially because a bespoke operational UI can stay lighter and more distinctive with a small set of local components.

### Keep API access behind a dedicated client layer

All HTTP calls should go through a small API client that owns base URL, bearer token attachment, JSON parsing, validation errors, unauthorized handling, and pagination shape normalization. This keeps externalization straightforward because only environment configuration should change.

Alternative considered: calling `fetch` directly from pages. Rejected because it scatters auth/error behavior and makes future extraction harder.

### Token auth for the SPA

The SPA should authenticate with API tokens rather than Laravel session pages. The implementation must provide `/api/v1/auth/login` for token issuing and `/api/v1/auth/logout` for current-token revocation if they are not already present. The login response should include the token and current user payload needed by the SPA.

Alternative considered: manually creating tokens via Tinker for operators. Rejected for real use because it blocks a usable login flow.

### Drawer-first CRUD

List pages should remain the primary work surface. Record details and edit forms should open in a right-side drawer where possible. Full pages are reserved for flows that need more space later.

Drawers must trap focus while open, close on Escape, restore focus to the trigger after close, expose an accessible label, and not hide API validation errors. Forms inside drawers must disable duplicate submits during async requests and preserve entered values when validation fails.

Alternative considered: separate create/edit/show pages for every resource. Rejected because it creates more navigation noise and slows operational work.

### Dashboard shows attention, not decoration

The dashboard should show actionable counts and shortcuts: leads by status, active campaigns, draft campaigns, recently added creators, and social accounts needing review if applicable. Charts are only acceptable if they directly support a decision.

Implementation should prefer a dedicated `/api/v1/dashboard/overview` endpoint if it can be added cleanly. If not, the dashboard may compose existing paginated API endpoints with explicit low page sizes and filters, but that approximation must stay behind a dashboard client module so it can be replaced later without changing UI components.

Alternative considered: a generic analytics dashboard. Rejected because the current domain needs operational clarity more than reporting.

### Responsive and accessible operations surfaces

Tables should remain compact on desktop and degrade intentionally on narrow screens. At `375px` width, each resource list must either use horizontal scrolling with sticky context where practical or switch to readable stacked cards. Interactive elements must be semantic buttons/links, inputs must have labels, focus states must be visible, color contrast must remain accessible, and motion should respect `prefers-reduced-motion`.

## Risks / Trade-offs

- API has no login endpoint yet -> Mitigation: add a minimal API token login/logout flow before protected UI routes are usable.
- Duplicating backend validation client-side can drift -> Mitigation: keep client validation light and rely on API validation errors for truth.
- Drawer-first UI can become cramped -> Mitigation: use drawers for common CRUD and allow full-page flows later for complex workflows.
- Externalization can be undermined by Laravel-specific assumptions -> Mitigation: keep route URLs, API base URL, and auth storage in frontend configuration/client modules.
- Minimal UI can hide important actions -> Mitigation: use clear empty states, focused primary actions, and consistent table actions.
- Mobile tables can become unusable -> Mitigation: define responsive table/card behavior before implementing each resource list.

## Migration Plan

1. Add Vue 3, Vue Router, and frontend app structure under `resources/js`.
2. Add/confirm Tailwind setup and app CSS tokens.
3. Add API auth endpoints if missing.
4. Add API client, auth store, router guards, app shell, and login page.
5. Add reusable UI primitives, accessibility behavior, and layout patterns.
6. Add dashboard and CRUD/list/detail flows for catalog and creator network resources.
7. Add documentation, frontend tests for API/auth/router behavior, and build/test verification.

## Open Questions

- Should auth tokens be stored in `localStorage` for simplicity or in a more constrained storage strategy with shorter-lived tokens?
- Should the SPA be served at `/app` only, leaving `/` as default/marketing, or should `/` redirect to the operations UI?
- Which future standalone frontend hostname should be used when documenting CORS/externalization examples?
