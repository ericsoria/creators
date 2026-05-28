# Status Flows

## Campaign

Campaigns currently support these stored statuses:

- `draft`
- `active`
- `paused`
- `completed`
- `cancelled`

This catalog change validates allowed values but does not implement transition actions. Future operational changes may add explicit endpoints for campaign state transitions if business rules require them.

## Future Domain Flows

Future changes must document status flows for entities such as Collaboration when those entities are introduced.

## Prospect

Prospects currently support these stored statuses:

- `discovered`
- `contacted`
- `follow_up`
- `interested`
- `approved`
- `rejected`
- `ghosted`
- `archived`

The explicit creator approval action converts an eligible `creator` prospect to `approved`, records `approved_at`, creates a creator, and creates an initial creator social account when social fields exist.

The explicit brand approval action converts an eligible `brand` prospect to `approved`, records `approved_at`, creates a brand, and creates an initial brand social account when social fields exist.

## Creator

Creators currently support these stored statuses:

- `active`
- `inactive`
- `paused`
- `blacklisted`

This change validates allowed values but does not implement explicit creator status transition actions.

## Opportunity

Opportunities currently support these stored statuses:

- `draft`
- `contacted`
- `follow_up`
- `interested`
- `accepted`
- `rejected`
- `ghosted`
- `expired`
- `cancelled`

Terminal statuses are:

- `accepted`
- `rejected`
- `ghosted`
- `expired`
- `cancelled`

The explicit accept action converts an eligible opportunity to `accepted`, records `responded_at` if missing, and creates an `accepted` opportunity event. Opportunity event creation can also record status transitions with `old_status` and `new_status`.

Opportunity event types:

- `contacted`
- `follow_up_sent`
- `creator_replied`
- `accepted`
- `rejected`
- `ghosted`
- `note`
