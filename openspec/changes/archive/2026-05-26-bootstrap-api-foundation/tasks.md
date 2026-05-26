## 1. Dependencies And Configuration

- [x] 1.1 Install and configure Laravel Sanctum for token-based internal API authentication.
- [x] 1.2 Configure API route mounting so `routes/api.php` is loaded separately from `routes/web.php`.
- [x] 1.3 Update environment examples and database configuration notes for PostgreSQL-backed development.

## 2. User Roles And Seeding

- [x] 2.1 Add support for the `admin`, `manager`, `operator`, and `viewer` user roles.
- [x] 2.2 Add validation or model-level constraints so unsupported roles cannot be assigned through application code.
- [x] 2.3 Add a repeatable admin user seeder using environment configuration for email and password.
- [x] 2.4 Update user factories to generate valid internal user roles for tests.

## 3. Versioned API Foundation

- [x] 3.1 Create the `/api/v1` route group and protect private endpoints with `auth:sanctum` by default.
- [x] 3.2 Add a protected current-user endpoint that returns the authenticated user's id, name, email, and role.
- [x] 3.3 Establish the API response conventions for single resources, paginated lists, validation errors, and unauthorized errors.
- [x] 3.4 Ensure unauthenticated API requests receive JSON unauthorized responses instead of browser redirects.

## 4. Documentation Baseline

- [x] 4.1 Create `docs/architecture.md` describing the API-first Laravel foundation and future domain boundaries.
- [x] 4.2 Create `docs/api.md` documenting API versioning, response envelopes, pagination conventions, and error shapes.
- [x] 4.3 Create `docs/auth.md` documenting Sanctum token auth, internal roles, and local admin seeding.
- [x] 4.4 Create `docs/status-flows.md` with a placeholder noting that domain status transitions will be documented by later changes.
- [x] 4.5 Create `docs/openapi.yaml` using OpenAPI 3.1 and document the implemented foundation endpoints and auth scheme.
- [x] 4.6 Create `docs/changelog.md` and record the foundation change.

## 5. Tests And Verification

- [x] 5.1 Add tests proving private `/api/v1` endpoints reject unauthenticated requests.
- [x] 5.2 Add tests proving authenticated requests can access the current-user endpoint.
- [x] 5.3 Add tests proving the current-user endpoint returns the expected `data` envelope and user fields.
- [x] 5.4 Add tests or assertions proving unsupported user roles fail validation or cannot be persisted through application code.
- [x] 5.5 Run the application test suite and formatting checks.

## 6. Review Readiness

- [x] 6.1 Confirm every implemented endpoint is represented in `docs/openapi.yaml`.
- [x] 6.2 Confirm documentation reflects the implemented auth mode and role model.
- [x] 6.3 Confirm no brand, campaign, creator, opportunity, collaboration, scoring, or social account domain code was added in this foundation change.
