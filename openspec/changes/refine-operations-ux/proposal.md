## Why

The operations UI is functional, but it still exposes implementation details such as raw IDs, class names, and flat navigation. Operators should work with recognizable business entities and a sharper information architecture that matches the current prospect, creator, brand, and settings workflows.

## What Changes

- Update the operations design system so the primary accent is black instead of olive while preserving the warm, minimal operations aesthetic.
- Reorganize the sidebar into primary operational areas: Dashboard, Prospects, Creators, and Brands.
- Nest Campaigns under Brands because campaigns are brand-owned operational records.
- Add a Settings group for Cities, Tags, and Social Accounts.
- Replace manual ID and comma-separated relationship inputs in forms with dropdown or multi-select controls backed by existing API data.
- Replace social account owner type/class and owner ID inputs with operator-friendly owner selection.
- Keep the change focused on shell/navigation and form usability; no dashboard redesign, new backend domain behavior, automation, or external integrations.

## Capabilities

### New Capabilities

- `operations-form-relations`: Entity-aware form controls for single and multi-record relationships in the operations UI.

### Modified Capabilities

- `vue-operations-shell`: Navigation structure and design tokens change to black accent, grouped sidebar, nested Brands/Campaigns, and Settings links.
- `catalog-management-ui`: Brand and campaign forms stop asking operators for raw IDs and use relationship selectors.
- `creator-network-ui`: Creator forms stop asking operators for raw city/tag IDs and use relationship selectors.
- `operations-dashboard`: Dashboard navigation labels and shortcuts refer to Prospects instead of legacy creator lead language where still present.
- `social-accounts`: Social account operations UI uses owner selectors instead of raw model class and owner ID inputs.

## Impact

- Affects Vue operations UI shell, resource configuration, resource form rendering, API client option loading, and documentation for UI behavior.
- Uses existing `/api/v1/brands`, `/api/v1/cities`, `/api/v1/tags`, `/api/v1/creators`, and `/api/v1/social-accounts` endpoints; no backend API contract change is expected unless implementation discovers a missing option source.
- Requires frontend build verification and focused UI tests/manual checks for relation payloads.
