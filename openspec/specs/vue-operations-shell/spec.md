## Requirements

### Requirement: Vue SPA shell
The system SHALL provide a Vue 3 single-page operations app served by Laravel while consuming business data from `/api/v1`.

#### Scenario: Load operations app
- **WHEN** an operator opens the operations UI route
- **THEN** the system SHALL render the Vue SPA shell

#### Scenario: API-driven data access
- **WHEN** the Vue app needs business data
- **THEN** it SHALL request that data from `/api/v1` endpoints instead of relying on server-rendered payloads

### Requirement: API auth client
The Vue app SHALL authenticate against API endpoints using bearer tokens and SHALL attach the token to protected API requests.

#### Scenario: Authenticated API request
- **WHEN** an authenticated operator requests protected data from the UI
- **THEN** the API client SHALL include the bearer token in the request

#### Scenario: Unauthorized API response
- **WHEN** an API request returns unauthorized
- **THEN** the UI SHALL clear the authenticated state and route the operator to login

#### Scenario: Login through API
- **WHEN** an operator submits valid credentials on the login screen
- **THEN** the UI SHALL call `/api/v1/auth/login`, persist the returned token according to the auth storage strategy, load the current user state, and route to the dashboard

#### Scenario: Logout through API
- **WHEN** an authenticated operator logs out
- **THEN** the UI SHALL call `/api/v1/auth/logout`, clear local auth state, and route to login

### Requirement: App navigation shell
The Vue app SHALL provide a minimal navigation shell for dashboard, catalog, creator network, and settings/system areas.

#### Scenario: Navigate primary areas
- **WHEN** an authenticated operator uses the primary navigation
- **THEN** the UI SHALL allow access to Dashboard, Brands, Campaigns, Creator Leads, Creators, Social Accounts, Cities, and Tags

### Requirement: Minimal UI primitives
The Vue app SHALL include reusable minimal primitives for buttons, inputs, tables, filters, drawers, badges, empty states, loading states, errors, and confirmations.

#### Scenario: Consistent CRUD surface
- **WHEN** a resource page renders a list or form
- **THEN** it SHALL use shared UI primitives for consistent spacing, state, and interaction behavior

### Requirement: Operations UI design tokens
The Vue app SHALL implement and document a restrained operations design system with explicit color, spacing, typography, focus, and state conventions.

#### Scenario: Design tokens are documented
- **WHEN** a developer reads `src/docs/ui.md`
- **THEN** the documented tokens SHALL include background, surface, text, muted text, border, primary accent, success, danger, and attention colors

#### Scenario: UI uses design tokens
- **WHEN** the operations UI renders shared components
- **THEN** the components SHALL use the documented design tokens instead of ad hoc colors

### Requirement: Accessible shell and primitives
The Vue app SHALL provide semantic, keyboard-accessible primitives and layout interactions.

#### Scenario: Drawer accessibility
- **WHEN** a drawer opens
- **THEN** focus SHALL move into the drawer, remain trapped while open, close on Escape, and return to the opening control after close

#### Scenario: Form accessibility and async state
- **WHEN** an operator submits a form
- **THEN** the submit control SHALL prevent duplicate submits while pending and API validation errors SHALL be associated with the relevant fields where possible

#### Scenario: Reduced motion preference
- **WHEN** the operator prefers reduced motion
- **THEN** non-essential UI transitions SHALL be disabled or reduced

### Requirement: Responsive operations lists
The Vue app SHALL keep resource lists usable on desktop and mobile widths.

#### Scenario: Narrow viewport list usage
- **WHEN** a resource list is viewed at 375px width
- **THEN** the list SHALL remain readable and operable through a deliberate horizontal-scroll or stacked-card responsive pattern
