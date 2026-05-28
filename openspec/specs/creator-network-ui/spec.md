## Requirements

### Requirement: Creator lead management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, delete, and approve creator leads through `/api/v1/creator-leads`.

#### Scenario: Approve creator lead
- **WHEN** an operator approves a creator lead
- **THEN** the UI SHALL call the API approval endpoint and show the created creator result

### Requirement: Creator management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete creators through `/api/v1/creators`.

#### Scenario: Manage creators
- **WHEN** an authenticated operator manages creators
- **THEN** the UI SHALL support city, tag, status, UGC-only, barter, and search workflows

### Requirement: Social account management UI
The UI SHALL allow authenticated operators to list, filter, create, view, update, and delete social accounts through `/api/v1/social-accounts` and owner-specific endpoints.

#### Scenario: Manage social accounts
- **WHEN** an operator manages creator or brand social accounts
- **THEN** the UI SHALL support platform, handle, URL, primary flag, and owner association

### Requirement: Creator network drawer-first interactions
Creator network resource lists SHALL support record inspection and editing in a drawer where practical.

#### Scenario: Open creator record drawer
- **WHEN** an operator selects a creator or creator lead from a list
- **THEN** the UI SHALL open a focused detail drawer without losing list filters

#### Scenario: Preserve creator network list context
- **WHEN** an operator opens and closes a creator network drawer
- **THEN** the list filters, pagination context, and scroll position SHALL remain stable where practical

### Requirement: Creator network responsive and async behavior
Creator network pages SHALL remain usable on narrow screens and during API mutations.

#### Scenario: Creator network list on mobile
- **WHEN** an operator views creator leads, creators, or social accounts at 375px width
- **THEN** the page SHALL keep row content and primary actions readable through the shared responsive list pattern

#### Scenario: Creator network mutation pending
- **WHEN** a creator lead, creator, approval, or social account mutation is pending
- **THEN** the UI SHALL prevent duplicate submissions and expose success or API validation/error feedback
