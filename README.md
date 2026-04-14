# CaseFlow

**Multi-tenant case management SaaS for service providers.** Run your practice from one admin panel. Invite clients into a separate portal. Bill on Stripe from day one.

Built for law firms, consultants, coaches, and anyone who manages cases for clients. No tenant URL prefixes, no plugin sprawl, no second framework just to render a client view.

---

## Key features

### Provider — run your practice from one panel

- **Filament admin** with dashboard widgets, recent activity, and case statistics
- **Cases** with stages (Intake → Active → Review → Closed), statuses, and priorities (Low → Urgent)
- **Clients** with full CRM details, notes, and linked cases
- **Tasks** assignable to team members with due dates and status tracking
- **Tags** with provider-scoped colors, attached to cases via polymorphic many-to-many
- **Case notes** as a timestamped journal separate from the case description
- **Activity log** automatically captures every create/update/delete with field-level changes
- **Kanban board** showing cases as draggable cards grouped by stage
- **Calendar view** for cases by due date, with month navigation
- **Milestones, documents, and messages** as relation managers on each case
- **Global search** (cmd+k) across cases and clients
- **In-app + email notifications** for new messages, task assignments, and paid invoices
- **Dark mode** built into the admin panel
- **Multi-tenant by default** — every provider sees only their own data, automatically
- **Plan-aware** — case creation is blocked when the provider hits their tier limit

### Time tracking and invoicing

- **Time entries** with billable hours, snapshotted hourly rates, and case linkage
- **Expenses** tracked per case with billable flag
- **Invoices** with auto-generated numbering (`INV-YYYY-NNNN`), line items, status tracking
- **Invoice statuses**: Draft → Sent → Paid (or Overdue)
- **PDF invoices** generated via DomPDF with a clean professional layout
- **Reports dashboard** with revenue chart, cases-by-stage doughnut, billable hours stats, and invoice totals

### REST API and webhooks

- **Sanctum-authenticated API** for cases and clients (full CRUD)
- **API token management** page in the admin for creating and revoking tokens
- **Outgoing webhooks** with HMAC-SHA256 signed payloads
- **Webhook events**: `case.created`, `case.updated`, `case.closed`, `invoice.paid`, `message.sent`, `task.completed`
- **CSV export** of cases as a bulk action

### Client — a portal that respects them

- **Separate Livewire portal** at `/portal`, not a second Filament panel
- **Invitation-based onboarding** — providers send a tokenized link, clients set their password
- **View case status, milestones, and documents** without ever touching the admin panel
- **Upload documents** to their cases
- **Chat-style messaging** with their provider, threaded per case
- **Invoices** synced from Stripe

### Billing — Stripe from day one

- **Three tiers:** Free (5 cases), Pro ($29/mo, 50 cases), Enterprise ($99/mo, unlimited)
- **Stripe Checkout** for upgrades
- **Customer billing portal** for invoices, payment methods, and cancellations
- **Plan enforcement** at the model layer, not just the UI
- **Laravel Cashier** drives the whole flow — webhooks, subscriptions, the works

### Multi-tenancy that disappears

- **Global scope on `provider_id`** auto-filters every query for the authenticated provider
- **No URL prefix** — `/admin/cases` works whether you're Sarah or Michael, you each see your own
- **Admins bypass the scope** to support any provider's account
- **One source of truth** in `ScopedByProvider` trait + `ProviderScope` class

---

## How it works

### Case lifecycle

```mermaid
stateDiagram-v2
    [*] --> Intake: Provider creates case
    Intake --> Active: Case work begins
    Active --> Review: Awaiting client sign-off
    Review --> Active: Changes requested
    Review --> Closed: Resolved
    Active --> Closed: Resolved early
    Closed --> [*]
```

### Provider onboarding and billing

```mermaid
sequenceDiagram
    autonumber
    participant P as Provider
    participant CF as CaseFlow
    participant S as Stripe

    P->>CF: Sign up (free tier, 5 cases)
    P->>CF: Create cases up to limit
    CF-->>P: Limit reached on case #6
    P->>CF: Click "Upgrade to Pro"
    CF->>S: Stripe Checkout session
    S-->>P: Hosted checkout
    P->>S: Pay $29/mo
    S->>CF: Webhook: subscription.created
    CF-->>P: Pro tier active, 50 cases
```

### Client invitation flow

```mermaid
sequenceDiagram
    autonumber
    participant P as Provider
    participant CF as CaseFlow
    participant E as Email
    participant C as Client

    P->>CF: Create Client record
    P->>CF: Click "Invite to Portal"
    CF->>CF: Generate signed token
    CF->>E: Send invitation email
    E->>C: Invitation link
    C->>CF: Open link, set password
    CF-->>C: Portal access granted
    C->>CF: View their cases
```

### Who sees what

```mermaid
flowchart LR
    Admin((Admin)) -->|sees all| Data[All providers' data]
    Provider((Provider)) -->|scoped by provider_id| Own[Own cases / clients / docs]
    Client((Client)) -->|portal only| Theirs[Their cases only]
    Provider -->|invites| Client
    Provider -->|billed by| Stripe[Stripe]
```

---

## Screenshots

### Public landing

![Landing](docs/screenshots/01-landing.png)

### Pricing — three tiers, Stripe-backed

Free, Pro, and Enterprise. Plan limits enforced at the model layer, not just hidden in the UI.

![Pricing](docs/screenshots/02-pricing.png)

### Provider dashboard

The Filament admin opens to a stat-heavy dashboard: open cases, in-review cases, overdue cases, total clients.

![Provider dashboard](docs/screenshots/03-admin-dashboard.png)

### Cases list

Every provider sees only their own cases. Sortable, searchable, filterable by stage, status, and priority. Tags visible inline.

![Cases list](docs/screenshots/04-cases-list.png)

### Case detail — milestones, tasks, documents, notes, messages, activity

One page, six relation managers. Track milestones, assign tasks, attach documents, write timestamped case notes, message the client, and watch an auto-captured activity log — all without leaving the case.

![Case detail](docs/screenshots/05-case-detail.png)

### Kanban board

Cases grouped by stage (Intake → Active → Review → Closed). A direct alternative to the table view when you want to see the pipeline at a glance.

![Kanban board](docs/screenshots/06-kanban-board.png)

### Calendar view

Monthly calendar of cases by due date. Jump straight to any case from the calendar cell.

![Calendar view](docs/screenshots/07-calendar-view.png)

### Tasks

Dedicated task list across all cases. Filter by status, overdue, and assignee. One-click mark complete.

![Tasks](docs/screenshots/08-tasks-list.png)

### Time tracking

Log billable hours with hourly rate snapshots. Roll entries into invoices in one click.

![Time entries](docs/screenshots/09-time-entries.png)

### Invoice detail

Auto-numbered invoices (`INV-YYYY-NNNN`) with line items, live qty × rate calculation, and Mark Sent / Mark Paid / Download PDF actions.

![Invoice detail](docs/screenshots/10-invoice-detail.png)

### Invoice PDF

Generated on demand with DomPDF. Branded header, parties block, line items, totals, notes.

![Invoice PDF](docs/screenshots/11-invoice-pdf.png)

### Reports dashboard

Revenue by month (line chart), cases by stage (doughnut), paid/outstanding/draft stats, time logged this week.

![Reports dashboard](docs/screenshots/12-reports-dashboard.png)

### API tokens

Self-service Sanctum token management. Copy-once token display with curl quickstart.

![API tokens](docs/screenshots/13-api-tokens.png)

### Webhooks

Outgoing webhooks with HMAC-SHA256 signatures (Stripe-style). Pick which events you want per endpoint.

![Webhooks](docs/screenshots/14-webhooks.png)

### Notifications

In-app database notifications with 30s polling. Triggered by new messages, task assignments, and invoice payments.

![Notifications](docs/screenshots/15-notifications.png)

### Global search

`Cmd+K` / `Ctrl+K` across cases and clients, with resource-specific result rows.

![Global search](docs/screenshots/16-global-search.png)

### Dark mode

Native Filament dark theme. One toggle in the user menu.

![Dark mode](docs/screenshots/17-dark-mode.png)

### Client portal

Clients log in to a separate Livewire portal. They see their own cases, upload documents, and chat with their provider. They never touch the admin panel.

![Client portal](docs/screenshots/18-client-portal.png)

> Screenshots are captured reproducibly by [`scripts/screenshots.mjs`](scripts/screenshots.mjs) — it drives headless Chrome through real provider and client flows against a freshly seeded database, and uses `pdftoppm` to render the DomPDF output.

---

## Roadmap

- [x] **Phase 1** — Multi-tenant Filament admin with cases, clients, milestones, documents, messages
- [x] **Phase 2** — Livewire client portal with invitation-based onboarding
- [x] **Phase 3** — Stripe Cashier billing with three tiers and plan enforcement
- [x] **Phase 4** — Reproducible screenshot pipeline
- [x] **Phase 5** — Productivity layer: tasks, tags, priority, case notes, kanban board, calendar view
- [x] **Phase 6** — Activity log per case with auto-captured field-level changes
- [x] **Phase 7** — Time tracking, expenses, invoices, and PDF invoice generation
- [x] **Phase 8** — In-app and email notifications, global search, dark mode
- [x] **Phase 9** — REST API (Sanctum), token management, reports dashboard with charts
- [x] **Phase 10** — Outgoing webhooks with HMAC-signed payloads for Zapier / Make
- [ ] **Phase 11** — File previews in the portal (PDF, image inline)
- [ ] **Phase 12** — Custom branding per provider (logo, colors)
- [ ] **Phase 13** — Public client intake forms with Stripe-paid consultations

---

## Architecture decisions

<details>
<summary>Click to expand</summary>

### Multi-tenancy via global scope, not subdomain or schema split

A `provider_id` column on every tenant-scoped model (Client, CaseRecord, Document, Message), plus a global `ProviderScope` that auto-filters queries to the authenticated provider. Admins bypass the scope.

This is invisible at the routing layer — `/admin/cases` works for any provider, they just see different data. No subdomain DNS to manage, no `ProviderMiddleware` to forget on a route, no per-tenant database to back up.

See [app/Models/Scopes/ProviderScope.php](app/Models/Scopes/ProviderScope.php) and [app/Models/Concerns/ScopedByProvider.php](app/Models/Concerns/ScopedByProvider.php).

### `CaseRecord` not `Case`

`Case` is a PHP reserved word (`switch / case`). The model is `CaseRecord` and the table is `case_records`. Filament displays it as "Case" in the UI via `$modelLabel = 'Case'`.

### Single User table with role enum

Admins, providers, and clients share one `users` table with a `role` column (`admin / provider / client`). A separate `Client` model holds CRM-style provider-specific data and links back to a `User` via nullable `user_id` (set when the client accepts their portal invite).

This keeps auth simple — one login form, one password reset flow — while letting the Client model carry data that has nothing to do with auth.

### Livewire portal, not a second Filament panel

The client portal is plain Livewire 3 with Tailwind. Filament's second-panel feature would have worked, but a portal for clients is fundamentally not the same product as an admin for providers — different navigation, different defaults, different polish bar. Livewire gives full control over UX without inheriting admin-panel assumptions.

### Plan enforcement at the model layer

`User::canCreateCase()` checks the provider's current case count against their plan limit. Filament's `beforeCreate()` hook calls it. The check would still fire from a script or a tinker session — it's not a UI affordance, it's a domain rule.

### Activity log without a package

`LogsActivity` is a custom trait (~50 lines) that hooks into Eloquent's `created`, `updated`, `deleted` events and writes to the `activity_logs` table. Polymorphic, so any model that uses the trait gets a per-record activity feed via `$model->activities`.

No Spatie dependency — the pattern is a few lines and the requirements are simple. The trait records the actor (`auth()->id()`), the event, and the dirty field changes for updates.

### Webhooks signed like Stripe

Outgoing webhooks send an `X-CaseFlow-Signature` header containing an HMAC-SHA256 of the body using each webhook's per-endpoint secret. Receivers verify by computing the same HMAC over the raw body — same pattern Stripe uses for incoming webhooks. The dispatcher catches and logs failures so a flaky downstream doesn't break the case-update flow.

### REST API as a thin layer over the same models

The API isn't a separate domain — it's the same Eloquent models, wrapped in `JsonResource` classes for output shaping, and gated by `auth:sanctum` plus the existing policies. The `ProviderScope` global scope still applies, so an API token issued by Sarah only sees Sarah's cases. No separate authorization logic to maintain.

</details>

---

## Running locally

<details>
<summary>Click to expand</summary>

### 1. Map `caseflow.local` to localhost

```bash
echo "127.0.0.1 caseflow.local" | sudo tee -a /etc/hosts
```

### 2. Start the Docker stack

```bash
git clone https://github.com/atifali-pm/caseflow.git
cd caseflow
cp .env.example .env
docker-compose up -d --build
```

### 3. Generate app key and seed demo data

```bash
docker exec caseflow-app php artisan key:generate
docker exec caseflow-app php artisan migrate:fresh --seed
```

### 4. Open the app

| URL | Who |
|---|---|
| http://caseflow.local:8010/ | Public landing |
| http://caseflow.local:8010/pricing | Pricing page |
| http://caseflow.local:8010/admin | Provider / admin Filament panel |
| http://caseflow.local:8010/portal/login | Client portal |

### Demo accounts (all password: `password`)

| Role | Email | Notes |
|---|---|---|
| Admin | `admin@caseflow.test` | Sees all data, bypasses provider scope |
| Provider | `sarah@caseflow.test` | 22 cases, 11 clients seeded |
| Provider | `michael@caseflow.test` | Empty, good for "blank slate" demos |
| Provider | `amy@caseflow.test` | Empty |

A demo client account is also linked to one of Sarah's seeded clients — check seeder output for the email.

### Stripe (optional, for live billing)

Add real test keys to `.env`:

```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
STRIPE_PRICE_PRO=price_...
STRIPE_PRICE_ENTERPRISE=price_...
```

The pricing page works without keys — checkout and the billing portal need them.

</details>

---

## License

MIT
