# CaseFlow — Upwork Portfolio Entry

> Paste this into an Upwork portfolio item. Upwork fields are noted inline.

---

## Title

**CaseFlow — Multi-tenant Case Management SaaS (Laravel + Filament + Stripe)**

---

## Project URL

https://github.com/atifali-pm/caseflow

---

## My role

Full-stack engineer and product owner. I built CaseFlow end-to-end: database schema, Filament admin, Livewire client portal, Stripe Cashier integration, REST API, outgoing webhook system, DomPDF invoice generation, reports dashboard, Docker environment, and the reproducible screenshot pipeline.

---

## Project description

CaseFlow is a production-grade case management SaaS platform I built to showcase a complete multi-tenant product with Stripe-backed subscriptions, a polished admin panel, a separate client-facing portal, and a proper API layer with webhooks. It targets the same kind of customer as Clio or MyCase but keeps the stack small, the tenancy boring, and the code readable.

Providers (lawyers, consultants, coaches, agencies) sign up on a free tier, work cases through a Filament admin, and invite their clients into a Livewire portal that lives at `/portal`. Paid tiers upgrade via Stripe Checkout, and the free-tier case limit is enforced at the model layer, not just the UI. Every tenant sees only their own data via a global Eloquent scope keyed on `provider_id`. Admin users bypass the scope to support any customer. There is no tenancy plugin, no subdomain routing, and no per-tenant database — just one Laravel application and one PostgreSQL schema, designed so the tenancy is invisible to both the code and the URL structure.

On the feature side I deliberately built a wide surface area because the goal was "something that looks and behaves like a real product, not a tutorial." That means:

- **Case management** with stages (Intake → Active → Review → Closed), statuses, priorities, tags, milestones, tasks, timestamped case notes, polymorphic document attachments, and a per-case activity log that auto-captures field-level changes
- **Productivity views** beyond the table: a kanban board grouping cases by stage, and a monthly calendar view grouped by due date
- **Time tracking and billing**: log billable hours with hourly-rate snapshots, track expenses, generate auto-numbered invoices (`INV-YYYY-NNNN`) with line items, and download branded PDFs via DomPDF
- **Subscription billing**: Stripe Cashier with three tiers (Free, Pro, Enterprise), Stripe Checkout for upgrades, the customer billing portal, and plan enforcement in the `CaseRecord` create hook
- **Client portal**: a separate Livewire surface with invitation-based onboarding (signed tokens), case viewing, document uploads, and chat-style messaging with the provider
- **REST API**: Laravel Sanctum bearer tokens, `/api/cases` and `/api/clients` CRUD endpoints with JSON Resources, and a self-service token management page in the admin
- **Outgoing webhooks**: configurable endpoints per event, HMAC-SHA256 signed payloads (Stripe-style), fired from model observers
- **Reports dashboard**: revenue-by-month line chart, cases-by-stage doughnut, time-logged-this-week stats, paid/outstanding/draft invoice totals
- **Polish**: Filament database notifications with 30s polling, Cmd+K global search across cases and clients, dark mode, CSV export, and email notifications on new messages, task assignments, and paid invoices

The repo is seeded with realistic demo data (5 users, 36 clients, 89 cases, 337 tasks, 439 time entries, 54 invoices, 426 activity log entries) and ships with a Playwright-driven screenshot pipeline that captures 18 reference shots across every feature area.

---

## Key results

- **10 build phases** delivered, tagged in git, with one focused commit per phase
- **~14 models** with polymorphic and many-to-many relationships and proper indexing
- **18 reproducible screenshots** driven by headless Chrome against a freshly seeded database
- **One-command bring-up**: `docker-compose up -d && docker exec caseflow-app php artisan migrate --seed`
- **Public on GitHub** so prospective clients can read the code before hiring

---

## Skills (20 max — Upwork lets you pick tags from their taxonomy)

Laravel, PHP, PostgreSQL, Filament, Livewire, Stripe, REST API, SaaS Development, Multi-tenancy, Docker, Laravel Cashier, Sanctum, Tailwind CSS, Webhook Integration, PDF Generation, Eloquent ORM, Authentication & Authorization, Subscription Billing, Full-Stack Development, DevOps

---

## Tools and technologies used

- **Backend:** Laravel 12, PHP 8.2
- **Admin panel:** Filament 3
- **Frontend:** Livewire 3, Tailwind CSS, Blade
- **Database:** PostgreSQL 16
- **Payments:** Laravel Cashier (Stripe)
- **API auth:** Laravel Sanctum (bearer tokens)
- **PDF:** barryvdh/laravel-dompdf
- **Container:** Docker + Docker Compose
- **CI-ready:** GitHub Actions structure
- **Screenshots:** Playwright (headless Chrome) + pdftoppm

---

## What the client gets with this pattern

When I build a SaaS for a client, this project is the reference for how the pieces fit together:

- How to do multi-tenancy **without** a plugin or a second database
- How to wire Stripe Cashier so billing isn't bolted on at the end
- How to expose a REST API on the same models the admin panel uses, with the same tenancy rules
- How to ship a client-facing portal that feels separate from the admin without running a second framework
- How to generate branded PDF documents from Blade templates
- How to put a webhook system behind model observers so events fire reliably

---

## Images to upload to this Upwork portfolio item

Upwork allows multiple images per portfolio item and the first becomes the thumbnail.

1. `docs/banner.png` — banner, 1280×720
2. `docs/screenshots/03-admin-dashboard.png` — admin dashboard
3. `docs/screenshots/05-case-detail.png` — case detail with relation managers
4. `docs/screenshots/06-kanban-board.png` — kanban
5. `docs/screenshots/10-invoice-detail.png` — invoice builder
6. `docs/screenshots/11-invoice-pdf.png` — generated PDF
7. `docs/screenshots/12-reports-dashboard.png` — reports with charts
8. `docs/screenshots/13-api-tokens.png` — API token management
9. `docs/screenshots/14-webhooks.png` — webhooks configuration
10. `docs/screenshots/18-client-portal.png` — client portal
