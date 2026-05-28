## ADDED Requirements

### Requirement: API token login endpoint
The system SHALL provide an API endpoint that exchanges valid internal user credentials for a Sanctum bearer token and current user payload for the Vue operations UI.

#### Scenario: Successful API token login
- **WHEN** an internal user submits valid email and password credentials to `/api/v1/auth/login`
- **THEN** the system SHALL return a bearer token and the authenticated user's id, name, email, and role in a top-level `data` envelope

#### Scenario: Failed API token login
- **WHEN** credentials are missing, invalid, or do not match an internal user
- **THEN** the system SHALL return a validation or unauthorized JSON response without issuing a token

### Requirement: API token logout endpoint
The system SHALL provide an authenticated API endpoint that revokes the current access token used by the Vue operations UI.

#### Scenario: Successful API token logout
- **WHEN** an authenticated internal user sends a logout request to `/api/v1/auth/logout`
- **THEN** the system SHALL revoke the current token and return a successful JSON response

#### Scenario: Unauthenticated API token logout
- **WHEN** an unauthenticated client sends a logout request
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: API auth documentation
The system SHALL document the login, logout, and current user endpoints in the OpenAPI document used by API consumers.

#### Scenario: Auth endpoints are discoverable
- **WHEN** a developer reads `src/docs/openapi.yaml`
- **THEN** the API token login, logout, and current user endpoints SHALL be documented with request and response shapes
