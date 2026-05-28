## 1. Frontend Foundation

- [x] 1.1 Install Vue 3, Vue Router, and minimal frontend dependencies needed for the SPA.
- [x] 1.2 Configure Vite to mount the Vue app from the existing Laravel frontend entrypoint.
- [x] 1.3 Configure Tailwind app CSS tokens for the minimal operations UI visual language.
- [x] 1.4 Add a Laravel web fallback route that serves the Vue app without embedding business data.
- [x] 1.5 Add frontend environment configuration for API base URL so the app can be externalized later.
- [x] 1.6 Document design tokens and UI conventions in `src/docs/ui.md`.

## 2. API Authentication

- [x] 2.1 Add or verify API token login endpoint for the Vue app if one does not already exist.
- [x] 2.2 Add logout/token revocation endpoint for the Vue app.
- [x] 2.3 Document login, logout, and current user endpoints in `src/docs/openapi.yaml`.
- [x] 2.4 Add Vue auth state handling for token persistence, current user loading, and logout.
- [x] 2.5 Add router guards for authenticated and unauthenticated routes.
- [x] 2.6 Add tests or verification covering unauthenticated redirects and authenticated `/api/v1/user` loading.

## 3. API Client Layer

- [x] 3.1 Add a shared API client that owns base URL, bearer token headers, JSON parsing, and unauthorized handling.
- [x] 3.2 Add common helpers for paginated responses, validation errors, loading states, and retry behavior.
- [x] 3.3 Add resource clients for brands, campaigns, cities, tags, creator leads, creators, and social accounts.
- [x] 3.4 Ensure pages do not call `fetch` directly outside the API client layer.

## 4. App Shell And UI Primitives

- [x] 4.1 Add Vue Router routes for login, dashboard, brands, campaigns, cities, tags, creator leads, creators, and social accounts.
- [x] 4.2 Add the authenticated app layout with quiet navigation, topbar, and responsive behavior.
- [x] 4.3 Add reusable UI primitives for buttons, fields, selects, checkboxes, badges, tables, filters, drawers, empty states, loading states, errors, and confirmations.
- [x] 4.4 Add a consistent drawer-first detail/edit interaction pattern.
- [x] 4.5 Ensure keyboard focus, labels, contrast, semantic controls, and visible focus states are handled.
- [x] 4.6 Add drawer focus trap, Escape close, trigger focus restoration, and `prefers-reduced-motion` behavior.
- [x] 4.7 Add responsive table/list behavior for 375px mobile widths.

## 5. Dashboard

- [x] 5.1 Add a minimal operational dashboard with actionable counts for creator leads, creators, brands, and campaigns.
- [x] 5.2 Add attention shortcuts for interested leads, draft campaigns, active campaigns, and recently added creators.
- [x] 5.3 Add loading, empty, error, and retry states for dashboard data.
- [x] 5.4 Avoid decorative charts or widgets that do not support a clear operator action.
- [x] 5.5 Implement dashboard data behind a dedicated client using `/api/v1/dashboard/overview` if available or an encapsulated composition of existing API endpoints.

## 6. Catalog Management UI

- [x] 6.1 Add brand list, filters, create, detail, edit, delete, and validation error handling.
- [x] 6.2 Add campaign list, filters, create, detail, edit, delete, and validation error handling.
- [x] 6.3 Add city read-oriented listing.
- [x] 6.4 Add tag list and create flow with validation error handling.
- [x] 6.5 Ensure catalog list pages preserve filters when opening/closing drawers.

## 7. Creator Network UI

- [x] 7.1 Add creator lead list, filters, create, detail, edit, delete, and validation error handling.
- [x] 7.2 Add creator lead approval flow that calls the API approval endpoint and shows the created creator result.
- [x] 7.3 Add creator list, filters, create, detail, edit, delete, city/tag relationships, and validation error handling.
- [x] 7.4 Add social account list, filters, create, detail, edit, delete, owner-specific listing, and primary account behavior.
- [x] 7.5 Ensure creator network list pages preserve filters when opening/closing drawers.

## 8. Documentation

- [x] 8.1 Add frontend architecture documentation covering Vue SPA, API consumption, auth, and externalization path.
- [x] 8.2 Update `docs/api.md` with the API auth endpoints used by the Vue app if added.
- [x] 8.3 Update `docs/changelog.md` with the Vue operations UI change summary.
- [x] 8.4 Document UI conventions: minimal dashboard, drawer-first CRUD, table/filter patterns, and no-noise design rules.
- [x] 8.5 Ensure frontend docs reference `src/docs/ui.md` design tokens and accessibility conventions.

## 9. Verification

- [x] 9.1 Run frontend build verification.
- [x] 9.2 Run Laravel tests to ensure API behavior remains compatible.
- [x] 9.3 Manually verify login, route guard behavior, dashboard loading, and representative CRUD flows.
- [x] 9.4 Confirm all business data in the UI is loaded through `/api/v1`.
- [x] 9.5 Confirm no Inertia or server-rendered CRUD dependency was introduced.
- [x] 9.6 Run frontend tests for API client, auth state, and router guard behavior if a frontend test runner is added.
