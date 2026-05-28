## ADDED Requirements

### Requirement: Campaign opportunity ownership
The system SHALL allow campaigns to own outreach opportunities.

#### Scenario: Campaign has opportunities
- **WHEN** an authenticated internal user retrieves or works with a campaign in backend code
- **THEN** the campaign SHALL be able to expose its related opportunities

#### Scenario: Campaign deletion preserves opportunity integrity
- **WHEN** a campaign has opportunities
- **THEN** the system SHALL preserve referential integrity according to the campaign and opportunity deletion rules
