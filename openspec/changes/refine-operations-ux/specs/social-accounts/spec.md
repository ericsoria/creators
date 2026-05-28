## ADDED Requirements

### Requirement: Operator-friendly social account owner selection
The operations UI SHALL represent social account owners with human-readable creator and brand labels while preserving the backend owner payload.

#### Scenario: Select creator owner in UI
- **WHEN** an operator selects a creator as the owner of a social account
- **THEN** the UI SHALL submit the creator owner type and selected creator ID expected by the social account API

#### Scenario: Select brand owner in UI
- **WHEN** an operator selects a brand as the owner of a social account
- **THEN** the UI SHALL submit the brand owner type and selected brand ID expected by the social account API
