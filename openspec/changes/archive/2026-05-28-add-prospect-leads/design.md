## Context

The current backend has `CreatorLead` as a creator-only discovery inbox. That was enough for initial creator recruiting, but the product now needs the same discovery path for brands: records found manually or through automation that may later become a `Creator` or `Brand`.

The desired naming is `Prospect` for the model/API entity and `prospect-leads` for the capability. A prospect is not final domain data; it is an automation-friendly intake record with a type, status, source, social handle/profile data, and approval path.

## Goals / Non-Goals

**Goals:**

- Replace `CreatorLead` with a shared `Prospect` model and `prospects` table.
- Support `prospect_type` values `creator` and `brand`.
- Replace creator-only `niche` with shared `category`.
- Keep the existing lead status lifecycle for prospects.
- Add `/api/v1/prospects` endpoints for list, create, show, update, delete, and type-specific approval.
- Approve creator prospects into `Creator` plus initial social account.
- Approve brand prospects into `Brand` plus optional initial social account.
- Update backend tests, resources, docs, OpenAPI, and status flows.

**Non-Goals:**

- No scraping, enrichment, import jobs, queues, or external automation yet.
- No prospect UI redesign beyond keeping current operations surfaces from breaking if implementation chooses to update the existing Vue API client.
- No compatibility layer for `/api/v1/creator-leads` unless implementation discovers a concrete internal dependency that needs a short migration bridge.
- No brand opportunity/collaboration automation in this change.

## Decisions

### Use `Prospect` as the root model

The domain model should be `App\Models\Prospect`, stored in `prospects`, exposed through `/api/v1/prospects`. The capability name can remain `prospect-leads`, but the concrete model should not be `ProspectLead` because `prospect` and `lead` are semantically redundant.

Alternative considered: keep `CreatorLead` and add `BrandLead`. Rejected because it duplicates statuses, filtering, source/import logic, approval mechanics, metrics, and future automation entrypoints.

### Use `prospect_type` instead of polymorphic lead classes

Prospects should store `prospect_type` as `creator` or `brand`. This keeps the inbox queryable as one list while allowing approval to branch by type.

Alternative considered: separate subtype tables. Rejected for MVP because the shared fields dominate and automation should not need to choose separate storage paths too early.

### Use `category` as shared classification

`niche` is creator-biased. `category` works for both creators and brands: `food`, `ugc`, `restaurant`, `hotel`, `beauty`, etc.

Alternative considered: keep `niche` and add `industry`. Rejected because it makes filters and automation mapping more fragmented before there is a proven need.

### Split approval actions by target type

Use explicit actions such as `ApproveCreatorProspectAction` and `ApproveBrandProspectAction`. Both consume a `Prospect`, but they create different target records and have different validation needs.

Alternative considered: one large `ApproveProspectAction`. Rejected because it would hide two separate domain transitions behind conditional logic.

### Treat this as an early breaking change

Because the project is still in MVP and there are no external API consumers, implementation should prefer a clean rename over long-lived compatibility code. If the current Vue operations UI depends on creator lead endpoints, implementation can update that client/page to point at prospects filtered by `prospect_type=creator`.

Alternative considered: keep `/api/v1/creator-leads` as aliases. Rejected unless there is a concrete need, because compatibility aliases add surface area during a domain rename.

## Risks / Trade-offs

- Existing frontend creator lead screens can break -> Mitigation: update the operations UI resource config or add a short internal bridge only if needed.
- Rename migrations can be awkward with existing local data -> Mitigation: migrate `creator_leads` data into `prospects` with `prospect_type=creator` and map `niche` to `category`.
- Brand approval needs a slug -> Mitigation: require or derive slug during approval using a predictable slug strategy and validate uniqueness.
- Shared fields can become too generic -> Mitigation: keep this MVP focused on common discovery fields; add typed metadata later only when automation proves the need.

## Migration Plan

1. Rename or replace creator lead storage with prospects, preserving existing creator lead data as `prospect_type=creator`.
2. Rename model/request/resource/controller/action concepts from creator lead to prospect.
3. Add brand approval path and tests.
4. Update API routes, docs, OpenAPI, and status flows.
5. Update any internal frontend API client/resource references if needed.
6. Run backend tests, frontend build if touched, and Pint for touched PHP files.

## Open Questions

- Should brand prospect approval require `slug`, or derive it from `name` by default?
- Should a brand prospect require `website_url`, or can it start from social profile data only?
- Should `prospect_type` be mutable before approval, or locked after creation?
