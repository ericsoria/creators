# API Conventions

## Versioning

All API endpoints must be versioned under `/api/v1`.

```txt
/api/v1/user
/api/v1/brands
/api/v1/campaigns
```

## Authentication

Private endpoints must use `auth:sanctum` unless explicitly documented as public.

The operations UI uses bearer tokens issued by the API:

```txt
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/user
```

`POST /api/v1/auth/login` accepts `email` and `password` and returns `data.token` plus `data.user`. `POST /api/v1/auth/logout` requires the bearer token and revokes the current token.

## Response Shapes

Single resources are returned with a top-level `data` key.

```json
{
  "data": {
    "id": 1
  }
}
```

Paginated lists use Laravel paginator resource conventions.

```json
{
  "data": [],
  "meta": {},
  "links": {}
}
```

Validation errors follow Laravel's JSON validation shape.

```json
{
  "message": "The given data was invalid.",
  "errors": {}
}
```

Unauthorized errors are JSON for API routes.

```json
{
  "message": "Unauthenticated."
}
```

## Catalog Endpoints

All catalog endpoints require Sanctum authentication.

```txt
GET    /api/v1/cities

GET    /api/v1/tags
POST   /api/v1/tags

GET    /api/v1/brands
POST   /api/v1/brands
GET    /api/v1/brands/{brand}
PATCH  /api/v1/brands/{brand}
DELETE /api/v1/brands/{brand}

GET    /api/v1/campaigns
POST   /api/v1/campaigns
GET    /api/v1/campaigns/{campaign}
PATCH  /api/v1/campaigns/{campaign}
DELETE /api/v1/campaigns/{campaign}
```

## Catalog Filters

Brand list filters:

- `status`
- `city`
- `tag`
- `per_page`

Campaign list filters:

- `status`
- `brand`
- `tag`
- `starts_at`
- `ends_at`
- `per_page`

List endpoints use Laravel pagination and default to newest records first where the endpoint is paginated.

## Creator Network Endpoints

All creator network endpoints require Sanctum authentication.

```txt
GET    /api/v1/prospects
POST   /api/v1/prospects
GET    /api/v1/prospects/{prospect}
PATCH  /api/v1/prospects/{prospect}
DELETE /api/v1/prospects/{prospect}
POST   /api/v1/prospects/{prospect}/approve-as-creator
POST   /api/v1/prospects/{prospect}/approve-as-brand

GET    /api/v1/creators
POST   /api/v1/creators
GET    /api/v1/creators/{creator}
PATCH  /api/v1/creators/{creator}
DELETE /api/v1/creators/{creator}
GET    /api/v1/creators/{creator}/social-accounts

GET    /api/v1/social-accounts
POST   /api/v1/social-accounts
GET    /api/v1/social-accounts/{social_account}
PATCH  /api/v1/social-accounts/{social_account}
DELETE /api/v1/social-accounts/{social_account}
GET    /api/v1/brands/{brand}/social-accounts

GET    /api/v1/opportunities
POST   /api/v1/opportunities
GET    /api/v1/opportunities/{opportunity}
PATCH  /api/v1/opportunities/{opportunity}
DELETE /api/v1/opportunities/{opportunity}
POST   /api/v1/opportunities/{opportunity}/accept
GET    /api/v1/opportunities/{opportunity}/events
POST   /api/v1/opportunities/{opportunity}/events
```

Prospect filters:

- `prospect_type`
- `status`
- `platform`
- `category`
- `source`
- `contacted_at`
- `responded_at`
- `per_page`

Creator filters:

- `status`
- `city`
- `tag`
- `ugc_only`
- `accepts_barter`
- `search`
- `per_page`

Social account filters:

- `platform`
- `accountable_type`
- `accountable_id`
- `per_page`

Approving a creator prospect creates a creator, creates an initial creator social account from prospect social fields when present, marks the prospect `approved`, and sets `approved_at`.

Approving a brand prospect creates a brand, derives or accepts a unique brand slug, creates an initial brand social account from prospect social fields when present, marks the prospect `approved`, and sets `approved_at`.

Opportunity filters:

- `campaign`
- `creator`
- `status`
- `channel`
- `assigned_to`
- `responded`
- `first_contacted_at`
- `last_contacted_at`
- `per_page`

Opportunities represent outreach from a campaign to a creator. Accepting an opportunity marks it `accepted` and records an opportunity event, but confirmed collaboration execution is handled by a future capability.
