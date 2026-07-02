# WWT Cargo Management System - Claude Instructions

## Project Overview

This is a Laravel-based cargo/logistics management system.

The system includes:

- Admin panel
- Client portal
- Package management
- Client management
- Origins and freight rates
- Employees and access rights
- Organization settings
- Reports
- Email templates
- Translation management
- Stripe payments
- Bilingual support: English and Spanish

The project is currently an older Laravel application and needs modernization before handing it to a new client.

## Current Known Stack

- Laravel 8
- PHP 7.3 / 8.0 style compatibility
- Blade templates
- Laravel Mix 6, mostly unused
- Static assets in public/js and public/css
- Server-side translations using resources/lang/en and resources/lang/es
- Client-side translations using old static public/messages.js
- Admin translation UI writes directly into PHP language files
- Passport and Sanctum are both present
- nwidart/laravel-modules exists in composer.json, but Modules folder may be missing
- Migrations are incomplete, so database schema may depend on external SQL dump

## Main Goal

Prepare this system for a new client with safer, cleaner, modern Laravel practices.

Main priorities:

1. Read and understand the existing code before changing anything.
2. Fix the translation system so JavaScript translations do not require server build or lang:js.
3. Modernize Laravel version control and deployment approach.
4. Add AI-assisted translation support only after the current translation flow is understood.
5. Clean old, duplicate, unused, and risky files before handoff.
6. Avoid breaking existing admin/client functionality.

## Very Important Rules

- Do not rewrite the full project unless explicitly asked.
- Do not remove existing functionality without explaining the impact.
- Do not change unrelated files.
- Do not expose or read secrets from .env files.
- Do not run destructive commands without approval.
- First inspect the relevant controllers, routes, middleware, Blade views, JS files, and lang files.
- Always explain the plan before editing.
- After editing, summarize changed files and reason for each change.

## Translation Modernization Direction

The current problem is that JavaScript translations depend on public/messages.js and a missing lang:js command.

Target approach:

- Keep Blade translations using __('fields.key').
- Remove dependency on Artisan::call('lang:js').
- Add runtime translation endpoint such as /js/translations.js or /api/translations/{locale}.
- Let JavaScript load translations dynamically from Laravel.
- Clear translation cache after admin saves translations.
- Do not require npm run build or server-side asset rebuild for translation changes.

## Laravel Upgrade Direction

Preferred long-term target:

- Laravel 10 or Laravel 11
- PHP 8.2+
- Replace deprecated packages
- Audit whether Passport or Sanctum is really needed
- Remove dead module references
- Add proper .env.example
- Add deployment notes

Do not start Laravel upgrade without creating a step-by-step upgrade plan first.

## Client Handoff Direction

Before handing over to the new client:

- Clean duplicate backup files.
- Remove unused old files.
- Add .env.example.
- Add setup instructions.
- Add deployment instructions.
- Lock risky routes like /clear.
- Make branding configurable where possible.
- Make translation management stable.

## Modern Website Development Goal

The project will also need a new modern public website for the cargo/shipping/logistics business.

Preferred frontend direction:

- Next.js
- React
- Tailwind CSS
- Framer Motion for animation
- Mobile-first responsive design
- SEO optimized pages
- Spanish as the primary language
- English as secondary language with header language toggle
- Clean AI-style typing effect in hero/content sections
- Beautiful cargo, shipment, packaging, warehouse, delivery, and tracking related visuals

The current Laravel system can remain as backend/admin/client portal/API.

Do not directly rewrite the old Laravel Blade admin system into React unless explicitly asked.

The modern website should be built as either:

1. A separate frontend folder such as `/frontend` using Next.js, or
2. A Laravel Vite React frontend only if this is clearly better for deployment.

Before coding the website, first propose:

- Recommended stack
- Folder structure
- Pages
- Sections
- Content strategy
- Translation strategy
- Animation strategy
- Image strategy
- SEO strategy
- Laravel integration approach

Primary website language should be Spanish.

Example Spanish tone:

- Professional
- Trustworthy
- Modern
- Simple for customers
- Cargo/logistics focused

Do not copy text or images from competitor websites. Use competitor research only for inspiration, structure, SEO topics, and service positioning.