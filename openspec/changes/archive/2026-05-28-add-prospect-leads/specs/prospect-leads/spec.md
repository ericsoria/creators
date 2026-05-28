## ADDED Requirements

### Requirement: Prospect persistence
The system SHALL persist prospect leads with prospect type, platform, handle, profile URL, name, location text, category, status, contact timestamps, approval timestamp, rejection reason, notes, and source fields.

#### Scenario: Create creator prospect
- **WHEN** an authenticated internal user creates a prospect with `prospect_type` of `creator` and valid data
- **THEN** the system SHALL persist the prospect and return it in a top-level `data` envelope

#### Scenario: Create brand prospect
- **WHEN** an authenticated internal user creates a prospect with `prospect_type` of `brand` and valid data
- **THEN** the system SHALL persist the prospect and return it in a top-level `data` envelope

#### Scenario: Reject invalid prospect
- **WHEN** an authenticated internal user creates or updates a prospect with invalid data
- **THEN** the system SHALL return a validation error response

### Requirement: Prospect types
The system SHALL allow only the prospect types `creator` and `brand`.

#### Scenario: Persist allowed prospect type
- **WHEN** an authenticated internal user creates or updates a prospect with an allowed prospect type
- **THEN** the system SHALL persist that prospect type

#### Scenario: Reject unsupported prospect type
- **WHEN** an authenticated internal user creates or updates a prospect with an unsupported prospect type
- **THEN** the system SHALL return a validation error response

### Requirement: Prospect statuses
The system SHALL allow only the prospect statuses `discovered`, `contacted`, `follow_up`, `interested`, `approved`, `rejected`, `ghosted`, and `archived`.

#### Scenario: Persist allowed prospect status
- **WHEN** an authenticated internal user creates or updates a prospect with an allowed status
- **THEN** the system SHALL persist that status

#### Scenario: Reject unsupported prospect status
- **WHEN** an authenticated internal user creates or updates a prospect with an unsupported status
- **THEN** the system SHALL return a validation error response

### Requirement: Prospect listing and filtering
The system SHALL provide a paginated prospect list with MVP filters.

#### Scenario: List prospects
- **WHEN** an authenticated internal user requests the prospect list
- **THEN** the system SHALL return a paginated response with `data`, `meta`, and `links`

#### Scenario: Filter prospects
- **WHEN** an authenticated internal user filters prospects by `prospect_type`, `status`, `platform`, `category`, `source`, or date range
- **THEN** the system SHALL return only prospects matching those filters

### Requirement: Approve creator prospect
The system SHALL provide an authenticated action that approves an eligible creator prospect and creates a creator in the private network.

#### Scenario: Approve eligible creator prospect
- **WHEN** an authenticated internal user approves an eligible prospect with `prospect_type` of `creator`
- **THEN** the system SHALL mark the prospect as `approved`, set `approved_at`, create a creator, create an initial creator social account when social fields exist, and return the created creator in a top-level `data` envelope

#### Scenario: Reject creator approval for non-creator prospect
- **WHEN** an authenticated internal user attempts to approve a non-creator prospect as a creator
- **THEN** the system SHALL return a validation or conflict error response

### Requirement: Approve brand prospect
The system SHALL provide an authenticated action that approves an eligible brand prospect and creates a brand.

#### Scenario: Approve eligible brand prospect
- **WHEN** an authenticated internal user approves an eligible prospect with `prospect_type` of `brand`
- **THEN** the system SHALL mark the prospect as `approved`, set `approved_at`, create a brand, create an initial brand social account when social fields exist, and return the created brand in a top-level `data` envelope

#### Scenario: Reject brand approval for non-brand prospect
- **WHEN** an authenticated internal user attempts to approve a non-brand prospect as a brand
- **THEN** the system SHALL return a validation or conflict error response

### Requirement: Prospect approval protection
The system SHALL reject approval of already approved prospects.

#### Scenario: Reject approving already approved prospect
- **WHEN** an authenticated internal user attempts to approve a prospect that is already approved
- **THEN** the system SHALL return a validation or conflict error response

### Requirement: Prospect soft deletion
The system SHALL soft-delete prospects instead of permanently deleting them through the API.

#### Scenario: Delete prospect
- **WHEN** an authenticated internal user deletes a prospect
- **THEN** the prospect SHALL be soft deleted and excluded from default prospect lists

### Requirement: Prospect authentication
The system SHALL require Sanctum authentication for all prospect endpoints.

#### Scenario: Unauthenticated prospect request
- **WHEN** an unauthenticated client requests a prospect endpoint
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: Prospect API documentation
The system SHALL document prospect endpoints, filters, request fields, statuses, prospect types, approval actions, and response shapes in the OpenAPI document used by API consumers.

#### Scenario: Prospect endpoints are discoverable
- **WHEN** a developer reads `src/docs/openapi.yaml`
- **THEN** the prospect list, create, show, update, delete, approve-as-creator, and approve-as-brand endpoints SHALL be documented
