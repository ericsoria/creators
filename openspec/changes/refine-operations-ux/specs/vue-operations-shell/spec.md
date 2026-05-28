## MODIFIED Requirements

### Requirement: App navigation shell
The Vue app SHALL provide a minimal grouped navigation shell for dashboard, prospect, creator, brand, and settings/system areas.

#### Scenario: Navigate primary areas
- **WHEN** an authenticated operator uses the primary navigation
- **THEN** the UI SHALL allow access to Dashboard, Prospects, Creators, and Brands as primary areas

#### Scenario: Navigate nested brand area
- **WHEN** an authenticated operator expands or views the Brands navigation group
- **THEN** the UI SHALL expose Campaigns as a nested Brands item while preserving the existing campaigns route

#### Scenario: Navigate settings area
- **WHEN** an authenticated operator uses the Settings navigation group
- **THEN** the UI SHALL allow access to Cities, Tags, and Social Accounts

### Requirement: Operations UI design tokens
The Vue app SHALL implement and document a restrained operations design system with explicit color, spacing, typography, focus, and state conventions, using black as the primary accent.

#### Scenario: Design tokens are documented
- **WHEN** a developer reads `src/docs/ui.md`
- **THEN** the documented tokens SHALL include background, surface, text, muted text, border, black primary accent, success, danger, and attention colors

#### Scenario: UI uses design tokens
- **WHEN** the operations UI renders shared components
- **THEN** the components SHALL use the documented design tokens instead of ad hoc colors

#### Scenario: Active navigation uses black accent
- **WHEN** a navigation item is active or a primary action is emphasized
- **THEN** the UI SHALL use the black accent token in a restrained, accessible way
