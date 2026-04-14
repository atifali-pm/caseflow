#!/usr/bin/env node
/**
 * Generates README screenshots by driving headless Chrome through real
 * CaseFlow flows. Logs in as a seeded provider and a seeded client,
 * navigates to each documented page, and snaps the viewport.
 *
 * Usage:
 *   node scripts/screenshots.mjs
 *
 * Requires:
 *   - Docker stack running: docker-compose up -d
 *   - Fresh seed:           docker exec caseflow-app php artisan migrate:fresh --seed
 *   - Playwright installed: npm install -D playwright-core
 *   - Chrome at /usr/bin/google-chrome (override with CHROME env var)
 */

import { chromium } from 'playwright-core';
import { mkdir } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

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

async function loginFilament(page, { email, password }) {
  await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' });
  await page.fill('input[type="email"]', email);
  await page.fill('input[type="password"]', password);
  await Promise.all([
    page.waitForURL(/\/admin($|\/)/, { waitUntil: 'networkidle' }),
    page.click('button[type="submit"]'),
  ]);
}

async function loginPortal(page, { email, password }) {
  await page.goto(`${BASE}/portal/login`, { waitUntil: 'networkidle' });
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await Promise.all([
    page.waitForURL(/\/portal\/?$/, { waitUntil: 'networkidle' }),
    page.click('button[type="submit"]'),
  ]);
}

async function clearSession(context) {
  await context.clearCookies();
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

  console.log('Capturing public pages...');
  await page.goto(`${BASE}/`, { waitUntil: 'networkidle' });
  await snap(page, '01-landing.png');

  await page.goto(`${BASE}/pricing`, { waitUntil: 'networkidle' });
  await snap(page, '02-pricing.png');

  console.log('Capturing provider admin (logging in as Sarah)...');
  await loginFilament(page, PROVIDER);
  await page.waitForLoadState('networkidle');
  await snap(page, '03-admin-dashboard.png');

  await page.goto(`${BASE}/admin/cases`, { waitUntil: 'networkidle' });
  await snap(page, '04-cases-list.png');

  // Click into the first case row
  const firstCaseLink = page
    .locator('table tbody tr')
    .first()
    .locator('a')
    .first();
  await firstCaseLink.click();
  await page.waitForLoadState('networkidle');
  await snap(page, '05-case-detail.png');

  await page.goto(`${BASE}/admin/clients`, { waitUntil: 'networkidle' });
  await snap(page, '06-clients-list.png');

  console.log('Capturing client portal (logging out, logging in as client)...');
  await clearSession(context);
  await loginPortal(page, CLIENT);
  await page.waitForLoadState('networkidle');
  await snap(page, '07-portal-dashboard.png');

  await browser.close();
  console.log(`\nDone. Screenshots written to ${OUT_DIR}`);
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
