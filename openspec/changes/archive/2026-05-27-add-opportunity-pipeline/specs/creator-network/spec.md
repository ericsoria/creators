## ADDED Requirements

### Requirement: Creator opportunity ownership
The system SHALL allow creators to own outreach opportunities.

#### Scenario: Creator has opportunities
- **WHEN** an authenticated internal user retrieves or works with a creator in backend code
- **THEN** the creator SHALL be able to expose its related opportunities

#### Scenario: Creator deletion preserves opportunity integrity
- **WHEN** a creator has opportunities
- **THEN** the system SHALL preserve referential integrity according to the creator and opportunity deletion rules
