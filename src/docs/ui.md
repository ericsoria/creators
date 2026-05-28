# Operations UI Conventions

The operations UI is a Vue 3 SPA mounted at `/app` and backed by `/api/v1` only. It uses a quiet, record-first interface for internal operators.

## Design Tokens

- Background: `#F7F3EC`
- Surface: `#FFFDF8`
- Text: `#171512`
- Muted text: `#6F6A61`
- Border: `#E4DED2`
- Primary accent: `#111111`
- Success: `#2F6B4F`
- Danger: `#A33A2B`
- Attention: `#9A3412`

## Interaction Rules

- Lists stay the main work surface.
- Record inspection, create, and edit flows use drawers where practical.
- Drawers trap focus, close on Escape, and restore focus to the trigger.
- Forms disable duplicate submit while requests are pending.
- API validation errors are shown next to fields where possible.
- Tables use compact desktop rows and stacked mobile cards at narrow widths.
- The dashboard shows attention and shortcuts, not decorative charts.
- Primary actions, active navigation, and focus states use the black accent sparingly; large surfaces keep the warm paper palette.

## Externalization

The frontend reads `VITE_API_BASE_URL` or the `api-base-url` meta tag. Moving the app to a standalone frontend should only require changing that base URL and configuring backend CORS for the deployed hostname.
