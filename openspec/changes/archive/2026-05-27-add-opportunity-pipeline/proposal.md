## Why

The backend can already manage campaigns and approved creators, but there is no operational bridge for outreach: offering a specific campaign to a specific creator, tracking follow-ups, and recording whether the creator accepts, rejects, or ghosts. Adding an opportunity pipeline creates the missing step between creator network management and confirmed collaborations.

## What Changes

- Add an authenticated `/api/v1/opportunities` API for campaign-to-creator outreach records.
- Add opportunity statuses for the outreach lifecycle: `draft`, `contacted`, `follow_up`, `interested`, `accepted`, `rejected`, `ghosted`, `expired`, and `cancelled`.
- Add opportunity filtering by campaign, creator, status, channel, assigned user, response state, and contact date ranges.
- Add an opportunity event timeline to capture outreach history, status transitions, messages, follow-ups, replies, and operational notes.
- Add an accept action that marks an opportunity accepted and prepares the handoff point for a future collaboration pipeline.
- Add tests, API resources, OpenAPI documentation, API docs, status-flow docs, and changelog entries for opportunities and opportunity events.
- Keep confirmed collaboration execution, visits, publications, scoring, and automation out of scope for this change.

## Capabilities

### New Capabilities

- `opportunity-pipeline`: Authenticated API, persistence, statuses, filtering, soft deletion, and acceptance behavior for campaign-to-creator outreach opportunities.
- `opportunity-events`: Timeline events for opportunity outreach history, status changes, messages, replies, and follow-ups.

### Modified Capabilities

- `campaign-catalog`: Campaigns can own opportunities.
- `creator-network`: Creators can own opportunities.

## Impact

- Adds `Opportunity` and `OpportunityEvent` domain models, migrations, factories, requests, resources, controllers, and tests.
- Adds `/api/v1/opportunities` and nested or related opportunity event endpoints under the existing Sanctum-protected API.
- Updates campaign and creator model relationships.
- Updates `src/docs/openapi.yaml`, `src/docs/api.md`, `src/docs/domain.md`, `src/docs/status-flows.md`, and `src/docs/changelog.md`.
- Does not add frontend screens in this change; the existing Vue operations UI may consume these endpoints in a later UI change.
