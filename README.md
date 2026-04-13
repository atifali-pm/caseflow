# CaseFlow

A multi-tenant case management SaaS platform for service providers (law firms, consultants, coaches). Built with Laravel 12, Filament 3, Livewire, and Stripe.

Providers manage their cases, clients, milestones, and documents from an admin panel. Clients access a dedicated portal to view case status, upload documents, and message their provider.

## Features

### Provider Admin Panel (Filament)
- Dashboard with case statistics and recent activity widgets
- Full CRUD for cases, clients, milestones, and documents
- Case stages (Intake, Active, Review, Closed) and statuses (Open, On Hold, Closed)
- Polymorphic document attachments with file upload
- In-app messaging tied to cases
- Multi-tenant data isolation (each provider sees only their own data)
- Subscription management via Stripe (Free, Pro, Enterprise plans)

### Client Portal (Livewire)
- Separate portal at `/portal` for invited clients
- View cases, milestones, and documents
- Upload documents to cases
- Chat-style messaging with the provider
- Invitation-based registration flow

### Billing (Laravel Cashier + Stripe)
- Three-tier pricing: Free (5 cases), Pro ($29/mo, 50 cases), Enterprise ($99/mo, unlimited)
- Stripe Checkout integration
- Customer billing portal
- Plan enforcement on case creation

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2
- **Admin Panel:** Filament 3
- **Frontend:** Livewire 3, Tailwind CSS
- **Database:** PostgreSQL 16
- **Payments:** Laravel Cashier (Stripe)
- **Containerization:** Docker + Docker Compose

## Prerequisites

- Docker and Docker Compose
- A Stripe account (for billing features)

## Setup

1. Clone the repo
   ```bash
   git clone https://github.com/atifali-pm/caseflow.git
   cd caseflow
   ```

2. Copy environment file
   ```bash
   cp .env.example .env
   ```

3. Add `caseflow.local` to your hosts file
   ```bash
   echo '127.0.0.1 caseflow.local' | sudo tee -a /etc/hosts
   ```

4. Start Docker containers
   ```bash
   docker-compose up -d --build
   ```

5. Generate app key and run migrations with demo data
   ```bash
   docker exec caseflow-app php artisan key:generate
   docker exec caseflow-app php artisan migrate --seed
   ```

6. Visit the app at http://caseflow.local:8010

## Demo Accounts

After seeding, the following accounts are available (all with password `password`):

| Role     | Email                    | Purpose                          |
|----------|--------------------------|----------------------------------|
| Admin    | admin@caseflow.test      | System admin (sees all data)     |
| Provider | sarah@caseflow.test      | Provider with cases and clients  |
| Provider | michael@caseflow.test    | Another provider                 |
| Provider | amy@caseflow.test        | Another provider                 |

- **Admin panel:** http://caseflow.local:8010/admin
- **Client portal:** http://caseflow.local:8010/portal/login
- **Pricing:** http://caseflow.local:8010/pricing

A demo client account is also created and linked to an existing Client record. Check the seeder output for credentials.

## Stripe Setup

To enable billing features:

1. Create two products in the Stripe Dashboard (Pro $29/mo and Enterprise $99/mo)
2. Copy the price IDs to your `.env`:
   ```
   STRIPE_KEY=pk_test_...
   STRIPE_SECRET=sk_test_...
   STRIPE_WEBHOOK_SECRET=whsec_...
   STRIPE_PRICE_PRO=price_...
   STRIPE_PRICE_ENTERPRISE=price_...
   ```
3. Set up a webhook endpoint pointing to `https://your-domain/stripe/webhook`

## Architecture

### Multi-Tenancy
Tenancy is implemented via a `provider_id` column on tenant-scoped models (Client, CaseRecord, Document, Message) combined with a global `ProviderScope` that auto-filters queries to the authenticated provider. Admins bypass the scope. This approach avoids URL prefixing and keeps tenancy invisible at the routing layer.

See [app/Models/Scopes/ProviderScope.php](app/Models/Scopes/ProviderScope.php) and [app/Models/Concerns/ScopedByProvider.php](app/Models/Concerns/ScopedByProvider.php).

### Role System
A simple `role` enum column on users (admin, provider, client) with helper methods on the User model. No separate roles table. Filament's `canAccessPanel()` hook restricts the admin panel to providers and admins. Clients use the Livewire portal instead.

See [app/Enums/UserRole.php](app/Enums/UserRole.php) and [app/Models/User.php](app/Models/User.php).

### Case Model Naming
The model is named `CaseRecord` because `Case` is a PHP reserved word. Filament displays it as "Case" via the `$modelLabel` property. The table is `case_records`.

### Portal Access
Clients do not self-register. A provider creates a Client record in the admin panel, then uses the "Invite to Portal" action to generate an invitation token and send an email. The client follows the link to set their password and activate their portal account.

## Project Structure

```
app/
├── Enums/              # CaseStatus, CaseStage, Plan, UserRole
├── Filament/           # Admin panel resources, pages, widgets
├── Http/Controllers/   # Portal controllers, subscription controller
├── Livewire/Portal/    # Client portal Livewire components
├── Models/             # User, Client, CaseRecord, Document, Message, etc.
│   ├── Concerns/       # ScopedByProvider trait
│   └── Scopes/         # ProviderScope
├── Notifications/      # ClientPortalInvitation
└── Policies/           # Authorization policies

database/
├── factories/          # Model factories for seeding
├── migrations/         # Schema migrations
└── seeders/            # Demo data seeders

resources/views/
├── layouts/portal.blade.php
├── livewire/portal/    # Livewire component views
├── portal/             # Portal pages
├── pricing.blade.php
└── welcome.blade.php
```

## License

MIT
