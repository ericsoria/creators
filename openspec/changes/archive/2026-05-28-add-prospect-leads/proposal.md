## Why

The current `CreatorLead` model only supports creator discovery, but the same discovery and outreach workflow is needed for both creators and brands. Generalizing leads into `Prospect` records with `prospect_type` prepares the backend for automation, shared inbox workflows, and future imports without duplicating lead logic.

## What Changes

- **BREAKING** Replace creator-specific lead concepts with prospect leads backed by a `Prospect` model and `/api/v1/prospects` API.
- Add `prospect_type` with allowed values `creator` and `brand`.
- Rename/normalize lead classification from creator-only `niche` to shared `category` so it works for creators and brands.
- Preserve the existing lead lifecycle statuses: `discovered`, `contacted`, `follow_up`, `interested`, `approved`, `rejected`, `ghosted`, and `archived`.
- Add prospect listing and filtering by `prospect_type`, status, platform, category, source, and contact date ranges.
- Add approval actions for creator prospects and brand prospects.
- Convert approved creator prospects into `Creator` records and initial social accounts.
- Convert approved brand prospects into `Brand` records and optional initial social accounts.
- Update tests, docs, OpenAPI, status flows, and the operations API contract from creator leads to prospects.
- Keep automated scraping/import jobs out of scope; this change prepares the data model and API for automation but does not implement automation.

## Capabilities

### New Capabilities

- `prospect-leads`: Shared prospect inbox, persistence, statuses, filtering, approval flows, and API documentation for creator and brand prospects.

### Modified Capabilities

- `creator-lead-recruiting`: Replace creator-specific lead behavior with shared prospect lead behavior.
- `creator-network`: Creator approval now originates from creator prospects instead of creator-only leads.
- `brand-catalog`: Brand approval can originate from brand prospects.

## Impact

- Adds or renames backend concepts around `Prospect`, prospect requests, resources, controllers, actions, factories, tests, and docs.
- Replaces `/api/v1/creator-leads` with `/api/v1/prospects` unless a concrete compatibility need is identified before implementation.
- Updates approval endpoints to distinguish creator and brand prospect approval.
- Updates the Vue operations UI API clients/pages in a follow-up frontend change or as a small compatibility adjustment only if required to keep the current UI usable.
- Updates `src/docs/openapi.yaml`, `src/docs/api.md`, `src/docs/domain.md`, `src/docs/status-flows.md`, and `src/docs/changelog.md`.
