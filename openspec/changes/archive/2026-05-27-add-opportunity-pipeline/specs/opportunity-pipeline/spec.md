## ADDED Requirements

### Requirement: Opportunity persistence
The system SHALL persist campaign-to-creator outreach opportunities with campaign, creator, status, channel, source account, message template, contact timestamps, follow-up count, rejection reason, notes, assigned user, and future collaboration conversion reference fields.

#### Scenario: Create opportunity
- **WHEN** an authenticated internal user creates an opportunity for an existing campaign and creator with valid data
- **THEN** the system SHALL persist the opportunity and return it in a top-level `data` envelope

#### Scenario: Reject opportunity without valid campaign or creator
- **WHEN** an authenticated internal user creates an opportunity without a valid campaign or creator
- **THEN** the system SHALL return a validation error response

### Requirement: Opportunity statuses
The system SHALL allow only the opportunity statuses `draft`, `contacted`, `follow_up`, `interested`, `accepted`, `rejected`, `ghosted`, `expired`, and `cancelled`.

#### Scenario: Persist allowed opportunity status
- **WHEN** an authenticated internal user creates or updates an opportunity with an allowed status
- **THEN** the system SHALL persist that status

#### Scenario: Reject unsupported opportunity status
- **WHEN** an authenticated internal user creates or updates an opportunity with an unsupported status
- **THEN** the system SHALL return a validation error response

### Requirement: Opportunity duplicate prevention
The system SHALL prevent multiple active opportunities for the same campaign and creator pair.

#### Scenario: Reject duplicate active opportunity
- **WHEN** an authenticated internal user creates an opportunity for a campaign and creator that already have a non-terminal opportunity
- **THEN** the system SHALL return a validation error response

#### Scenario: Allow new opportunity after terminal outcome
- **WHEN** an existing opportunity for a campaign and creator has a terminal status of `accepted`, `rejected`, `ghosted`, `expired`, or `cancelled`
- **THEN** the system SHALL allow a new opportunity for the same campaign and creator pair

### Requirement: Opportunity listing and filtering
The system SHALL provide a paginated opportunity list with MVP filters.

#### Scenario: List opportunities
- **WHEN** an authenticated internal user requests the opportunity list
- **THEN** the system SHALL return a paginated response with `data`, `meta`, and `links`

#### Scenario: Filter opportunities
- **WHEN** an authenticated internal user filters opportunities by `campaign`, `creator`, `status`, `channel`, `assigned_to`, response state, first contact date, or last contact date
- **THEN** the system SHALL return only opportunities matching those filters

### Requirement: Opportunity acceptance
The system SHALL provide an authenticated action that accepts an eligible opportunity.

#### Scenario: Accept eligible opportunity
- **WHEN** an authenticated internal user accepts an eligible opportunity
- **THEN** the system SHALL mark the opportunity as `accepted`, record the transition, and return the updated opportunity in a top-level `data` envelope

#### Scenario: Reject accepting terminal opportunity
- **WHEN** an authenticated internal user attempts to accept an opportunity that is already terminal
- **THEN** the system SHALL return a validation or conflict error response

### Requirement: Opportunity soft deletion
The system SHALL soft-delete opportunities instead of permanently deleting them through the API.

#### Scenario: Delete opportunity
- **WHEN** an authenticated internal user deletes an opportunity
- **THEN** the opportunity SHALL be soft deleted and excluded from default opportunity lists

### Requirement: Opportunity authentication
The system SHALL require Sanctum authentication for all opportunity endpoints.

#### Scenario: Unauthenticated opportunity request
- **WHEN** an unauthenticated client requests an opportunity endpoint
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: Opportunity API documentation
The system SHALL document opportunity endpoints, filters, request fields, statuses, and response shapes in the OpenAPI document used by API consumers.

#### Scenario: Opportunity endpoints are discoverable
- **WHEN** a developer reads `src/docs/openapi.yaml`
- **THEN** the opportunity list, create, show, update, delete, and accept endpoints SHALL be documented
