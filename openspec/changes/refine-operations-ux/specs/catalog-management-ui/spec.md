## MODIFIED Requirements

### Requirement: Campaign management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete campaigns through `/api/v1/campaigns` without requiring raw relationship ID entry.

#### Scenario: Manage campaigns
- **WHEN** an authenticated operator manages campaigns
- **THEN** the UI SHALL support brand selection by human-readable brand label, campaign status, date fields, tags, and API validation errors

#### Scenario: Select campaign tags
- **WHEN** an authenticated operator assigns tags to a campaign
- **THEN** the UI SHALL provide a multi-select tag control and submit `tag_ids` as an array of IDs

### Requirement: Brand management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete brands through `/api/v1/brands` without requiring raw relationship ID entry.

#### Scenario: Manage brands
- **WHEN** an authenticated operator manages brands
- **THEN** the UI SHALL use the API to persist changes and reflect API validation errors

#### Scenario: Select brand taxonomy
- **WHEN** an authenticated operator assigns cities or tags to a brand
- **THEN** the UI SHALL provide relationship selectors and submit `city_ids` and `tag_ids` as arrays of IDs
