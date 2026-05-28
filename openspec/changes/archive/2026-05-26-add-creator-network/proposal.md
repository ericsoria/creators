## Why

The platform can now model brands, taxonomy, and campaigns, but it cannot yet build the private creator network that future outreach and collaborations depend on. This change adds recruiting and creator profile foundations so operators can track discovered creators, approve them into the network, and classify them by city, tag, and social profile.

## What Changes

- Add `CreatorLead` for discovered creators that are not yet part of the private network.
- Add `Creator` for approved creators in the private network.
- Add `SocialAccount` as a polymorphic social profile model for creators and brands.
- Add creator relationships with existing cities and tags.
- Add authenticated `/api/v1` endpoints for creator leads, creators, and social accounts.
- Add an approve flow that converts an approved creator lead into a creator.
- Add request validation, API resources, factories, seeders, tests, OpenAPI updates, domain docs, status-flow docs, and changelog updates.
- Keep metrics, scoring, outreach opportunities, collaborations, and automation out of scope.

## Capabilities

### New Capabilities

- `creator-lead-recruiting`: Manage discovered creator leads and approve qualified leads into the private creator network.
- `creator-network`: Manage approved creators with profile fields, status, city relationships, tag relationships, and listing filters.
- `social-accounts`: Manage social accounts for creators and brands using a shared polymorphic model.

### Modified Capabilities

- `taxonomy-catalog`: Extend taxonomy relationships so creators can be classified by existing cities and tags.
- `brand-catalog`: Extend brands so they can own social accounts through the shared social account model.

## Impact

- Adds Laravel models, migrations, relationships, factories, seeders, Form Requests, API Resources, controllers, routes, feature tests, and documentation for creator leads, creators, and social accounts.
- Extends existing `City`, `Tag`, and `Brand` relationships without changing their existing API behavior.
- Updates `docs/domain.md`, `docs/api.md`, `docs/status-flows.md`, `docs/openapi.yaml`, and `docs/changelog.md`.
- Depends on existing API foundation, taxonomy catalog, and brand/campaign catalog capabilities.
