## ADDED Requirements

### Requirement: Social account persistence
The system SHALL persist social accounts with accountable owner, platform, handle, URL, and primary flag fields.

#### Scenario: Create social account
- **WHEN** an authenticated internal user creates a social account for a supported owner with valid data
- **THEN** the system SHALL persist the social account and return it in a top-level `data` envelope

#### Scenario: Reject invalid social account
- **WHEN** an authenticated internal user creates or updates a social account with invalid owner, platform, or handle data
- **THEN** the system SHALL return a validation error response

### Requirement: Supported social account owners
The system SHALL support social accounts for creators and brands.

#### Scenario: Attach social account to creator
- **WHEN** an authenticated internal user creates a social account for a creator
- **THEN** the system SHALL associate the social account with that creator

#### Scenario: Attach social account to brand
- **WHEN** an authenticated internal user creates a social account for a brand
- **THEN** the system SHALL associate the social account with that brand

### Requirement: Social account listing
The system SHALL provide authenticated endpoints to list social accounts globally and by owner.

#### Scenario: List owner social accounts
- **WHEN** an authenticated internal user requests social accounts for a creator or brand
- **THEN** the system SHALL return only social accounts owned by that record

### Requirement: Primary social account behavior
The system SHALL allow an owner to have one primary social account per platform.

#### Scenario: Set primary account
- **WHEN** an authenticated internal user creates or updates a social account as primary for a platform
- **THEN** the system SHALL persist the account as primary and ensure other accounts for the same owner and platform are not primary

### Requirement: Social account soft deletion
The system SHALL soft-delete social accounts instead of permanently deleting them through the API.

#### Scenario: Delete social account
- **WHEN** an authenticated internal user deletes a social account
- **THEN** the social account SHALL be soft deleted and excluded from default social account lists

### Requirement: Social account authentication
The system SHALL require Sanctum authentication for all social account endpoints.

#### Scenario: Unauthenticated social account request
- **WHEN** an unauthenticated client requests a social account endpoint
- **THEN** the system SHALL return an unauthorized JSON response
