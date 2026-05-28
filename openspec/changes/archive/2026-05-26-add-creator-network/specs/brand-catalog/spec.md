## ADDED Requirements

### Requirement: Brand social account ownership
The system SHALL allow brands to own social accounts through the shared social account model.

#### Scenario: Brand has social accounts
- **WHEN** an authenticated internal user retrieves a brand with social accounts loaded
- **THEN** the response SHALL be able to include social accounts owned by that brand

#### Scenario: Brand social accounts do not change brand catalog behavior
- **WHEN** an authenticated internal user uses existing brand catalog endpoints without requesting social account data
- **THEN** existing brand catalog response behavior SHALL remain compatible
