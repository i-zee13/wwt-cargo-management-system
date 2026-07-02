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

The repo includes [`netlify.toml`](../netlify.toml) at the project root. Connect the **full Git repository** to Netlify (do not upload only the build folder).

Netlify will automatically:

1. Use `front-end` as the base directory
2. Run `npm install` and `npm run build`
3. Deploy with the built-in Next.js 16 runtime (no extra plugin required)

### Netlify site settings

| Setting | Value |
|---------|--------|
| Base directory | `front-end` *(auto from `netlify.toml`)* |
| Build command | `npm run build` |
| Publish directory | *(leave empty — Netlify sets this for Next.js)* |
| Node version | `20` |

### Environment variables (Netlify UI)

Set these under **Site configuration → Environment variables**:

| Variable | Example |
|----------|---------|
| `NEXT_PUBLIC_SITE_URL` | `https://wwt.com.py` |
| `NEXT_PUBLIC_LARAVEL_URL` | `https://portal.wwt.com.py` |

Redeploy after changing env vars so they are baked into the client bundle.

### Custom domain

Point `wwt.com.py` (or `www`) to Netlify in DNS. Update `NEXT_PUBLIC_SITE_URL` to match the live domain.

### Laravel backend

Netlify hosts **only** the marketing site. Keep Laravel on your PHP server and ensure the tracking API (`GET /api/track/{waybill}`) allows requests from your Netlify domain (CORS if needed).

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
