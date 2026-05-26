## 1. Database Schema

- [x] 1.1 Create migrations for `creator_leads`, `creators`, and `social_accounts` with fields defined in the specs.
- [x] 1.2 Create pivot table migrations for `creator_city` and `creator_tag` with foreign keys and uniqueness constraints.
- [x] 1.3 Add indexes for commonly filtered fields: lead status, creator status, platform, handle, city, tag, UGC-only, accepts-barter, and dates.
- [x] 1.4 Add soft deletes for creator leads, creators, and social accounts.
- [x] 1.5 Add database constraints or indexes for social account owner/platform/handle lookup.

## 2. Models And Relationships

- [x] 2.1 Add `CreatorLead`, `Creator`, and `SocialAccount` models with fillable fields, casts, and soft delete behavior.
- [x] 2.2 Add `Creator belongsToMany Cities`, `Creator belongsToMany Tags`, and `Creator morphMany SocialAccounts` relationships.
- [x] 2.3 Add `SocialAccount morphTo accountable` relationship.
- [x] 2.4 Extend `City` and `Tag` models with creator relationships.
- [x] 2.5 Extend `Brand` with `morphMany SocialAccounts` relationship.
- [x] 2.6 Add constants or enum-like helpers for allowed creator lead statuses and creator statuses.
- [x] 2.7 Add query scopes or minimal query helpers for lead, creator, and social account filters where they keep controllers thin.

## 3. Actions And Domain Flow

- [x] 3.1 Add `ApproveCreatorLeadAction` to convert an eligible lead into a creator.
- [x] 3.2 Ensure approval sets creator lead status to `approved` and records `approved_at`.
- [x] 3.3 Ensure approval can create an initial creator social account from the lead platform, handle, and profile URL.
- [x] 3.4 Prevent approving a creator lead that is already approved.

## 4. Factories And Seeders

- [x] 4.1 Add factories for creator leads, creators, and social accounts.
- [x] 4.2 Add optional demo seed data for creator leads, creators, and social accounts for local development.
- [x] 4.3 Ensure seeders are idempotent and do not duplicate demo creator records when rerun.

## 5. Requests And Resources

- [x] 5.1 Add Form Requests for storing and updating creator leads.
- [x] 5.2 Add Form Requests for approving creator leads.
- [x] 5.3 Add Form Requests for storing and updating creators.
- [x] 5.4 Add Form Requests for storing and updating social accounts.
- [x] 5.5 Add list/filter request validation for creator leads, creators, and social accounts.
- [x] 5.6 Add API Resources for creator leads, creators, and social accounts.
- [x] 5.7 Ensure creator resources can include related cities, tags, and social accounts when loaded.

## 6. Controllers And Routes

- [x] 6.1 Add authenticated creator lead index, store, show, update, destroy, and approve endpoints.
- [x] 6.2 Add authenticated creator index, store, show, update, and destroy endpoints.
- [x] 6.3 Add authenticated social account index, store, show, update, and destroy endpoints.
- [x] 6.4 Add authenticated owner-specific social account listing endpoints for creators and brands if needed by the API design.
- [x] 6.5 Keep controllers thin by delegating approval to the Action, validation to Form Requests, and response formatting to API Resources.

## 7. Filtering And Pagination

- [x] 7.1 Add paginated creator lead listing with `status`, `platform`, `niche`, `source`, `contacted_at`, and `responded_at` filters.
- [x] 7.2 Add paginated creator listing with `status`, `city`, `tag`, `ugc_only`, `accepts_barter`, and `search` filters.
- [x] 7.3 Add social account listing filters for `platform`, `accountable_type`, and `accountable_id`.
- [x] 7.4 Add predictable default ordering for creator lead, creator, and social account lists.
- [x] 7.5 Ensure paginated list responses include top-level `data`, `meta`, and `links` keys.

## 8. Tests

- [x] 8.1 Add authentication tests proving unauthenticated creator network requests are rejected.
- [x] 8.2 Add feature tests for creator lead create, read, update, delete, validation, filtering, pagination, and soft deletion.
- [x] 8.3 Add feature tests for approving a creator lead into a creator and initial social account.
- [x] 8.4 Add feature tests for creator create, read, update, delete, city/tag relationships, validation, filtering, pagination, and soft deletion.
- [x] 8.5 Add feature tests for social account create, read, update, delete, owner relationships, primary behavior, validation, filtering, and soft deletion.
- [x] 8.6 Add model tests or relationship assertions for creator taxonomy and polymorphic social accounts.
- [x] 8.7 Run the full test suite and formatting checks.

## 9. Documentation

- [x] 9.1 Update `docs/domain.md` with creator lead, creator, social account fields and relationships.
- [x] 9.2 Update `docs/api.md` with creator network endpoints, filters, pagination details, and approval action.
- [x] 9.3 Update `docs/status-flows.md` with creator lead statuses and creator statuses.
- [x] 9.4 Update `docs/openapi.yaml` with schemas and endpoints for creator leads, creators, and social accounts.
- [x] 9.5 Update `docs/changelog.md` with the creator network change summary.

## 10. Review Readiness

- [x] 10.1 Confirm all implemented endpoints are protected by Sanctum authentication.
- [x] 10.2 Confirm no opportunity, collaboration, event, metric, score, scraping, or automation code was added.
- [x] 10.3 Confirm OpenAPI and documentation match the implemented endpoints and response shapes.
- [x] 10.4 Confirm existing brand, city, tag, and campaign catalog behavior remains compatible.
