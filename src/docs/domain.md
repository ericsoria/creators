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
- `hasMany Opportunity`

Campaigns use soft deletes.

## Prospect

Discovered creator or brand profile that has not yet become a managed creator or brand record.

Fields:

- `id`
- `prospect_type`
- `platform`
- `handle`
- `profile_url`
- `name`
- `city_name`
- `country_name`
- `category`
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

Allowed prospect types are `creator` and `brand`.

Prospects use soft deletes. Creator prospect approval creates a creator and optional initial creator social account. Brand prospect approval creates a brand and optional initial brand social account.

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
- `hasMany Opportunity`

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

## Opportunity

Campaign-to-creator outreach record. An opportunity means a specific campaign has been offered or prepared for a specific creator; it is not yet a confirmed collaboration.

Fields:

- `id`
- `campaign_id`
- `creator_id`
- `status`
- `channel`
- `source_account`
- `message_template`
- `first_contacted_at`
- `last_contacted_at`
- `responded_at`
- `follow_up_count`
- `rejection_reason`
- `notes`
- `assigned_to`
- `converted_to_collaboration_id`

Statuses:

- `draft`
- `contacted`
- `follow_up`
- `interested`
- `accepted`
- `rejected`
- `ghosted`
- `expired`
- `cancelled`

Relationships:

- `belongsTo Campaign`
- `belongsTo Creator`
- `belongsTo User` as assigned user
- `hasMany OpportunityEvent`

Opportunities use soft deletes. The system prevents duplicate active opportunities for the same campaign and creator pair.

## OpportunityEvent

Append-only operational timeline entry for an opportunity.

Fields:

- `id`
- `opportunity_id`
- `type`
- `old_status`
- `new_status`
- `message`
- `metadata`
- `created_by`

Event types:

- `contacted`
- `follow_up_sent`
- `creator_replied`
- `accepted`
- `rejected`
- `ghosted`
- `note`

Relationships:

- `belongsTo Opportunity`
- `belongsTo User` as created user
