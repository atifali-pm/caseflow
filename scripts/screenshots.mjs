#!/usr/bin/env node
/**
 * Generates README and portfolio screenshots by driving headless Chrome
 * through real CaseFlow flows. Captures all 10 feature phases.
 *
 * Usage:
 *   node scripts/screenshots.mjs
 *
 * Requires:
 *   - Docker stack running: docker-compose up -d
 *   - Fresh seed:           docker exec caseflow-app php artisan migrate:fresh --seed
 *   - Node 18+:             /home/atif/.nvm/versions/node/v20.11.0/bin/node
 *   - Playwright installed: npm install --no-save playwright-core
 *   - Chrome at /usr/bin/google-chrome (override with CHROME env var)
 */

import { chromium } from 'playwright-core';
import { mkdir, writeFile, rename, unlink } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { execSync } from 'node:child_process';

const BASE = 'http://caseflow.local:8010';
const OUT_DIR = resolve(
  dirname(fileURLToPath(import.meta.url)),
  '..',
  'docs',
  'screenshots',
);
const VIEWPORT = { width: 1440, height: 900 };
const CHROME = process.env.CHROME || '/usr/bin/google-chrome';

const PROVIDER = { email: 'sarah@caseflow.test', password: 'password' };
const CLIENT = { email: 'client@caseflow.test', password: 'password' };

// ---------- helpers ----------

async function snap(page, name) {
  const path = resolve(OUT_DIR, name);
  await page.screenshot({ path, fullPage: false });
  console.log(`  -> ${name}`);
}

async function snapFull(page, name) {
  const path = resolve(OUT_DIR, name);
  await page.screenshot({ path, fullPage: true });
  console.log(`  -> ${name} (full)`);
}

async function loginFilament(page, { email, password }) {
  await page.goto(`${BASE}/admin/login`, { waitUntil: 'domcontentloaded' });
  await page.fill('input[type="email"]', email);
  await page.fill('input[type="password"]', password);
  await Promise.all([
    page.waitForURL(/\/admin($|\/)/, { waitUntil: 'domcontentloaded' }),
    page.click('button[type="submit"]'),
  ]);
  // Give widgets time to render, but don't wait for networkidle (notifications poll forever)
  await page.waitForTimeout(1500);
}

async function gotoAdmin(page, path) {
  // Retry once on transient network errors (Livewire polling can briefly stall things).
  try {
    await page.goto(`${BASE}${path}`, { waitUntil: 'domcontentloaded', timeout: 60000 });
  } catch (err) {
    console.log(`  ~~ retrying ${path} after: ${err.message.split('\n')[0]}`);
    await page.waitForTimeout(1000);
    await page.goto(`${BASE}${path}`, { waitUntil: 'domcontentloaded', timeout: 60000 });
  }
  await page.waitForTimeout(800);
}

async function loginPortal(page, { email, password }) {
  await page.goto(`${BASE}/portal/login`, { waitUntil: 'domcontentloaded' });
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await Promise.all([
    page.waitForURL(/\/portal\/?$/, { waitUntil: 'domcontentloaded' }),
    page.click('button[type="submit"]'),
  ]);
}

async function clearSession(context) {
  await context.clearCookies();
}

async function firstCaseId(page) {
  await gotoAdmin(page, '/admin/cases');
  const href = await page
    .locator('table tbody tr a[href*="/admin/cases/"]')
    .first()
    .getAttribute('href');
  const m = href && href.match(/\/admin\/cases\/(\d+)/);
  return m ? m[1] : null;
}

async function firstInvoiceId(page) {
  await gotoAdmin(page, '/admin/invoices');
  const href = await page
    .locator('table tbody tr a[href*="/admin/invoices/"]')
    .first()
    .getAttribute('href');
  const m = href && href.match(/\/admin\/invoices\/(\d+)/);
  return m ? m[1] : null;
}

// ---------- main ----------

async function main() {
  await mkdir(OUT_DIR, { recursive: true });

  const browser = await chromium.launch({
    executablePath: CHROME,
    headless: true,
  });
  const context = await browser.newContext({ viewport: VIEWPORT });
  const page = await context.newPage();

  console.log('== Public pages ==');
  await page.goto(`${BASE}/`, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(600);
  await snap(page, '01-landing.png');

  await page.goto(`${BASE}/pricing`, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(400);
  await snap(page, '02-pricing.png');

  console.log('== Provider admin (logging in as Sarah) ==');
  await loginFilament(page, PROVIDER);
  await snap(page, '03-admin-dashboard.png');

  await gotoAdmin(page, '/admin/cases');
  await snap(page, '04-cases-list.png');

  const caseId = await firstCaseId(page);
  if (caseId) {
    await gotoAdmin(page, `/admin/cases/${caseId}/edit`);
    await snapFull(page, '05-case-detail.png');
  }

  await gotoAdmin(page, '/admin/cases/kanban');
  await snap(page, '06-kanban-board.png');

  await gotoAdmin(page, '/admin/cases/calendar');
  await snap(page, '07-calendar-view.png');

  await gotoAdmin(page, '/admin/tasks');
  await snap(page, '08-tasks-list.png');

  await gotoAdmin(page, '/admin/time-entries');
  await snap(page, '09-time-entries.png');

  const invoiceId = await firstInvoiceId(page);
  if (invoiceId) {
    await gotoAdmin(page, `/admin/invoices/${invoiceId}/edit`);
    await snapFull(page, '10-invoice-detail.png');

    // Invoice PDF — download via the authenticated browser context, then pdftoppm to PNG.
    // Headless Chrome can't render PDFs inline, so we grab the bytes and convert.
    const cookies = await context.cookies();
    const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ');
    const pdfPath = resolve(OUT_DIR, '_invoice.pdf');
    execSync(
      `curl -s -o "${pdfPath}" -H "Cookie: ${cookieHeader}" "${BASE}/invoices/${invoiceId}/pdf"`,
      { stdio: 'inherit' },
    );
    // pdftoppm converts the first page to PNG at 120dpi
    execSync(
      `pdftoppm -png -r 120 -f 1 -l 1 "${pdfPath}" "${resolve(OUT_DIR, '_invoice')}"`,
      { stdio: 'inherit' },
    );
    // pdftoppm writes _invoice-1.png; rename to the target
    await rename(
      resolve(OUT_DIR, '_invoice-1.png'),
      resolve(OUT_DIR, '11-invoice-pdf.png'),
    );
    await unlink(pdfPath);
    console.log('  -> 11-invoice-pdf.png (from DomPDF via pdftoppm)');
  }

  await gotoAdmin(page, '/admin/reports');
  await page.waitForTimeout(1200); // give charts time to render
  await snap(page, '12-reports-dashboard.png');

  await gotoAdmin(page, '/admin/api-tokens');
  await snap(page, '13-api-tokens.png');

  await gotoAdmin(page, '/admin/webhooks/create');
  await snap(page, '14-webhooks.png');

  // Notifications bell — Filament uses title="Open notifications"
  await gotoAdmin(page, '/admin');
  try {
    const bell = page.locator('button[title="Open notifications"]').first();
    await bell.click({ timeout: 3000 });
    await page.waitForTimeout(600);
    await snap(page, '15-notifications.png');
    await page.keyboard.press('Escape');
  } catch (err) {
    console.log('  !! notifications bell not found, skipping');
  }

  // Global search — cmd+k / ctrl+k
  try {
    await gotoAdmin(page, '/admin');
    await page.keyboard.press('Control+k');
    await page.waitForTimeout(300);
    // "Consultation" appears in the seeded case titles ("Initial Consultation - X")
    await page.keyboard.type('Consultation');
    await page.waitForTimeout(700);
    await snap(page, '16-global-search.png');
    await page.keyboard.press('Escape');
  } catch (err) {
    console.log('  !! global search shortcut failed, skipping');
  }

  // Dark mode — open user menu, click dark theme
  try {
    await gotoAdmin(page, '/admin');
    await page.locator('button[aria-label="User menu"]').first().click({ timeout: 3000 });
    await page.waitForTimeout(400);
    await page.locator('button[aria-label="Enable dark theme"]').first().click({ timeout: 3000 });
    await page.waitForTimeout(500);
    // Click somewhere to close the menu so the screenshot is clean
    await page.mouse.click(200, 200);
    await page.waitForTimeout(400);
    await snap(page, '17-dark-mode.png');
    // Revert so the light-mode dashboard shot at the very end is clean
    await page.locator('button[aria-label="User menu"]').first().click({ timeout: 3000 }).catch(() => {});
    await page.waitForTimeout(300);
    await page.locator('button[aria-label="Enable light theme"]').first().click({ timeout: 3000 }).catch(() => {});
  } catch (err) {
    console.log('  !! dark mode toggle failed:', err.message);
  }

  console.log('== Client portal ==');
  await clearSession(context);
  await loginPortal(page, CLIENT);
  await page.waitForTimeout(800);

  const portalCaseLink = page.locator('a[href*="/portal/cases/"]').first();
  if ((await portalCaseLink.count()) > 0) {
    await portalCaseLink.click();
    await page.waitForTimeout(1200);
    await snapFull(page, '18-client-portal.png');
  } else {
    await snap(page, '18-client-portal.png');
  }

  await browser.close();
  console.log(`\nDone. Screenshots written to ${OUT_DIR}`);
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
