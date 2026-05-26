## Context

The system currently has an authenticated API foundation plus catalog entities for cities, tags, brands, and campaigns. The next MVP layer is the private creator network: operators need to track discovered creators, approve them into the network, classify them, and record their social handles before outreach can begin.

The existing taxonomy catalog already states that cities classify future creators. This change makes that concrete by adding `CreatorLead`, `Creator`, and `SocialAccount`, while extending `City`, `Tag`, and `Brand` relationships.

## Goals / Non-Goals

**Goals:**

- Add `CreatorLead` for recruiting/discovery before a creator joins the network.
- Add `Creator` for approved network members.
- Add `SocialAccount` as a shared polymorphic model for creators and brands.
- Support creator relationships with existing cities and tags.
- Add an explicit approve action that converts a creator lead into a creator and preserves traceability.
- Expose authenticated `/api/v1` endpoints for creator leads, creators, and social accounts.
- Keep controllers thin with Form Requests, API Resources, and an approval Action.
- Add tests, OpenAPI, domain documentation, status-flow documentation, and changelog updates.

**Non-Goals:**

- No `SocialAccountMetric` snapshots yet.
- No `CreatorScore` or reliability scoring yet.
- No opportunities, outreach pipeline, collaborations, events, visits, publications, or campaign matching.
- No automated scraping or external social network integrations.
- No public creator portal or marketplace behavior.

## Decisions

### Keep CreatorLead social data denormalized

`CreatorLead` stores `platform`, `handle`, and `profile_url` directly. Leads are lightweight discovered profiles and should not require full social account records until approval.

Alternative considered: attach `SocialAccount` to leads too. Rejected because it increases complexity for unqualified records and conflicts with the MVP domain note that leads do not have SocialAccounts yet.

### Use SocialAccount only for approved Creators and Brands

`SocialAccount` will use `accountable_type` and `accountable_id` so both `Creator` and `Brand` can own accounts. This matches the existing product model and avoids duplicating platform/handle logic.

Alternative considered: separate `creator_social_accounts` and `brand_social_accounts` tables. Rejected because the fields and validation rules are shared.

### Convert leads through an explicit Action

Approving a lead should run through an `ApproveCreatorLeadAction`. The action should validate the lead can be approved, create the creator, optionally create the initial social account from lead fields, mark the lead approved, and record timestamps.

Alternative considered: approve inline in a controller. Rejected because approval is a domain transition and will likely gain more rules later.

### Keep creator status simple

Creators support `active`, `inactive`, `paused`, and `blacklisted`. This change validates allowed values but does not add explicit transition endpoints.

Alternative considered: add creator status transition endpoints immediately. Rejected because the current goal is network management, not workflow automation.

### Use id-based routes and relationship sync arrays

Creator endpoints should use id-based route model binding and accept `city_ids`, `tag_ids`, and `social_accounts` where appropriate. This is consistent with the brand/campaign catalog and keeps internal APIs predictable.

Alternative considered: slug/username route binding. Rejected for now because usernames and handles can change or collide across platforms.

## Risks / Trade-offs

- Duplicate creators can be created from repeated leads -> Mitigate with validation around email/username where present and tests for approve behavior.
- Social account uniqueness can be ambiguous across platforms -> Mitigate with uniqueness on platform plus handle for the accountable record and request validation.
- Lead approval may need richer audit history later -> Mitigate by keeping approval in an Action so event logging can be added in a later change.
- Creator profile fields may be sparse -> Mitigate by allowing nullable contact fields while requiring enough identity to operate.
- Polymorphic social accounts add validation branching -> Mitigate by restricting API creation to supported accountable types and testing creator/brand ownership.

## Migration Plan

1. Add migrations for creator leads, creators, creator taxonomy pivots, and social accounts.
2. Add models, relationships, factories, and seeders.
3. Add Form Requests, API Resources, controllers, and approval Action.
4. Add protected `/api/v1` routes.
5. Add feature and unit tests for CRUD, filtering, approval, relationships, and social account ownership.
6. Update OpenAPI and internal documentation.

## Open Questions

- Should approving a lead require `name`, or can a creator be created from only `platform` and `handle`?
- Should `username` be globally unique for creators, or nullable/non-unique until enough data is available?
- Should social account uniqueness be global per `platform + handle`, or scoped per accountable owner?
