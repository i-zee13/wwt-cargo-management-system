# WWT — Marketing Website

New public website for World Wide Trading Group (WWT), built as a standalone
Next.js app separate from the Laravel admin/client-portal system in the
repository root. Laravel remains the backend for the client portal, package
tracking data, and auth.

## Stack

- Next.js (App Router) + TypeScript
- Tailwind CSS v4
- Framer Motion
- next-intl (Spanish default at `/`, English at `/en`)
- next-sitemap (sitemap.xml + robots.txt generated on build)

## Getting started

```bash
npm install
cp .env.local.example .env.local
npm run dev
```

Open http://localhost:3000.

## Environment variables

See `.env.local.example`:

- `NEXT_PUBLIC_SITE_URL` — canonical URL of this site, used for metadata and the sitemap.
- `NEXT_PUBLIC_LARAVEL_URL` — base URL of the Laravel app. Used for:
  - `Ingresar` / `Registrate` CTAs, which link to `/customer-login` and `/customer-register` on Laravel.
  - The package tracking page (`/rastreo`), which calls `GET {LARAVEL_URL}/api/track/{waybill}`.

## Laravel integration

This site does not duplicate auth or package data. It links out to the
existing Laravel client portal for login/registration, and calls a small
public read-only endpoint (`routes/api.php` → `PackageController@trackApi`)
for tracking lookups. No existing Blade pages were removed.

## Content

Spanish (`src/messages/es.json`) is the source of truth; English
(`src/messages/en.json`) mirrors the same keys. Visuals are built with CSS
gradients, SVG/icon compositions (`lucide-react`) and Framer Motion — no
third-party photography is bundled. Replace/extend with licensed photography
in `public/images` as needed; update `next.config.ts` `images.remotePatterns`
if loading images from a remote host.
