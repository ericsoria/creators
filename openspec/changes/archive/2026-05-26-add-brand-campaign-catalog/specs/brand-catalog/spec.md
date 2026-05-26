## ADDED Requirements

### Requirement: Brand persistence
The system SHALL persist brands with `name`, `slug`, `industry`, `description`, `website_url`, `status`, and `notes` fields.

#### Scenario: Create brand
- **WHEN** an authenticated internal user creates a brand with valid data
- **THEN** the system SHALL persist the brand and return it in a top-level `data` envelope

#### Scenario: Reject invalid brand
- **WHEN** an authenticated internal user creates or updates a brand with invalid data
- **THEN** the system SHALL return a validation error response

### Requirement: Brand city relationships
The system SHALL allow brands to be associated with zero or more normalized cities.

#### Scenario: Attach cities to brand
- **WHEN** an authenticated internal user creates or updates a brand with valid city ids
- **THEN** the system SHALL sync those cities to the brand

#### Scenario: Reject unknown city ids
- **WHEN** a brand request includes city ids that do not exist
- **THEN** the system SHALL return a validation error response

### Requirement: Brand tag relationships
The system SHALL allow brands to be associated with zero or more tags.

#### Scenario: Attach tags to brand
- **WHEN** an authenticated internal user creates or updates a brand with valid tag ids
- **THEN** the system SHALL sync those tags to the brand

#### Scenario: Return brand relationships
- **WHEN** an authenticated internal user retrieves a brand
- **THEN** the response SHALL include its associated cities and tags when loaded by the endpoint

### Requirement: Brand listing and filtering
The system SHALL provide a paginated brand list with MVP filters.

#### Scenario: List brands
- **WHEN** an authenticated internal user requests the brand list
- **THEN** the system SHALL return a paginated response with `data`, `meta`, and `links`

#### Scenario: Filter brands
- **WHEN** an authenticated internal user filters brands by `status`, `city`, or `tag`
- **THEN** the system SHALL return only brands matching those filters

### Requirement: Brand soft deletion
The system SHALL soft-delete brands instead of permanently deleting them through the API.

#### Scenario: Delete brand
- **WHEN** an authenticated internal user deletes a brand
- **THEN** the brand SHALL be soft deleted and excluded from default brand lists

### Requirement: Brand authentication
The system SHALL require Sanctum authentication for all brand catalog endpoints.

#### Scenario: Unauthenticated brand request
- **WHEN** an unauthenticated client requests a brand endpoint
- **THEN** the system SHALL return an unauthorized JSON response
