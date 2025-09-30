# Allan Web Design – Portfolio Platform

A modern, API‑driven portfolio platform built as a monorepo. The admin app (Laravel 12 + Filament 4) serves as a headless CMS powering a Next.js public site, with clean REST endpoints and a streamlined content workflow.

## At a glance

- **Filament 4‑native** resources using Schemas and unified Actions
- **Experience module** with rich CRUD, media upload, skills tagging, and drag‑and‑drop ordering
- **Clean REST API** consumed by the Next.js site
- **Production‑ready** storage, caching, and CORS guidance
- **Developer DX**: clear local setup, documented vhosts, and quick cache commands

```mermaid
flowchart LR
  A[Admin (Laravel 12 + Filament 4)] -- REST API --> B[Public Site (Next.js)]
  A <-- Storage (public disk) --> S[(Uploads)]
```

The admin lives on a subdomain in production (`https://admin.allanwebdesign.com`). The public site is deployed separately (Render or similar) at `https://www.allanwebdesign.com`.

## Table of Contents

- [Overview & Highlights](#overview--highlights)
- [Prerequisites](#prerequisites)
- [Local development](#local-development)
  - [Local domains](#local-domains)
  - [1) Hosts entries](#1-hosts-entries)
  - [2) Apache vhosts (Laragon)](#2-apache-vhosts-laragon)
  - [3) Admin app (Laravel + Filament)](#3-admin-app-laravel--filament)
- [Experience Module (Admin)](#experience-module-admin)
  - [UI](#ui)
  - [Data model](#data-model)
  - [API](#api)
  - [Filament 4 decisions](#filament-4-decisions)
  - [Future me notes](#future-me-notes)
- [4) Public app (Next.js)](#4-public-app-nextjs)
- [Repository layout](#repository-layout)
- [Environment variables](#environment-variables)
- [Git ignore for uploads](#git-ignore-for-uploads)
- [Production (high level)](#production-high-level)
- [Useful commands](#useful-commands)
- [Security notes](#security-notes)

## Overview & Highlights

- **Admin-first CMS**: Laravel 12 + Filament 4 as a headless content system powering a Next.js site.
- **Filament 4-native**: Uses Schemas (`Filament\\Schemas`) and unified Actions (`Filament\\Actions`) across resources.
- **Experience module**: Rich CRUD, media uploads, skills tagging, drag-and-drop ordering, and a clean REST API.
- **Local DX**: One-command cache clear, consistent local domains via Laragon, and documented vhosts.
- **Production-ready**: Storage symlink, route/config caching notes, and CORS guidance for the Next.js domain.

## Prerequisites

- PHP 8.3+, Composer
- Node.js 18+ (or LTS compatible with Next.js 15)
- Laragon on Windows (Apache)

## Local development

Local domains:

- Admin (Laravel): `http://admin.allanwebdesign.com.2025.test`
- Public (Next.js): `http://allanwebdesign.com.2025.test`

### 1) Hosts entries
Edit `C:\Windows\System32\drivers\etc\hosts` as Administrator and add:

```
127.0.0.1 admin.allanwebdesign.com.2025.test
127.0.0.1 allanwebdesign.com.2025.test
```

### 2) Apache vhosts (Laragon)
Place these files under `C:\laragon\etc\apache2\sites-enabled\`:

`admin.allanwebdesign.com.2025.test.conf`
```apache
<VirtualHost *:80>
    ServerName admin.allanwebdesign.com.2025.test
    DocumentRoot "e:/laragon/www/allanwebdesign.com.2025/apps/admin-laravel/public"

    <Directory "e:/laragon/www/allanwebdesign.com.2025/apps/admin-laravel/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>

    ErrorLog "logs/admin-laravel-error.log"
    CustomLog "logs/admin-laravel-access.log" combined
</VirtualHost>
```

`allanwebdesign.com.2025.test.conf` (reverse proxy to Next.js dev server)
```apache
<VirtualHost *:80>
    ServerName allanwebdesign.com.2025.test

    ProxyPreserveHost On
    ProxyPass / http://127.0.0.1:3000/
    ProxyPassReverse / http://127.0.0.1:3000/

    ErrorLog "logs/web-next-error.log"
    CustomLog "logs/web-next-access.log" combined
</VirtualHost>
```

Ensure these modules are enabled in `httpd.conf`:

```
LoadModule proxy_module modules/mod_proxy.so
LoadModule proxy_http_module modules/mod_proxy_http.so
LoadModule rewrite_module modules/mod_rewrite.so
IncludeOptional etc/apache2/sites-enabled/*.conf
```

Restart Apache from Laragon after changes.

### 3) Admin app (Laravel + Filament)

From `apps/admin-laravel/`:

```
cp .env.example .env  # or configure DB accordingly
php artisan key:generate
php artisan migrate
php artisan db:seed   # Seeds Filament admin user
php artisan storage:link
php artisan filament:install
# If needed to create a panel explicitly:
# php artisan make:filament-panel Admin
```

- Local admin URL: `http://admin.allanwebdesign.com.2025.test/admin`
- Admin credentials are seeded via `Database\Seeders\FilamentAdminSeeder` and can be overridden with:
  - `FILAMENT_ADMIN_NAME`
  - `FILAMENT_ADMIN_EMAIL`
  - `FILAMENT_ADMIN_PASSWORD`

Media storage (local/dev and Hostinger): uses Laravel `public` disk.

## Experience Module (Admin)

- **Location**: `apps/admin-laravel/app/Filament/Resources/ExperienceResource.php`
- **UI**:
  - Layouts via `Filament\Schemas\Components\Fieldset` (Filament 4).
  - Actions via `Filament\Actions` (`EditAction`, `DeleteAction`, `BulkActionGroup`).
  - Drag-and-drop reordering on `sort_order`.
  - Badge coloring for employment type, boolean icon for current role.
- **Data model**: `App\Models\Experience` with JSON casts (`skills`, `media`), scopes, and `user` relation.
- **API**: `routes/api.php` + `App\Http\Controllers\Api\ExperienceController`
  - `GET /api/v1/experiences`
  - `GET /api/v1/experiences/current`
  - `GET /api/v1/experiences/{id}`
  - Returns formatted dates, duration, and media URLs.
- **Filament 4 decisions**:
  - Resource signature is `form(Schema $schema): Schema`.
  - Navigation types match parent: `$navigationIcon` as `BackedEnum|string|null`, `$navigationGroup` as `UnitEnum|string|null`.

### Future me notes

- Clear caches after changing Filament resources:
  ```bash
  php artisan optimize:clear
  ```
- If tables/actions look off, confirm imports:
  - `use Filament\Schemas\Schema;`
  - `use Filament\Schemas\Components\Fieldset;`
  - `use Filament\Actions;`
- Media paths are stored relative to `public` disk; `php artisan storage:link` must exist.
- Local admin: `http://admin.allanwebdesign.com.2025.test/admin`.
- When adding new resources, stick to Filament 4 namespaces (no `Tables\\Actions` or `Forms\\Form`).

### 4) Public app (Next.js)

From `apps/web-next/`:

```
npm install
npm run dev
```

Create `.env.local` with:
```
NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test
```

Open `http://allanwebdesign.com.2025.test`.

## Repository layout

```
apps/
  admin-laravel/  # Laravel 12 + Filament 4
  web-next/       # Next.js (App Router, Tailwind)
infra/            # CI/CD & deployment (Render/Hostinger) — planned
packages/
  shared/         # Shared types/utils (optional)
```

## Environment variables

Laravel (`apps/admin-laravel/.env`):
```
APP_URL=http://admin.allanwebdesign.com.2025.test
FILESYSTEM_DISK=public
```

Next.js (`apps/web-next/.env.local`):
```
NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test
```

## Git ignore for uploads

Uploads are not committed to Git. At repo root `.gitignore`:
```
apps/admin-laravel/storage/app/public/**
apps/admin-laravel/public/storage/**
apps/admin-laravel/public/uploads/**
```

## Production (high level)

- Admin (Laravel): Hostinger subdomain `admin.allanwebdesign.com` → document root to `apps/admin-laravel/public`
- Public (Next.js): Render with Root Directory set to `apps/web-next`
- Media: stored on Hostinger (Laravel `public` disk) initially
- CORS: lock to public domain in production
- CI/CD: GitHub Actions path filters to deploy each app independently (planned)

## Useful commands

```
# Laravel
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed

# Next.js
npm run dev
npm run build && npm run start
```

## Security notes

- Change the seeded admin password after first login.
- Consider enabling 2FA and role-based access with spatie/permission.
- Force HTTPS in production (TrustProxies + `URL::forceScheme('https')`).

---

See `CHANGELOG.md` for notable changes.
