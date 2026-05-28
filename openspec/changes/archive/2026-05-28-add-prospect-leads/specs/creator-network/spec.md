## ADDED Requirements

### Requirement: Creator prospect approval source
The system SHALL allow creators to be created from approved prospects with `prospect_type` of `creator`.

#### Scenario: Creator originates from prospect
- **WHEN** an authenticated internal user approves an eligible creator prospect
- **THEN** the created creator SHALL use prospect and approval data for its initial fields

#### Scenario: Creator approval creates social account
- **WHEN** an approved creator prospect has platform and handle data
- **THEN** the created creator SHALL receive an initial social account from that prospect data
