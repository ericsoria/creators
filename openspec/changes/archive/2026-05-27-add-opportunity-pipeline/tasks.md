## 1. Data Model

- [x] 1.1 Create opportunities migration with campaign, creator, status, outreach, assignment, conversion reference, timestamps, soft deletes, and indexes.
- [x] 1.2 Create opportunity events migration with opportunity, event type, old/new status, message, metadata, created_by, timestamps, and indexes.
- [x] 1.3 Add `Opportunity` model with fillable/casts, status constants, terminal status helpers, and relationships.
- [x] 1.4 Add `OpportunityEvent` model with fillable/casts, event type constants, and relationships.
- [x] 1.5 Add campaign and creator `hasMany` opportunity relationships.
- [x] 1.6 Add factories for opportunities and opportunity events.

## 2. Validation And Resources

- [x] 2.1 Add Form Requests for listing, creating, updating, accepting, and deleting opportunities as needed.
- [x] 2.2 Add Form Requests for listing and creating opportunity events.
- [x] 2.3 Add duplicate active opportunity validation for campaign and creator pairs.
- [x] 2.4 Add `OpportunityResource` with campaign, creator, assigned user, and conversion fields when loaded.
- [x] 2.5 Add `OpportunityEventResource` with opportunity, created user, status transition, message, and metadata fields.

## 3. Opportunity API

- [x] 3.1 Add `OpportunityController` with index, store, show, update, destroy, and accept endpoints.
- [x] 3.2 Implement opportunity filtering by campaign, creator, status, channel, assigned user, response state, first contacted date, and last contacted date.
- [x] 3.3 Add `AcceptOpportunityAction` for accepted transition and transition event creation.
- [x] 3.4 Ensure terminal opportunities cannot be accepted again.
- [x] 3.5 Add authenticated `/api/v1/opportunities` routes and accept action route.

## 4. Opportunity Event API

- [x] 4.1 Add `OpportunityEventController` for listing and creating events under an opportunity.
- [x] 4.2 Validate allowed event types and optional old/new status values.
- [x] 4.3 Persist created_by from the authenticated internal user.
- [x] 4.4 If event creation supports status updates, ensure opportunity status and event old/new status remain consistent.
- [x] 4.5 Add authenticated nested opportunity event routes.

## 5. Tests

- [x] 5.1 Add feature tests proving opportunity endpoints reject unauthenticated requests.
- [x] 5.2 Add feature tests for opportunity create, update, show, list, delete, and pagination response shapes.
- [x] 5.3 Add feature tests for opportunity filters.
- [x] 5.4 Add feature tests for duplicate active opportunity prevention and terminal re-outreach allowance.
- [x] 5.5 Add feature tests for opportunity acceptance and terminal acceptance rejection.
- [x] 5.6 Add feature tests for opportunity event list/create, validation, created_by, and authentication behavior.
- [x] 5.7 Add unit or feature tests for campaign/creator/opportunity/event relationships.

## 6. Documentation

- [x] 6.1 Update `src/docs/openapi.yaml` with opportunity and opportunity event endpoints, schemas, filters, statuses, and responses.
- [x] 6.2 Update `src/docs/api.md` with opportunity endpoints and filters.
- [x] 6.3 Update `src/docs/domain.md` with opportunity and opportunity event domain behavior.
- [x] 6.4 Update `src/docs/status-flows.md` with opportunity statuses and event types.
- [x] 6.5 Update `src/docs/changelog.md` with the opportunity pipeline change summary.

## 7. Verification

- [x] 7.1 Run Laravel tests.
- [x] 7.2 Run Pint style check for touched PHP files.
- [x] 7.3 Confirm OpenAPI docs include all new endpoints and response shapes.
- [x] 7.4 Confirm no collaboration execution, visit scheduling, scoring, automation, or frontend UI scope was added.
