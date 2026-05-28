## MODIFIED Requirements

### Requirement: Creator management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete creators through `/api/v1/creators` without requiring raw relationship ID entry.

#### Scenario: Manage creators
- **WHEN** an authenticated operator manages creators
- **THEN** the UI SHALL support city, tag, status, UGC-only, barter, and search workflows

#### Scenario: Select creator taxonomy
- **WHEN** an authenticated operator assigns cities or tags to a creator
- **THEN** the UI SHALL provide relationship selectors and submit `city_ids` and `tag_ids` as arrays of IDs

### Requirement: Social account management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete social accounts through `/api/v1/social-accounts` and owner-specific endpoints without requiring Laravel class names or raw owner ID entry.

#### Scenario: Manage social accounts
- **WHEN** an operator manages creator or brand social accounts
- **THEN** the UI SHALL support platform, handle, URL, primary flag, and owner association

#### Scenario: Select social account owner
- **WHEN** an operator creates or updates a social account
- **THEN** the UI SHALL allow the operator to choose an owner from creator and brand options using human-readable labels while submitting `accountable_type` and `accountable_id` values expected by the API

### Requirement: Creator lead management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, delete, and approve prospects through `/api/v1/prospects`.

#### Scenario: Approve prospect
- **WHEN** an operator approves a creator or brand prospect
- **THEN** the UI SHALL call the appropriate API approval endpoint and show the created creator or brand result
