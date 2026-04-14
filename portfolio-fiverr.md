# CaseFlow — Fiverr Portfolio Entry

> Paste this into a Fiverr portfolio item on the "Custom SaaS Product" gig.

---

## Title (60 char max)

**Multi-tenant case management SaaS with Stripe billing** *(56 chars)*

Alternatives if the above is taken:
- **CaseFlow — Case management SaaS with Stripe & REST API** *(52 chars)*
- **Full-featured SaaS: cases, invoicing, portal, Stripe** *(54 chars)*

---

## Short description (for the gallery card, ~150 chars)

A full-featured case management SaaS for solo practices and small firms. Cases, time tracking, Stripe billing, client portal, REST API.

---

## Long description (for the portfolio detail page)

CaseFlow is a production-grade SaaS platform I built end-to-end to show what a polished multi-tenant product looks like when every piece is wired up. Providers (lawyers, consultants, coaches) sign up on a free tier, work cases through a Filament admin panel, and invite their clients into a dedicated Livewire portal. Paid plans flow through Stripe Checkout with Cashier and enforce limits at the model layer, not just the UI.

Under the hood it's one Laravel 12 application with a single Postgres schema. Tenancy is done the quiet way: a `provider_id` column on every tenant-scoped model plus a global `ProviderScope` that auto-filters queries for the authenticated provider. No tenancy plugin, no subdomain routing, no multi-database hell. Admin users bypass the scope. The result is `/admin/cases` that works the same for every provider but shows completely isolated data.

The feature surface is deliberately wide because the pitch is "this could ship to real customers tomorrow": case pipelines with stages and priorities, tasks and milestones, timestamped case notes, polymorphic document uploads, a kanban board, a calendar view, time tracking with billable-hour rate snapshots, auto-numbered PDF invoices (DomPDF), a REST API on Sanctum bearer tokens, outgoing webhooks with HMAC-SHA256 signatures (Stripe-style), a reports dashboard with line and doughnut charts, Filament database notifications, Cmd+K global search, and dark mode. All of it lives behind one cohesive admin panel.

Everything is seeded with realistic demo data (5 users, 36 clients, 89 cases, 300+ tasks, 400+ time entries, 50+ invoices) and ships with a reproducible screenshot pipeline that drives headless Chrome through the full product. The repo is public on GitHub so prospective clients can read the code.

**Tech:** Laravel 12, Filament 3, Livewire 3, PostgreSQL 16, Laravel Cashier (Stripe), Laravel Sanctum, DomPDF, Docker.

**Live demo + source:** [github.com/atifali-pm/caseflow](https://github.com/atifali-pm/caseflow)

---

## Tags (15 max, comma-separated for Fiverr)

saas, laravel, filament, stripe, multi tenant, rest api, client portal, postgresql, docker, livewire, subscription billing, time tracking, invoicing, webhooks, dashboard

---

## Gig categories this portfolio fits

1. **Custom SaaS Product** (primary) — the whole piece
2. **Full-Stack Web Application** — it's also a complete Laravel + Livewire app
3. **REST API Development** — Sanctum-backed REST API with webhooks

---

## Images to upload to the portfolio item

Upload in this order; Fiverr shows the first as the thumbnail.

1. `docs/banner.png` — branded banner, 1280×720
2. `docs/screenshots/01-landing.png` — public landing page
3. `docs/screenshots/03-admin-dashboard.png` — Filament dashboard with widgets
4. `docs/screenshots/06-kanban-board.png` — kanban pipeline view
5. `docs/screenshots/10-invoice-detail.png` — invoice builder
6. `docs/screenshots/11-invoice-pdf.png` — generated PDF
7. `docs/screenshots/12-reports-dashboard.png` — charts dashboard
8. `docs/screenshots/17-dark-mode.png` — dark mode
9. `docs/screenshots/18-client-portal.png` — client-facing portal
10. `docs/screenshots/02-pricing.png` — Stripe-backed pricing

---

## Optional video caption (if you upload `docs/demo.mp4`)

45-second walkthrough: landing → provider dashboard → case detail → kanban → invoice PDF → dark mode.
