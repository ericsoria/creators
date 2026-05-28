## ADDED Requirements

### Requirement: Brand prospect approval source
The system SHALL allow brands to be created from approved prospects with `prospect_type` of `brand`.

#### Scenario: Brand originates from prospect
- **WHEN** an authenticated internal user approves an eligible brand prospect
- **THEN** the created brand SHALL use prospect and approval data for its initial fields

#### Scenario: Brand approval creates social account
- **WHEN** an approved brand prospect has platform and handle data
- **THEN** the created brand SHALL receive an initial social account from that prospect data
