# Domain

## City

Normalized city catalog used to classify brands and future creators.

Fields:

- `id`
- `name`
- `slug`
- `country`
- `timezone`

Relationships:

- `belongsToMany Brand`

## Tag

Flexible taxonomy for catalog classification.

Fields:

- `id`
- `name`
- `slug`
- `type`

Relationships:

- `belongsToMany Brand`
- `belongsToMany Campaign`

Tags use soft deletes.

## Brand

Business or brand that owns campaigns.

Fields:

- `id`
- `name`
- `slug`
- `industry`
- `description`
- `website_url`
- `status`
- `notes`

Relationships:

- `hasMany Campaign`
- `belongsToMany City`
- `belongsToMany Tag`

Brands use soft deletes.

## Campaign

Operational unit for activations, monthly content needs, and creator visits.

Fields:

- `id`
- `brand_id`
- `name`
- `description`
- `objective`
- `status`
- `starts_at`
- `ends_at`
- `compensation_type`
- `requirements`
- `notes`

Statuses:

- `draft`
- `active`
- `paused`
- `completed`
- `cancelled`

Relationships:

- `belongsTo Brand`
- `belongsToMany Tag`

Campaigns use soft deletes.

## CreatorLead

Discovered creator profile that has not yet joined the private network.

Fields:

- `id`
- `platform`
- `handle`
- `profile_url`
- `name`
- `city_name`
- `country_name`
- `niche`
- `status`
- `contacted_at`
- `responded_at`
- `approved_at`
- `rejection_reason`
- `notes`
- `source`

Statuses:

- `discovered`
- `contacted`
- `follow_up`
- `interested`
- `approved`
- `rejected`
- `ghosted`
- `archived`

Creator leads use soft deletes.

## Creator

Approved member of the private creator network.

Fields:

- `id`
- `name`
- `username`
- `email`
- `phone`
- `bio`
- `ugc_only`
- `accepts_barter`
- `status`
- `rating`
- `joined_at`
- `last_active_at`
- `notes`

Statuses:

- `active`
- `inactive`
- `paused`
- `blacklisted`

Relationships:

- `belongsToMany City`
- `belongsToMany Tag`
- `morphMany SocialAccount`

Creators use soft deletes.

## SocialAccount

Shared social profile owned by a creator or brand.

Fields:

- `id`
- `accountable_type`
- `accountable_id`
- `platform`
- `handle`
- `url`
- `is_primary`

Relationships:

- `morphTo accountable`

Social accounts use soft deletes. An owner can have one primary account per platform.
