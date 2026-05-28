## 1. Data Model Migration

- [x] 1.1 Add or rename storage from `creator_leads` to `prospects` while preserving existing local creator lead data as `prospect_type=creator`.
- [x] 1.2 Add `prospect_type` with allowed values `creator` and `brand`.
- [x] 1.3 Rename or map `niche` to shared `category`.
- [x] 1.4 Add indexes for `prospect_type`, `status`, `platform`, `category`, `source`, `contacted_at`, and `responded_at`.
- [x] 1.5 Replace `CreatorLead` model with `Prospect` model, constants, casts, fillable fields, and filters.
- [x] 1.6 Replace creator lead factory with prospect factory and creator/brand states.

## 2. API Requests And Resources

- [x] 2.1 Replace creator lead list/store/update requests with prospect list/store/update requests.
- [x] 2.2 Add validation for `prospect_type`, shared prospect fields, statuses, and filters.
- [x] 2.3 Replace `CreatorLeadResource` with `ProspectResource`.
- [x] 2.4 Add request validation for approving creator prospects.
- [x] 2.5 Add request validation for approving brand prospects, including slug behavior.

## 3. Approval Actions

- [x] 3.1 Replace `ApproveCreatorLeadAction` with `ApproveCreatorProspectAction`.
- [x] 3.2 Add `ApproveBrandProspectAction`.
- [x] 3.3 Ensure creator approval rejects non-creator prospects and already approved prospects.
- [x] 3.4 Ensure brand approval rejects non-brand prospects and already approved prospects.
- [x] 3.5 Ensure approval marks the prospect `approved`, sets `approved_at`, and creates initial social accounts when platform/handle data exists.

## 4. API Controllers And Routes

- [x] 4.1 Replace `CreatorLeadController` with `ProspectController`.
- [x] 4.2 Add `/api/v1/prospects` resource routes.
- [x] 4.3 Add creator prospect approval endpoint.
- [x] 4.4 Add brand prospect approval endpoint.
- [x] 4.5 Remove `/api/v1/creator-leads` routes unless a concrete compatibility need is identified during implementation.

## 5. Tests

- [x] 5.1 Replace creator lead recruiting tests with prospect lead tests.
- [x] 5.2 Add tests for creator and brand prospect creation, update, list, filters, soft deletion, and authentication.
- [x] 5.3 Add tests for unsupported `prospect_type`, unsupported status, and invalid category/filter data.
- [x] 5.4 Add tests for creator prospect approval and rejection of non-creator prospects.
- [x] 5.5 Add tests for brand prospect approval and rejection of non-brand prospects.
- [x] 5.6 Update creator network relationship tests that referenced creator leads.
- [x] 5.7 Update authentication tests for prospect endpoints.

## 6. Documentation

- [x] 6.1 Update `src/docs/openapi.yaml` from creator lead endpoints/schemas to prospect endpoints/schemas.
- [x] 6.2 Update `src/docs/api.md` with prospect endpoints, filters, and approval actions.
- [x] 6.3 Update `src/docs/domain.md` with Prospect behavior and remove CreatorLead as a root concept.
- [x] 6.4 Update `src/docs/status-flows.md` with prospect statuses and type-specific approvals.
- [x] 6.5 Update `src/docs/changelog.md` with the prospect lead change summary.

## 7. Operations UI Compatibility

- [x] 7.1 Check whether the Vue operations UI still calls `/api/v1/creator-leads`.
- [x] 7.2 If needed, update the local operations UI resource config/client from creator leads to prospects filtered by `prospect_type=creator`.
- [x] 7.3 Confirm no larger frontend redesign is introduced in this backend-focused change.

## 8. Verification

- [x] 8.1 Run Laravel tests.
- [x] 8.2 Run Pint style check for touched PHP files.
- [x] 8.3 Run frontend build verification if Vue operations UI files are touched.
- [x] 8.4 Confirm OpenAPI docs include prospect list, CRUD, approve-as-creator, and approve-as-brand endpoints.
- [x] 8.5 Confirm no automation, scraping, enrichment jobs, or external integrations were added.
