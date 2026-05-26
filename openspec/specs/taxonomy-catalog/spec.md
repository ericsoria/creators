## Requirements

### Requirement: City catalog
The system SHALL store normalized cities with `name`, `slug`, `country`, and `timezone` fields for use by brand and future creator classification.

#### Scenario: List cities
- **WHEN** an authenticated internal user requests the city list
- **THEN** the system SHALL return cities in a top-level `data` envelope

#### Scenario: City has required fields
- **WHEN** a city is persisted
- **THEN** it SHALL include `name`, `slug`, `country`, and `timezone`

### Requirement: Tag catalog
The system SHALL store tags with `name`, `slug`, and `type` fields for flexible classification of brands and campaigns.

#### Scenario: Create tag
- **WHEN** an authenticated internal user creates a tag with valid `name`, `slug`, and `type`
- **THEN** the system SHALL persist the tag and return it in a top-level `data` envelope

#### Scenario: Reject invalid tag
- **WHEN** an authenticated internal user creates a tag without required fields
- **THEN** the system SHALL return a validation error response

### Requirement: Catalog authentication
The system SHALL require Sanctum authentication for taxonomy catalog endpoints.

#### Scenario: Unauthenticated taxonomy request
- **WHEN** an unauthenticated client requests a taxonomy catalog endpoint
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: Initial taxonomy seed data
The system SHALL provide seed data for common MVP cities and tags.

#### Scenario: Seed taxonomy data
- **WHEN** database seeders run in a local or MVP environment
- **THEN** the system SHALL create initial cities and tags without duplicating existing records
