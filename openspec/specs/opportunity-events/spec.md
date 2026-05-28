## Requirements

### Requirement: Opportunity event persistence
The system SHALL persist opportunity events with opportunity, type, old status, new status, message, metadata, and creator user fields.

#### Scenario: Create opportunity event
- **WHEN** an authenticated internal user creates an event for an existing opportunity with valid data
- **THEN** the system SHALL persist the event and return it in a top-level `data` envelope

#### Scenario: Reject event without opportunity
- **WHEN** an authenticated internal user creates an event without a valid opportunity
- **THEN** the system SHALL return a validation error response

### Requirement: Opportunity event types
The system SHALL allow only documented MVP event types for opportunity history.

#### Scenario: Persist allowed opportunity event type
- **WHEN** an authenticated internal user creates an opportunity event with an allowed type such as `contacted`, `follow_up_sent`, `creator_replied`, `accepted`, `rejected`, `ghosted`, or `note`
- **THEN** the system SHALL persist that event type

#### Scenario: Reject unsupported opportunity event type
- **WHEN** an authenticated internal user creates an opportunity event with an unsupported type
- **THEN** the system SHALL return a validation error response

### Requirement: Opportunity event listing
The system SHALL provide authenticated endpoints to list events for an opportunity in chronological order.

#### Scenario: List opportunity events
- **WHEN** an authenticated internal user requests events for an opportunity
- **THEN** the system SHALL return events for that opportunity ordered by creation time

### Requirement: Opportunity event status history
Opportunity events SHALL be able to record old and new opportunity statuses for transition history.

#### Scenario: Record opportunity status transition
- **WHEN** an opportunity status changes through an action or event workflow
- **THEN** the system SHALL be able to persist an opportunity event containing the old status and new status

### Requirement: Opportunity event authentication
The system SHALL require Sanctum authentication for all opportunity event endpoints.

#### Scenario: Unauthenticated opportunity event request
- **WHEN** an unauthenticated client requests an opportunity event endpoint
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: Opportunity event API documentation
The system SHALL document opportunity event endpoints, request fields, event types, and response shapes in the OpenAPI document used by API consumers.

#### Scenario: Opportunity event endpoints are discoverable
- **WHEN** a developer reads `src/docs/openapi.yaml`
- **THEN** the opportunity event list and create endpoints SHALL be documented
