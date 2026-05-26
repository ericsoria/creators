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

Future changes must document status flows for entities such as CreatorLead, Opportunity, and Collaboration when those entities are introduced.

## CreatorLead

Creator leads currently support these stored statuses:

- `discovered`
- `contacted`
- `follow_up`
- `interested`
- `approved`
- `rejected`
- `ghosted`
- `archived`

The explicit approval action converts an eligible lead to `approved`, records `approved_at`, and creates a creator.

## Creator

Creators currently support these stored statuses:

- `active`
- `inactive`
- `paused`
- `blacklisted`

This change validates allowed values but does not implement explicit creator status transition actions.
