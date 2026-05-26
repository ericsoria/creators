## Requirements

### Requirement: Versioned API routing
The system SHALL expose API routes under a versioned `/api/v1` prefix and SHALL keep API routes separate from web routes.

#### Scenario: API route uses v1 prefix
- **WHEN** a client requests a foundation API endpoint
- **THEN** the endpoint path SHALL begin with `/api/v1`

#### Scenario: Web route remains separate
- **WHEN** the application defines browser-facing routes
- **THEN** those routes SHALL NOT be mixed into the API route group

### Requirement: Private API route protection
The system SHALL protect private API endpoints with Sanctum authentication by default.

#### Scenario: Unauthenticated private request
- **WHEN** an unauthenticated client requests a private `/api/v1` endpoint
- **THEN** the system SHALL return an unauthorized JSON response

#### Scenario: Authenticated private request
- **WHEN** an authenticated client requests a private `/api/v1` endpoint
- **THEN** the system SHALL allow the request to reach the endpoint handler

### Requirement: Consistent JSON responses
The system SHALL use consistent JSON response envelopes for API success, paginated list, validation error, and authentication error responses.

#### Scenario: Single resource response
- **WHEN** an API endpoint returns a single resource
- **THEN** the response SHALL wrap the resource in a top-level `data` key

#### Scenario: Paginated list response
- **WHEN** an API endpoint returns a paginated list
- **THEN** the response SHALL include top-level `data`, `meta`, and `links` keys

#### Scenario: Validation error response
- **WHEN** request validation fails
- **THEN** the response SHALL include a `message` key and an `errors` key

### Requirement: Documentation baseline
The system SHALL include internal documentation files for architecture, API conventions, authentication, status flows, OpenAPI, and changelog before domain features are added.

#### Scenario: Documentation files exist
- **WHEN** the foundation change is complete
- **THEN** the `docs/` directory SHALL contain `architecture.md`, `api.md`, `auth.md`, `openapi.yaml`, `status-flows.md`, and `changelog.md`

#### Scenario: OpenAPI documents implemented foundation endpoints
- **WHEN** the foundation change adds an API endpoint
- **THEN** `docs/openapi.yaml` SHALL document that endpoint, its authentication requirement, and its main responses

### Requirement: API testing baseline
The system SHALL include tests proving authentication protection and response shape for foundation API endpoints.

#### Scenario: Private endpoint test coverage
- **WHEN** tests are run
- **THEN** at least one test SHALL verify that a private API endpoint rejects unauthenticated requests

#### Scenario: Authenticated endpoint test coverage
- **WHEN** tests are run
- **THEN** at least one test SHALL verify that an authenticated request receives the expected JSON response shape
