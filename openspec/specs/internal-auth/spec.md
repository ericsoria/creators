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
