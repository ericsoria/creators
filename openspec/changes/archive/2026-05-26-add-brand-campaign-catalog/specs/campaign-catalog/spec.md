## ADDED Requirements

### Requirement: Campaign persistence
The system SHALL persist campaigns under a brand with `name`, `description`, `objective`, `status`, `starts_at`, `ends_at`, `compensation_type`, `requirements`, and `notes` fields.

#### Scenario: Create campaign
- **WHEN** an authenticated internal user creates a campaign for an existing brand with valid data
- **THEN** the system SHALL persist the campaign and return it in a top-level `data` envelope

#### Scenario: Reject campaign without brand
- **WHEN** an authenticated internal user creates a campaign without a valid brand
- **THEN** the system SHALL return a validation error response

### Requirement: Campaign statuses
The system SHALL allow only the campaign statuses `draft`, `active`, `paused`, `completed`, and `cancelled`.

#### Scenario: Persist allowed status
- **WHEN** an authenticated internal user creates or updates a campaign with an allowed status
- **THEN** the system SHALL persist that status

#### Scenario: Reject unsupported status
- **WHEN** an authenticated internal user creates or updates a campaign with an unsupported status
- **THEN** the system SHALL return a validation error response

### Requirement: Campaign tag relationships
The system SHALL allow campaigns to be associated with zero or more tags.

#### Scenario: Attach tags to campaign
- **WHEN** an authenticated internal user creates or updates a campaign with valid tag ids
- **THEN** the system SHALL sync those tags to the campaign

#### Scenario: Reject unknown campaign tag ids
- **WHEN** a campaign request includes tag ids that do not exist
- **THEN** the system SHALL return a validation error response

### Requirement: Campaign listing and filtering
The system SHALL provide a paginated campaign list with MVP filters.

#### Scenario: List campaigns
- **WHEN** an authenticated internal user requests the campaign list
- **THEN** the system SHALL return a paginated response with `data`, `meta`, and `links`

#### Scenario: Filter campaigns
- **WHEN** an authenticated internal user filters campaigns by `status`, `brand`, `tag`, or date range
- **THEN** the system SHALL return only campaigns matching those filters

### Requirement: Campaign soft deletion
The system SHALL soft-delete campaigns instead of permanently deleting them through the API.

#### Scenario: Delete campaign
- **WHEN** an authenticated internal user deletes a campaign
- **THEN** the campaign SHALL be soft deleted and excluded from default campaign lists

### Requirement: Campaign authentication
The system SHALL require Sanctum authentication for all campaign catalog endpoints.

#### Scenario: Unauthenticated campaign request
- **WHEN** an unauthenticated client requests a campaign endpoint
- **THEN** the system SHALL return an unauthorized JSON response
