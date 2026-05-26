## 1. Database Schema

- [x] 1.1 Create migrations for `cities`, `tags`, `brands`, and `campaigns` with the fields defined in the specs.
- [x] 1.2 Create pivot table migrations for `brand_city`, `brand_tag`, and `campaign_tag` with foreign keys and uniqueness constraints.
- [x] 1.3 Add indexes for commonly filtered fields: status, slug, brand, city, tag, and campaign date fields.
- [x] 1.4 Add soft deletes for `brands`, `campaigns`, and `tags`.

## 2. Models And Relationships

- [x] 2.1 Add `City`, `Tag`, `Brand`, and `Campaign` models with fillable fields, casts, and soft delete behavior where required.
- [x] 2.2 Add `Brand hasMany Campaigns`, `Brand belongsToMany Cities`, and `Brand belongsToMany Tags` relationships.
- [x] 2.3 Add `Campaign belongsTo Brand` and `Campaign belongsToMany Tags` relationships.
- [x] 2.4 Add query scopes or minimal query helpers for status and relationship filters where they keep controllers thin.
- [x] 2.5 Add constants or enum-like model helpers for allowed campaign statuses.

## 3. Factories And Seeders

- [x] 3.1 Add factories for cities, tags, brands, and campaigns.
- [x] 3.2 Add taxonomy seed data for initial MVP cities and tags.
- [x] 3.3 Add optional demo seed data for brands and campaigns without making it required for production.
- [x] 3.4 Ensure seeders are idempotent and do not duplicate records when rerun.

## 4. Requests And Resources

- [x] 4.1 Add Form Requests for storing and updating brands.
- [x] 4.2 Add Form Requests for storing and updating campaigns.
- [x] 4.3 Add Form Requests or inline request classes for creating tags and filtering list endpoints as appropriate.
- [x] 4.4 Add API Resources for city, tag, brand, and campaign responses.
- [x] 4.5 Ensure brand and campaign resources can include related cities, tags, and brand data when loaded.

## 5. Controllers And Routes

- [x] 5.1 Add authenticated `/api/v1/cities` list endpoint.
- [x] 5.2 Add authenticated `/api/v1/tags` list and create endpoints.
- [x] 5.3 Add authenticated brand index, store, show, update, and destroy endpoints.
- [x] 5.4 Add authenticated campaign index, store, show, update, and destroy endpoints.
- [x] 5.5 Keep controllers thin by delegating validation to Form Requests and response formatting to API Resources.

## 6. Filtering And Pagination

- [x] 6.1 Add paginated brand listing with `status`, `city`, and `tag` filters.
- [x] 6.2 Add paginated campaign listing with `status`, `brand`, `tag`, `starts_at`, and `ends_at` filters.
- [x] 6.3 Add predictable default ordering for brand and campaign lists.
- [x] 6.4 Ensure paginated list responses include top-level `data`, `meta`, and `links` keys.

## 7. Tests

- [x] 7.1 Add authentication tests proving unauthenticated catalog requests are rejected.
- [x] 7.2 Add feature tests for city listing and tag creation/listing.
- [x] 7.3 Add feature tests for brand create, read, update, delete, relationships, validation, filtering, and pagination.
- [x] 7.4 Add feature tests for campaign create, read, update, delete, tag relationships, status validation, filtering, and pagination.
- [x] 7.5 Add model tests or relationship assertions for brand/campaign taxonomy relationships.
- [x] 7.6 Run the full test suite and formatting checks.

## 8. Documentation

- [x] 8.1 Update `docs/domain.md` with city, tag, brand, and campaign fields and relationships.
- [x] 8.2 Update `docs/api.md` with catalog endpoint conventions, filters, and pagination details.
- [x] 8.3 Update `docs/status-flows.md` with campaign statuses and note that transition actions are not part of this change.
- [x] 8.4 Update `docs/openapi.yaml` with schemas and endpoints for cities, tags, brands, and campaigns.
- [x] 8.5 Update `docs/changelog.md` with the catalog change summary.

## 9. Review Readiness

- [x] 9.1 Confirm all implemented endpoints are protected by Sanctum authentication.
- [x] 9.2 Confirm no creator, creator lead, social account, opportunity, collaboration, event, metric, or score code was added.
- [x] 9.3 Confirm OpenAPI and documentation match the implemented endpoints and response shapes.
