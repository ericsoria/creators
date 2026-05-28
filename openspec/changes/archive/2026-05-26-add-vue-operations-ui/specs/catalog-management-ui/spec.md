## ADDED Requirements

### Requirement: Brand management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete brands through `/api/v1/brands`.

#### Scenario: Manage brands
- **WHEN** an authenticated operator manages brands
- **THEN** the UI SHALL use the API to persist changes and reflect API validation errors

### Requirement: Campaign management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete campaigns through `/api/v1/campaigns`.

#### Scenario: Manage campaigns
- **WHEN** an authenticated operator manages campaigns
- **THEN** the UI SHALL support brand selection, campaign status, date fields, tags, and API validation errors

### Requirement: Taxonomy management UI
The UI SHALL allow authenticated operators to list cities and list/create tags through `/api/v1/cities` and `/api/v1/tags`.

#### Scenario: Manage taxonomy
- **WHEN** an authenticated operator manages taxonomy
- **THEN** the UI SHALL show cities as read-oriented catalog data and tags as createable catalog data

### Requirement: Catalog drawer-first interactions
Catalog resource lists SHALL support record inspection and editing in a drawer where practical.

#### Scenario: Open catalog record drawer
- **WHEN** an operator selects a brand or campaign from a list
- **THEN** the UI SHALL open a focused detail drawer without leaving the list context

#### Scenario: Preserve catalog list context
- **WHEN** an operator opens and closes a catalog record drawer
- **THEN** the list filters, pagination context, and scroll position SHALL remain stable where practical

### Requirement: Catalog responsive and async behavior
Catalog management pages SHALL remain usable on narrow screens and during API mutations.

#### Scenario: Catalog list on mobile
- **WHEN** an operator views brands, campaigns, cities, or tags at 375px width
- **THEN** the page SHALL keep row content and primary actions readable through the shared responsive list pattern

#### Scenario: Catalog mutation pending
- **WHEN** a catalog create, update, or delete request is pending
- **THEN** the UI SHALL prevent duplicate submissions and expose success or API validation/error feedback
