#!/usr/bin/env node
/**
 * Renders docs/banner.png at 1280x720 using Playwright + a temp HTML file.
 *
 * Usage:
 *   node scripts/banner.mjs
 */

import { chromium } from 'playwright-core';
import { writeFile, mkdir } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const OUT_PATH = resolve(
  dirname(fileURLToPath(import.meta.url)),
  '..',
  'docs',
  'banner.png',
);
const CHROME = process.env.CHROME || '/usr/bin/google-chrome';

const HTML = `<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
  html, body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
  body {
    width: 1280px; height: 720px;
    background:
      radial-gradient(ellipse 70% 50% at 20% 10%, rgba(245, 158, 11, 0.22), transparent 60%),
      radial-gradient(ellipse 60% 50% at 90% 100%, rgba(245, 158, 11, 0.12), transparent 60%),
      linear-gradient(180deg, #0f172a 0%, #111827 100%);
    color: #fff;
    display: grid;
    grid-template-columns: 1fr 540px;
    align-items: center;
    padding: 0 72px;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
  }
  /* subtle grid lines */
  body::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 40px 40px;
    mask-image: linear-gradient(180deg, transparent, black 20%, black 80%, transparent);
  }
  .left { position: relative; z-index: 1; }
  .logo { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; }
  .logo-mark {
    width: 44px; height: 44px; border-radius: 10px;
    background: #f59e0b;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
  }
  .logo-mark svg { width: 26px; height: 26px; stroke: white; stroke-width: 2.2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
  .brand { font-size: 28px; font-weight: 800; letter-spacing: -0.5px; }
  h1 {
    font-size: 68px; line-height: 1.02; font-weight: 800; margin: 0 0 24px 0;
    letter-spacing: -2px;
  }
  h1 .accent { color: #fbbf24; }
  p.tag {
    font-size: 22px; line-height: 1.45; color: #cbd5e1; margin: 0 0 40px 0;
    max-width: 620px; font-weight: 400;
  }
  .chips { display: flex; flex-wrap: wrap; gap: 10px; max-width: 620px; }
  .chip {
    padding: 8px 14px; border-radius: 999px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    font-size: 14px; font-weight: 600; color: #e2e8f0;
  }
  .right { position: relative; z-index: 1; display: flex; justify-content: flex-end; }
  /* Mock Filament admin panel preview */
  .card {
    width: 500px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.10);
    border-radius: 18px;
    padding: 24px;
    backdrop-filter: blur(8px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.45);
  }
  .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .card-head .title { font-size: 18px; font-weight: 700; color: #fff; }
  .card-head .menu { display: flex; gap: 6px; }
  .dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.25); }
  .stats { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 20px; }
  .stat { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 14px; border: 1px solid rgba(255,255,255,0.08); }
  .stat .v { font-size: 28px; font-weight: 800; color: #fff; }
  .stat .l { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.4px; }
  .stat.green .v { color: #34d399; }
  .stat.amber .v { color: #fbbf24; }
  .stat.red .v { color: #f87171; }
  .row { display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(255,255,255,0.03); border-radius: 10px; margin-bottom: 8px; border: 1px solid rgba(255,255,255,0.05); }
  .row .name { font-size: 14px; color: #e2e8f0; font-weight: 600; }
  .row .badge { font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 4px 10px; border-radius: 999px; }
  .b-green { background: rgba(52,211,153,0.15); color: #34d399; border: 1px solid rgba(52,211,153,0.3); }
  .b-amber { background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.3); }
  .b-blue { background: rgba(96,165,250,0.15); color: #60a5fa; border: 1px solid rgba(96,165,250,0.3); }
</style>
</head>
<body>
  <div class="left">
    <div class="logo">
      <div class="logo-mark">
        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
      </div>
      <div class="brand">CaseFlow</div>
    </div>
    <h1>Run your practice<br>from <span class="accent">one panel.</span></h1>
    <p class="tag">Multi-tenant case management SaaS with Stripe billing, client portal, and REST API. Built with Laravel 12, Filament 3, and Livewire.</p>
    <div class="chips">
      <div class="chip">Laravel 12</div>
      <div class="chip">Filament 3</div>
      <div class="chip">Stripe Cashier</div>
      <div class="chip">Livewire</div>
      <div class="chip">REST API</div>
      <div class="chip">Webhooks</div>
      <div class="chip">PostgreSQL</div>
      <div class="chip">Docker</div>
    </div>
  </div>
  <div class="right">
    <div class="card">
      <div class="card-head">
        <div class="title">Provider dashboard</div>
        <div class="menu"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>
      </div>
      <div class="stats">
        <div class="stat green"><div class="v">22</div><div class="l">Open cases</div></div>
        <div class="stat amber"><div class="v">9</div><div class="l">In review</div></div>
        <div class="stat red"><div class="v">3</div><div class="l">Overdue</div></div>
      </div>
      <div class="row">
        <div class="name">Asset Division — Corwin</div>
        <div class="badge b-green">Open</div>
      </div>
      <div class="row">
        <div class="name">Property Settlement — Johns</div>
        <div class="badge b-blue">Review</div>
      </div>
      <div class="row">
        <div class="name">Mediation Prep — Baumbach</div>
        <div class="badge b-amber">Active</div>
      </div>
    </div>
  </div>
</body>
</html>`;

async function main() {
  await mkdir(dirname(OUT_PATH), { recursive: true });
  const htmlPath = resolve('/tmp', `caseflow-banner-${Date.now()}.html`);
  await writeFile(htmlPath, HTML);

  const browser = await chromium.launch({ executablePath: CHROME, headless: true });
  const context = await browser.newContext({
    viewport: { width: 1280, height: 720 },
    deviceScaleFactor: 2,
  });
  const page = await context.newPage();
  await page.goto(`file://${htmlPath}`, { waitUntil: 'networkidle' });
  // extra beat for the Google Font to paint
  await page.waitForTimeout(600);
  await page.screenshot({ path: OUT_PATH, omitBackground: false });
  await browser.close();
  console.log(`Banner written to ${OUT_PATH}`);
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
