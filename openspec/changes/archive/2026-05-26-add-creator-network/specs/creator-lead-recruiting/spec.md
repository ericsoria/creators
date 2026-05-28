## ADDED Requirements

### Requirement: Creator lead persistence
The system SHALL persist discovered creator leads with platform, handle, profile URL, name, location text, niche, status, contact timestamps, approval timestamp, rejection reason, notes, and source fields.

#### Scenario: Create creator lead
- **WHEN** an authenticated internal user creates a creator lead with valid data
- **THEN** the system SHALL persist the lead and return it in a top-level `data` envelope

#### Scenario: Reject invalid creator lead
- **WHEN** an authenticated internal user creates or updates a creator lead with invalid data
- **THEN** the system SHALL return a validation error response

### Requirement: Creator lead statuses
The system SHALL allow only the creator lead statuses `discovered`, `contacted`, `follow_up`, `interested`, `approved`, `rejected`, `ghosted`, and `archived`.

#### Scenario: Persist allowed lead status
- **WHEN** an authenticated internal user creates or updates a creator lead with an allowed status
- **THEN** the system SHALL persist that status

#### Scenario: Reject unsupported lead status
- **WHEN** an authenticated internal user creates or updates a creator lead with an unsupported status
- **THEN** the system SHALL return a validation error response

### Requirement: Creator lead listing and filtering
The system SHALL provide a paginated creator lead list with MVP filters.

#### Scenario: List creator leads
- **WHEN** an authenticated internal user requests the creator lead list
- **THEN** the system SHALL return a paginated response with `data`, `meta`, and `links`

#### Scenario: Filter creator leads
- **WHEN** an authenticated internal user filters creator leads by `status`, `platform`, `niche`, `source`, or date range
- **THEN** the system SHALL return only leads matching those filters

### Requirement: Approve creator lead
The system SHALL provide an authenticated action that approves a creator lead and creates a creator in the private network.

#### Scenario: Approve eligible lead
- **WHEN** an authenticated internal user approves an eligible creator lead
- **THEN** the system SHALL mark the lead as `approved`, set `approved_at`, create a creator, and return the created creator in a top-level `data` envelope

#### Scenario: Reject approving already approved lead
- **WHEN** an authenticated internal user attempts to approve a creator lead that is already approved
- **THEN** the system SHALL return a validation or conflict error response

### Requirement: Creator lead soft deletion
The system SHALL soft-delete creator leads instead of permanently deleting them through the API.

#### Scenario: Delete creator lead
- **WHEN** an authenticated internal user deletes a creator lead
- **THEN** the lead SHALL be soft deleted and excluded from default creator lead lists

### Requirement: Creator lead authentication
The system SHALL require Sanctum authentication for all creator lead endpoints.

#### Scenario: Unauthenticated creator lead request
- **WHEN** an unauthenticated client requests a creator lead endpoint
- **THEN** the system SHALL return an unauthorized JSON response
