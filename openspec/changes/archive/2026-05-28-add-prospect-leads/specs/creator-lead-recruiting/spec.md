## REMOVED Requirements

### Requirement: Creator lead persistence
**Reason**: Creator-only leads are replaced by shared prospects with `prospect_type=creator` or `prospect_type=brand`.
**Migration**: Existing creator lead records become prospects with `prospect_type=creator`; `niche` becomes `category`; `/api/v1/creator-leads` is replaced by `/api/v1/prospects?prospect_type=creator`.

### Requirement: Creator lead statuses
**Reason**: Prospect statuses replace creator lead statuses while preserving the same lifecycle values.
**Migration**: Use `prospect-leads` status requirements.

### Requirement: Creator lead listing and filtering
**Reason**: Prospect listing and filtering replaces creator-only lead listing.
**Migration**: Use `/api/v1/prospects` with `prospect_type=creator`, `status`, `platform`, `category`, `source`, and date filters.

### Requirement: Approve creator lead
**Reason**: Creator approval now originates from creator prospects.
**Migration**: Use the creator prospect approval action.

### Requirement: Creator lead soft deletion
**Reason**: Prospect soft deletion replaces creator lead soft deletion.
**Migration**: Use prospect delete behavior.

### Requirement: Creator lead authentication
**Reason**: Prospect endpoints replace creator lead endpoints.
**Migration**: Use prospect authentication behavior.
