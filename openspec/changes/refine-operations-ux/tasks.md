## 1. Design Tokens And Documentation

- [x] 1.1 Update the operations primary accent token from olive to black or near-black.
- [x] 1.2 Update focus, primary action, hover, and active navigation styles to use the black accent accessibly.
- [x] 1.3 Update `src/docs/ui.md` with the black accent convention and any changed token examples.

## 2. Sidebar Information Architecture

- [x] 2.1 Refactor sidebar navigation data from a flat list to grouped/nested navigation.
- [x] 2.2 Show primary navigation items: Dashboard, Prospects, Creators, and Brands.
- [x] 2.3 Show Campaigns as a nested item under Brands while keeping the existing `/app/campaigns` route.
- [x] 2.4 Show Settings with nested Cities, Tags, and Social Accounts links.
- [x] 2.5 Ensure navigation remains usable on desktop and mobile widths.

## 3. Relation Field Infrastructure

- [x] 3.1 Extend resource field configuration to describe API-backed relation and multi-relation fields.
- [x] 3.2 Add option-loading support that fetches relation choices from existing authenticated API endpoints.
- [x] 3.3 Update `ResourceForm` to render single relation dropdowns.
- [x] 3.4 Update `ResourceForm` to render multi-relation controls for arrays of IDs.
- [x] 3.5 Preserve existing API payload shapes for relation fields.
- [x] 3.6 Add recoverable UI behavior for failed option loading.

## 4. Resource Form Updates

- [x] 4.1 Replace brand `city_ids` and `tag_ids` CSV fields with relation selectors.
- [x] 4.2 Replace campaign `brand_id` and `tag_ids` raw fields with relation selectors.
- [x] 4.3 Replace creator `city_ids` and `tag_ids` CSV fields with relation selectors.
- [x] 4.4 Replace social account owner class and owner ID fields with operator-friendly owner selection.
- [x] 4.5 Confirm status and enum fields continue to use static selects.

## 5. Dashboard And Copy Alignment

- [x] 5.1 Confirm dashboard labels and shortcuts use Prospects terminology rather than creator lead terminology.
- [x] 5.2 Confirm dashboard links continue to route to the intended resource pages after sidebar regrouping.

## 6. Verification

- [x] 6.1 Run frontend build verification.
- [x] 6.2 Run Laravel tests if API payload or request assumptions are touched.
- [x] 6.3 Manually verify create/update payloads for brands, campaigns, creators, and social accounts.
- [x] 6.4 Manually verify keyboard access and focus visibility for new dropdown/multi-select controls.
- [x] 6.5 Confirm no backend domain behavior, automation, scraping, enrichment, or external integrations were added.
