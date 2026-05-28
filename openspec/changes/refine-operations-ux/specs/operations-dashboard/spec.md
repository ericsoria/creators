## MODIFIED Requirements

### Requirement: Operational dashboard overview
The UI SHALL provide a dashboard focused on actionable operational signals from existing API data.

#### Scenario: View dashboard
- **WHEN** an authenticated operator opens the dashboard
- **THEN** the UI SHALL show concise counts and shortcuts for prospects, creators, brands, and campaigns

#### Scenario: Dashboard data strategy
- **WHEN** the dashboard loads operational overview data
- **THEN** the UI SHALL use a dedicated dashboard API client that either calls `/api/v1/dashboard/overview` or composes existing `/api/v1` endpoints without leaking that approximation into presentation components
