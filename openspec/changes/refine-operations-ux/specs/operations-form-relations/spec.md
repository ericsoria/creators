## ADDED Requirements

### Requirement: Relation form controls
The operations UI SHALL provide form controls that allow operators to select related records by human-readable labels instead of entering raw IDs.

#### Scenario: Select single related record
- **WHEN** an operator edits a form field backed by one related record
- **THEN** the UI SHALL present a dropdown of available records and submit the selected record ID using the API field expected by the endpoint

#### Scenario: Select multiple related records
- **WHEN** an operator edits a form field backed by multiple related records
- **THEN** the UI SHALL present a multi-select control of available records and submit an array of selected record IDs using the API field expected by the endpoint

### Requirement: Relationship option loading
The operations UI SHALL load relationship options from authenticated `/api/v1` endpoints instead of hardcoding entity names in the frontend.

#### Scenario: Load options for relation field
- **WHEN** a form containing relation fields opens
- **THEN** the UI SHALL request the configured option resources and render available options with human-readable labels

#### Scenario: Option loading fails
- **WHEN** relationship options cannot be loaded
- **THEN** the UI SHALL show a recoverable error state and avoid asking the operator to manually enter raw IDs as a fallback

### Requirement: API payload compatibility
The operations UI SHALL preserve existing API payload field names and value shapes when replacing raw ID inputs with relation controls.

#### Scenario: Submit relation payload
- **WHEN** an operator submits a relation-backed form
- **THEN** the UI SHALL send IDs and ID arrays matching the existing API contract
