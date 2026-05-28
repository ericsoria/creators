import { api } from './api';

const creatorType = 'App\\Models\\Creator';
const brandType = 'App\\Models\\Brand';
const brandOptions = { source: 'brands', path: '/brands', labelField: 'name' };
const cityOptions = { source: 'cities', path: '/cities', labelField: ['name', 'country'] };
const tagOptions = { source: 'tags', path: '/tags', labelField: ['name', 'type'] };

export const resources = {
    brands: {
        title: 'Brands',
        path: '/brands',
        createLabel: 'New brand',
        filters: ['status', 'city', 'tag'],
        columns: ['name', 'industry', 'status', 'website_url'],
        fields: [
            ['name', 'Name', 'text', true],
            ['slug', 'Slug', 'text', true],
            ['industry', 'Industry'],
            ['website_url', 'Website URL'],
            ['status', 'Status'],
            ['description', 'Description', 'textarea'],
            ['notes', 'Notes', 'textarea'],
            ['city_ids', 'Cities', 'multi-relation', false, { ...cityOptions, relation: 'cities' }],
            ['tag_ids', 'Tags', 'multi-relation', false, { ...tagOptions, relation: 'tags' }],
        ],
    },
    campaigns: {
        title: 'Campaigns',
        path: '/campaigns',
        createLabel: 'New campaign',
        filters: ['status', 'brand', 'tag', 'starts_at', 'ends_at'],
        columns: ['name', 'brand_id', 'status', 'starts_at', 'ends_at'],
        fields: [
            ['brand_id', 'Brand', 'relation', true, brandOptions],
            ['name', 'Name', 'text', true],
            ['status', 'Status', 'select', false, ['draft', 'active', 'paused', 'completed', 'cancelled']],
            ['starts_at', 'Starts at', 'date'],
            ['ends_at', 'Ends at', 'date'],
            ['objective', 'Objective'],
            ['compensation_type', 'Compensation type'],
            ['description', 'Description', 'textarea'],
            ['requirements', 'Requirements', 'textarea'],
            ['notes', 'Notes', 'textarea'],
            ['tag_ids', 'Tags', 'multi-relation', false, { ...tagOptions, relation: 'tags' }],
        ],
    },
    cities: {
        title: 'Cities',
        path: '/cities',
        readonly: true,
        filters: [],
        columns: ['name', 'slug', 'country', 'timezone'],
        fields: [],
    },
    tags: {
        title: 'Tags',
        path: '/tags',
        createLabel: 'New tag',
        createOnly: true,
        filters: [],
        columns: ['name', 'slug', 'type'],
        fields: [
            ['name', 'Name', 'text', true],
            ['slug', 'Slug', 'text', true],
            ['type', 'Type', 'text', true],
        ],
    },
    prospects: {
        title: 'Prospects',
        path: '/prospects',
        createLabel: 'New prospect',
        filters: ['prospect_type', 'status', 'platform', 'category', 'source'],
        columns: ['name', 'prospect_type', 'platform', 'handle', 'status', 'category'],
        fields: [
            ['prospect_type', 'Type', 'select', true, ['creator', 'brand']],
            ['platform', 'Platform', 'text', true],
            ['handle', 'Handle', 'text', true],
            ['profile_url', 'Profile URL'],
            ['name', 'Name'],
            ['city_name', 'City'],
            ['country_name', 'Country'],
            ['category', 'Category'],
            ['status', 'Status', 'select', false, ['discovered', 'contacted', 'follow_up', 'interested', 'approved', 'rejected', 'ghosted', 'archived']],
            ['source', 'Source'],
            ['contacted_at', 'Contacted at', 'date'],
            ['responded_at', 'Responded at', 'date'],
            ['rejection_reason', 'Rejection reason', 'textarea'],
            ['notes', 'Notes', 'textarea'],
        ],
        approveFields: [
            ['name', 'Name'],
            ['username', 'Username'],
            ['email', 'Email'],
            ['phone', 'Phone'],
            ['bio', 'Bio', 'textarea'],
            ['ugc_only', 'UGC only', 'checkbox'],
            ['accepts_barter', 'Accepts barter', 'checkbox'],
            ['notes', 'Notes', 'textarea'],
        ],
        approveBrandFields: [
            ['name', 'Name'],
            ['slug', 'Slug'],
            ['industry', 'Industry'],
            ['website_url', 'Website URL'],
            ['status', 'Status'],
            ['description', 'Description', 'textarea'],
            ['notes', 'Notes', 'textarea'],
        ],
    },
    creators: {
        title: 'Creators',
        path: '/creators',
        createLabel: 'New creator',
        filters: ['status', 'city', 'tag', 'ugc_only', 'accepts_barter', 'search'],
        columns: ['name', 'username', 'status', 'rating', 'ugc_only', 'accepts_barter'],
        fields: [
            ['name', 'Name', 'text', true],
            ['username', 'Username'],
            ['email', 'Email'],
            ['phone', 'Phone'],
            ['status', 'Status', 'select', false, ['active', 'inactive', 'paused', 'blacklisted']],
            ['rating', 'Rating', 'number'],
            ['ugc_only', 'UGC only', 'checkbox'],
            ['accepts_barter', 'Accepts barter', 'checkbox'],
            ['joined_at', 'Joined at', 'date'],
            ['last_active_at', 'Last active at', 'date'],
            ['bio', 'Bio', 'textarea'],
            ['notes', 'Notes', 'textarea'],
            ['city_ids', 'Cities', 'multi-relation', false, { ...cityOptions, relation: 'cities' }],
            ['tag_ids', 'Tags', 'multi-relation', false, { ...tagOptions, relation: 'tags' }],
        ],
    },
    socialAccounts: {
        title: 'Social Accounts',
        path: '/social-accounts',
        createLabel: 'New social account',
        filters: ['platform', 'accountable_type', 'accountable_id'],
        columns: ['platform', 'handle', 'accountable_type', 'accountable_id', 'is_primary'],
        fields: [
            ['owner', 'Owner', 'owner-relation', true, {
                sources: [
                    { source: 'creators', path: '/creators', type: creatorType, label: 'Creator', labelField: ['name', 'username'] },
                    { source: 'brands', path: '/brands', type: brandType, label: 'Brand', labelField: 'name' },
                ],
            }],
            ['platform', 'Platform', 'text', true],
            ['handle', 'Handle', 'text', true],
            ['url', 'URL'],
            ['is_primary', 'Primary', 'checkbox'],
        ],
    },
};

export const dashboardClient = {
    async overview() {
        const [leads, interested, creators, brands, activeCampaigns, draftCampaigns] = await Promise.all([
            api.list('/prospects', { per_page: 1 }),
            api.list('/prospects', { status: 'interested', per_page: 5 }),
            api.list('/creators', { per_page: 5 }),
            api.list('/brands', { per_page: 1 }),
            api.list('/campaigns', { status: 'active', per_page: 5 }),
            api.list('/campaigns', { status: 'draft', per_page: 5 }),
        ]);

        return {
            counts: {
                leads: leads.meta?.total ?? leads.data.length,
                creators: creators.meta?.total ?? creators.data.length,
                brands: brands.meta?.total ?? brands.data.length,
                activeCampaigns: activeCampaigns.meta?.total ?? activeCampaigns.data.length,
                draftCampaigns: draftCampaigns.meta?.total ?? draftCampaigns.data.length,
            },
            attention: {
                interestedLeads: interested.data,
                activeCampaigns: activeCampaigns.data,
                draftCampaigns: draftCampaigns.data,
                recentCreators: creators.data,
            },
        };
    },
};
