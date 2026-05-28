## Requirements

### Requirement: Creator persistence
The system SHALL persist approved creators with name, username, email, phone, bio, UGC-only flag, accepts-barter flag, status, rating, joined timestamp, last-active timestamp, and notes fields.

#### Scenario: Create creator
- **WHEN** an authenticated internal user creates a creator with valid data
- **THEN** the system SHALL persist the creator and return it in a top-level `data` envelope

#### Scenario: Reject invalid creator
- **WHEN** an authenticated internal user creates or updates a creator with invalid data
- **THEN** the system SHALL return a validation error response

### Requirement: Creator statuses
The system SHALL allow only the creator statuses `active`, `inactive`, `paused`, and `blacklisted`.

#### Scenario: Persist allowed creator status
- **WHEN** an authenticated internal user creates or updates a creator with an allowed status
- **THEN** the system SHALL persist that status

#### Scenario: Reject unsupported creator status
- **WHEN** an authenticated internal user creates or updates a creator with an unsupported status
- **THEN** the system SHALL return a validation error response

### Requirement: Creator taxonomy relationships
The system SHALL allow creators to be associated with zero or more cities and zero or more tags.

#### Scenario: Attach creator taxonomy
- **WHEN** an authenticated internal user creates or updates a creator with valid city ids and tag ids
- **THEN** the system SHALL sync those cities and tags to the creator

#### Scenario: Reject unknown creator taxonomy ids
- **WHEN** a creator request includes city ids or tag ids that do not exist
- **THEN** the system SHALL return a validation error response

### Requirement: Creator listing and filtering
The system SHALL provide a paginated creator list with MVP filters.

#### Scenario: List creators
- **WHEN** an authenticated internal user requests the creator list
- **THEN** the system SHALL return a paginated response with `data`, `meta`, and `links`

#### Scenario: Filter creators
- **WHEN** an authenticated internal user filters creators by `status`, `city`, `tag`, `ugc_only`, `accepts_barter`, or search text
- **THEN** the system SHALL return only creators matching those filters

### Requirement: Creator soft deletion
The system SHALL soft-delete creators instead of permanently deleting them through the API.

#### Scenario: Delete creator
- **WHEN** an authenticated internal user deletes a creator
- **THEN** the creator SHALL be soft deleted and excluded from default creator lists

### Requirement: Creator authentication
The system SHALL require Sanctum authentication for all creator endpoints.

#### Scenario: Unauthenticated creator request
- **WHEN** an unauthenticated client requests a creator endpoint
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: Creator opportunity ownership
The system SHALL allow creators to own outreach opportunities.

#### Scenario: Creator has opportunities
- **WHEN** an authenticated internal user retrieves or works with a creator in backend code
- **THEN** the creator SHALL be able to expose its related opportunities

#### Scenario: Creator deletion preserves opportunity integrity
- **WHEN** a creator has opportunities
- **THEN** the system SHALL preserve referential integrity according to the creator and opportunity deletion rules

### Requirement: Creator prospect approval source
The system SHALL allow creators to be created from approved prospects with `prospect_type` of `creator`.

#### Scenario: Creator originates from prospect
- **WHEN** an authenticated internal user approves an eligible creator prospect
- **THEN** the created creator SHALL use prospect and approval data for its initial fields

#### Scenario: Creator approval creates social account
- **WHEN** an approved creator prospect has platform and handle data
- **THEN** the created creator SHALL receive an initial social account from that prospect data
