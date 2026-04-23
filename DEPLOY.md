# Deploying CaseFlow for free

This guide deploys CaseFlow to a public URL using two always-free services: **Render** for the web app and **Neon** for PostgreSQL. No credit card required for either.

Expected wall time: **15-20 minutes**.

---

## Prerequisites

- A GitHub account with the CaseFlow repo (fork [atifali-pm/caseflow](https://github.com/atifali-pm/caseflow) or use your own).
- Docker running locally (only needed once, to generate `APP_KEY`).

---

## 1. Create a Neon database

1. Sign up at **[neon.tech](https://neon.tech)** (GitHub login works).
2. Create a new project — name it `caseflow`, region closest to your Render region (Frankfurt if you keep the default).
3. On the project dashboard, copy the **connection string** under "Connection Details". It looks like:
   ```
   postgresql://user:pass@ep-xxx.region.aws.neon.tech/neondb?sslmode=require
   ```
4. Keep this tab open, you'll paste the URL in step 3.

Neon free tier: 3 GB storage, 1 project, no time limit, no card.

---

## 2. Generate an APP_KEY

Laravel needs a 32-byte app key. Run this locally:

```bash
docker-compose up -d
docker exec caseflow-app php artisan key:generate --show
```

You'll get something like `base64:abc123...`. Copy it.

If you don't have the stack running locally, any quick PHP install can do:
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

---

## 3. Create the Render service

1. Sign up at **[render.com](https://render.com)**.
2. Top right **New** → **Blueprint**.
3. Connect your GitHub, select the **caseflow** repo.
4. Render reads `render.yaml` and prepares one web service.
5. Before clicking Apply, fill the three `sync: false` env vars:

   | Key       | Value                                                     |
   |-----------|-----------------------------------------------------------|
   | `APP_KEY` | The `base64:...` string from step 2                        |
   | `APP_URL` | `https://caseflow.onrender.com` (Render will confirm the final hostname) |
   | `DB_URL`  | The Neon connection string from step 1                     |

6. Leave the `STRIPE_*` keys blank unless you're wiring up real billing.
7. Click **Apply**.

Render builds the Docker image (~5-8 min first time), runs `docker/start.sh`, which migrates and seeds the database, then starts `php artisan serve`.

---

## 4. Fix APP_URL after first deploy

If Render assigned a hostname different from `caseflow.onrender.com` (e.g. `caseflow-abcd.onrender.com`):

1. Open the service → **Environment** tab.
2. Edit `APP_URL` to match the assigned hostname (including `https://`).
3. Save → Render redeploys automatically.

---

## 5. Smoke test

After deploy finishes:

- `https://<your-service>.onrender.com/` → landing page
- `/admin/login` → Filament sign-in
- `/portal/login` → client portal sign-in
- `/pricing` → three-tier pricing

Log in with a seeded account (all use password `password`):

| Email                   | Role     |
|-------------------------|----------|
| `admin@caseflow.test`   | Admin    |
| `sarah@caseflow.test`   | Provider |
| `client@caseflow.test`  | Client (portal only) |

---

## What you get on the free tier

- Always-free PostgreSQL (Neon, 3 GB)
- Always-free web hosting (Render, 512 MB RAM, 0.1 vCPU)
- Automatic HTTPS
- Auto-deploy on every push to `main`

## Free-tier gotchas

- **Cold starts**: Render's free web services spin down after 15 minutes of no traffic. The first request after an idle period takes **~40-60 seconds** while the container wakes up. Warm responses are fast.
- **Ephemeral disk**: uploaded documents are lost on redeploy or restart. Seeded demo data reappears automatically (the entrypoint re-seeds if the users table is empty).
- **Email**: `MAIL_MAILER=log` by default — check the Render logs tab to see queued emails. To actually send, set `MAIL_MAILER=smtp` and the SMTP vars (Resend free tier, Postmark trial, etc.).
- **Stripe**: leave keys blank and the pricing page still works; Checkout is gated on having a configured Stripe customer.

## Updating

```bash
git push origin main
```
Render auto-deploys. Migrations run on boot. Seeds do **not** re-run (users table is non-empty after first deploy).

## Troubleshooting

- **Build fails on `composer install`**: Render's free tier sometimes OOMs during `package:discover`. Retry once in the dashboard.
- **"Please provide a valid cache path"**: you hit an older Dockerfile. Pull latest; the current Dockerfile pre-creates `storage/framework/views` etc.
- **Filament redirects to `http://`**: `trustProxies(at: '*')` is set in `bootstrap/app.php` — make sure you're on the latest commit.
- **`sslmode` errors from Neon**: the connection string must include `?sslmode=require`. Copy the "Pooled connection" variant from Neon.
