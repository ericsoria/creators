## Requirements

### Requirement: Internal user authentication
The system SHALL authenticate internal API users with Laravel Sanctum tokens.

#### Scenario: Successful token authentication
- **WHEN** a valid Sanctum token is provided to a protected API endpoint
- **THEN** the system SHALL authenticate the request as the token owner

#### Scenario: Missing token authentication
- **WHEN** no valid Sanctum token is provided to a protected API endpoint
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: Internal user roles
The system SHALL support the internal roles `admin`, `manager`, `operator`, and `viewer` on users.

#### Scenario: User has allowed role
- **WHEN** a user is created with one of the allowed internal roles
- **THEN** the system SHALL persist that role on the user record

#### Scenario: User has unsupported role
- **WHEN** a request or seeder attempts to assign an unsupported role
- **THEN** the system SHALL reject the role or fail validation

### Requirement: Current authenticated user endpoint
The system SHALL provide a protected endpoint that returns the current authenticated internal user.

#### Scenario: Authenticated current user request
- **WHEN** an authenticated user requests the current user endpoint
- **THEN** the system SHALL return the user's id, name, email, and role in a top-level `data` envelope

#### Scenario: Unauthenticated current user request
- **WHEN** an unauthenticated client requests the current user endpoint
- **THEN** the system SHALL return an unauthorized JSON response

### Requirement: Admin seed user
The system SHALL provide a repeatable way to seed an initial admin user for local and MVP environments without hardcoding secrets in source control.

#### Scenario: Admin seed uses environment configuration
- **WHEN** the database seeder creates the initial admin user
- **THEN** the admin email and password SHALL come from environment configuration or documented safe defaults suitable only for local development

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
