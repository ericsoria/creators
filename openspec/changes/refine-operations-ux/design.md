## Context

The current Vue operations UI is intentionally minimal and API-driven, but several screens still expose backend-oriented implementation details. Resource forms ask operators for numeric IDs or comma-separated ID lists, social account ownership exposes Laravel model class names, and navigation is a flat list that mixes primary workflows with settings-style maintenance screens.

The UI already has a resource configuration layer, shared form component, shared API client, and API endpoints that can provide option data for most relationship selectors. This change should reuse those pieces instead of adding a large new form framework.

## Goals / Non-Goals

**Goals:**

- Make black the primary accent color while keeping the current warm paper/surface visual language.
- Reorganize sidebar navigation into primary work areas and settings.
- Place Campaigns visually under Brands.
- Replace raw ID, comma-separated ID, and Laravel class-name inputs with operator-friendly relation controls.
- Keep relation payloads compatible with the existing API contracts.
- Keep implementation small and local to the operations UI where possible.

**Non-Goals:**

- No backend domain changes.
- No new endpoints unless implementation proves an existing option source is missing.
- No full dashboard redesign.
- No replacement of the drawer-first CRUD model.
- No automation, scraping, enrichment, or external integrations.

## Decisions

### Use black as the accent, not a dark theme

The primary accent token should become black or near-black. Background, surface, muted text, border, success, danger, and attention tokens should remain close to the existing warm operations palette.

Alternative considered: switch to a full black-and-white theme. Rejected because the current warm palette is calmer and better for long operational sessions.

### Represent navigation as grouped data

The sidebar should use grouped navigation data rather than one flat array. Primary navigation should include Dashboard, Prospects, Creators, and Brands. Brands should visually contain Campaigns. Settings should contain Cities, Tags, and Social Accounts.

Alternative considered: keep Campaigns top-level. Rejected for this change because campaigns are brand-owned and the requested information architecture intentionally reduces top-level sidebar noise.

### Keep Campaigns as a route, nested only in navigation

For this change, Campaigns can remain the existing `/app/campaigns` resource route while being visually nested under Brands. A future brand detail experience can show brand-scoped campaigns, but that is outside this scope.

Alternative considered: build brand detail pages with embedded campaigns now. Rejected because it expands the change beyond navigation and form usability.

### Extend resource field config instead of hardcoding forms

The current resource config arrays should evolve just enough to describe relation fields. The form can then render relation and multi-relation controls generically.

Expected field capabilities:

- Static select: enum-like values already supported today.
- Relation select: one API-backed selected ID, such as `brand_id`.
- Multi-relation select: many selected IDs, such as `city_ids` or `tag_ids`.
- Typed owner select: social account owner type and owner ID represented as one operator-friendly control or as two coordinated controls.

Alternative considered: create bespoke forms for each resource. Rejected because the resource config architecture is already in place and the needed behavior is cross-cutting.

### Load relationship options from existing APIs

Relation options should come from existing authenticated API endpoints. Brands, creators, cities, and tags are already listable. For small MVP lists, loading the first page or a sufficiently large page is acceptable. If lists become large, a future change can add searchable remote combobox behavior.

Alternative considered: embed all option data in the page shell. Rejected because the SPA already consumes `/api/v1` and should keep business data API-driven.

### Preserve existing API payloads

The UI should still submit payloads the API already expects, such as `brand_id`, `city_ids`, `tag_ids`, `accountable_type`, and `accountable_id`. This is a presentation-layer usability change, not an API redesign.

Alternative considered: change API payloads to nested objects. Rejected because it would unnecessarily widen backend scope.

## Risks / Trade-offs

- Option lists may grow large -> Start with existing paginated APIs and document that searchable remote selectors are a future enhancement.
- Generic form config can become too abstract -> Keep only the field types needed by current resources.
- Social account owner selection has conditional behavior -> Prefer a clear owner type selector plus dependent owner dropdown unless a combined owner picker remains simple.
- Moving Campaigns under Brands may reduce discoverability -> Keep visual nesting visible and preserve direct route access.
- Black accent can feel too harsh -> Use black primarily for active states, focus, and primary actions, not for large filled surfaces everywhere.

## Migration Plan

1. Update UI tokens and documented accent color.
2. Change sidebar data and rendering to support grouped/nested navigation.
3. Extend the resource form field model for relation controls.
4. Replace ID/CSV fields in resource configs with relation field definitions.
5. Verify payloads sent to existing APIs are unchanged in shape.
6. Run frontend build and relevant Laravel tests if touched behavior crosses API assumptions.

## Open Questions

- Should relation selects use simple native `<select multiple>` controls first, or should implementation introduce a lightweight custom multi-select for better usability?
- Should filters also become dropdowns in this change, or should scope stay limited to create/update forms?
- Should Prospects filters default to all prospects, or should the UI provide quick tabs for creator versus brand prospects in a later change?
