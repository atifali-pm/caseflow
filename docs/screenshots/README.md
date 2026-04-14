# Screenshots

These PNGs are referenced by the root [README](../../README.md). They're captured reproducibly by [`scripts/screenshots.mjs`](../../scripts/screenshots.mjs), which drives headless Chrome through real provider and client flows against a freshly seeded database.

| Filename | What it shows |
|---|---|
| `01-landing.png` | Public landing page at `/` |
| `02-pricing.png` | Three-tier pricing page at `/pricing` |
| `03-admin-dashboard.png` | Filament dashboard with stat widgets and recent activity |
| `04-cases-list.png` | Cases table at `/admin/cases` (Sarah's 22 seeded cases) |
| `05-case-detail.png` | Single case view with milestones, documents, and messages |
| `06-clients-list.png` | Clients table at `/admin/clients` |
| `07-portal-dashboard.png` | Client portal dashboard at `/portal` |

## Recommended capture settings

- **Viewport:** 1440×900 (matches the script default)
- **Theme:** light
- **Browser:** clean profile or incognito (no extension chrome visible)
- **Hide bookmarks bar:** Ctrl+Shift+B in Chromium-based browsers

## Re-running the capture

```bash
# Make sure the Docker stack is up
docker-compose up -d

# Fresh seed for clean demo data
docker exec caseflow-app php artisan migrate:fresh --seed

# Capture all screenshots
node scripts/screenshots.mjs
```

The script overwrites existing PNGs in this folder.
