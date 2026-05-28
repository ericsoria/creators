## Requirements

### Requirement: Operational dashboard overview
The UI SHALL provide a dashboard focused on actionable operational signals from existing API data.

#### Scenario: View dashboard
- **WHEN** an authenticated operator opens the dashboard
- **THEN** the UI SHALL show concise counts and shortcuts for creator leads, creators, brands, and campaigns

#### Scenario: Dashboard data strategy
- **WHEN** the dashboard loads operational overview data
- **THEN** the UI SHALL use a dedicated dashboard API client that either calls `/api/v1/dashboard/overview` or composes existing `/api/v1` endpoints without leaking that approximation into presentation components

### Requirement: No noisy analytics by default
The dashboard SHALL avoid decorative charts and widgets that do not directly support operational decisions.

#### Scenario: Dashboard avoids noise
- **WHEN** the dashboard renders
- **THEN** it SHALL prioritize attention lists, status counts, and direct navigation over decorative visualizations

### Requirement: Dashboard loading and error states
The dashboard SHALL handle API loading and error states gracefully.

#### Scenario: Dashboard API error
- **WHEN** dashboard data cannot be loaded
- **THEN** the UI SHALL show a minimal error state with a retry action

#### Scenario: Dashboard loading state
- **WHEN** dashboard data is loading
- **THEN** the UI SHALL show quiet loading placeholders without layout shift or decorative animation
