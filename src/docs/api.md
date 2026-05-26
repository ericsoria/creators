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
GET    /api/v1/creator-leads
POST   /api/v1/creator-leads
GET    /api/v1/creator-leads/{creator_lead}
PATCH  /api/v1/creator-leads/{creator_lead}
DELETE /api/v1/creator-leads/{creator_lead}
POST   /api/v1/creator-leads/{creator_lead}/approve

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
```

Creator lead filters:

- `status`
- `platform`
- `niche`
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

Approving a creator lead creates a creator, creates an initial creator social account from the lead handle, marks the lead `approved`, and sets `approved_at`.
