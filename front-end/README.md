# WWT — Public Marketing Website (`front-end`)

Modern public website for [World Wide Trading Group (WWT)](https://wwt.com.py/),
built as a standalone Next.js app in `/front-end`. The Laravel app in the
repository root remains the backend for admin, client portal, auth, payments,
and package data.

## Brand colors

Matched to the live WWT site:

| Token | Hex | Usage |
|-------|-----|--------|
| Brand navy | `#040725` | Headers, nav, dark sections, icons |
| Accent yellow | `#dfaa09` | Primary buttons, highlights, stats |
| White | `#ffffff` | Page background |

## Stack

- Next.js 16 (App Router) + TypeScript
- Tailwind CSS v4
- Framer Motion
- next-intl — Spanish default at `/`, English at `/en`
- next-sitemap

## Getting started

```bash
cd front-end
npm install
cp .env.example .env.local
npm run dev
```

Open http://localhost:3000.

## Environment variables

See `.env.example`:

- `NEXT_PUBLIC_SITE_URL` — canonical URL for metadata and sitemap
- `NEXT_PUBLIC_LARAVEL_URL` — Laravel base URL for portal links and tracking API

## Deploy on Netlify

**Live site:** https://wwt-cargo-marketing.netlify.app

The repo includes [`netlify.toml`](../netlify.toml) at the project root. Netlify deploys **only** the static export from `front-end/out`. The Laravel app is **not** deployed to Netlify.

### What goes where

| Component | Host | Folder |
|-----------|------|--------|
| Marketing website (Next.js) | **Netlify** | `/front-end` |
| Admin panel, client portal, API, MySQL | **Your PHP server** | Laravel repo root |

### Netlify site settings

| Setting | Value |
|---------|--------|
| Base directory | `front-end` |
| Build command | `npm run build` |
| Publish directory | `front-end/out` |
| Node version | `20` |

### Environment variables (Netlify UI — required at build time)

| Variable | Current production value |
|----------|--------------------------|
| `NEXT_PUBLIC_SITE_URL` | `https://wwt-cargo-marketing.netlify.app` (change to `https://wwt.com.py` when DNS is ready) |
| `NEXT_PUBLIC_LARAVEL_URL` | `https://wwcsys.worldwidecommerce.us` |

Redeploy after changing env vars so they are baked into the client bundle.

### Connect GitHub for auto-deploy

In Netlify → **wwt-cargo-marketing** → **Build & deploy** → **Link repository** → select `i-zee13/wwt-cargo-management-system`, branch `main`. Netlify reads `netlify.toml` automatically.

### Custom domain

Point `wwt.com.py` DNS to Netlify, then update `NEXT_PUBLIC_SITE_URL` and redeploy.

### Laravel backend (not on Netlify)

Keep Laravel on your PHP/MySQL server. The marketing site calls:

- `GET {LARAVEL_URL}/api/track/{waybill}` — package tracking
- `{LARAVEL_URL}/customer-login` / `customer-register` — client portal

CORS is already open for `api/*` in Laravel `config/cors.php`.

## Pages

| Route | Description |
|-------|-------------|
| `/` | Home |
| `/servicios` | Services |
| `/rastreo` | Package tracking |
| `/tarifas` | Rates |
| `/sobre-nosotros` | About us |
| `/preguntas-frecuentes` | FAQ |
| `/contacto` | Contact |

English mirrors the same paths under `/en/...`.

## Laravel integration

- **Login / Register** → `{LARAVEL_URL}/customer-login` and `/customer-register`
- **Tracking** → `GET {LARAVEL_URL}/api/track/{waybill}` (read-only, no PII)

No Laravel routes, database, or admin panel code is modified by this folder.

## Content

Spanish (`src/messages/es.json`) is the source of truth; English
(`src/messages/en.json`) mirrors the same keys.

## Build for production

```bash
npm run build
npm start
```

Generates static pages and `sitemap.xml` via `next-sitemap`.

## Note on `/frontend` vs `/front-end`

An earlier copy exists at `/frontend`. This folder (`/front-end`) is the
WWT-branded version with orange/blue palette. Use one folder in production.
