#### Local API base URL note

- If you run the admin with `php artisan serve` (port 8000), point Next.js to that port:
  ```
  NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test:8000
  ```
  Or use `http://127.0.0.1:8000` during dev. If you use Laragon’s Apache vhost on port 80, the `:8000` suffix is not needed.

#### Local R2 images note

- To avoid CORS in local development, serve images from the R2 Public URL instead of the S3 API or CDN domain.
- Admin `.env` (local): set `AWS_URL=https://pub-<id>.r2.dev`.
- Next.js `.env.local` (local): set `NEXT_PUBLIC_CDN_HOST=pub-<id>.r2.dev`.
- In production, switch `NEXT_PUBLIC_CDN_HOST` back to `cdn.allanwebdesign.com` and keep `AWS_URL=https://cdn.allanwebdesign.com`.

# Allan Web Design – Portfolio Platform

A modern, API‑driven portfolio platform built as a monorepo. The admin app (Laravel 12 + Filament 4) serves as a headless CMS powering a Next.js public site, with clean REST endpoints and a streamlined content workflow.

## At a glance

- **Filament 4‑native** resources using Schemas and unified Actions
- **Experience, Education, Projects, Certifications, and Settings modules** with rich CRUD, media upload, skills taxonomy, reusable links, and drag‑and‑drop ordering
- **Clean REST API** consumed by the Next.js site
- **Production‑ready** storage, caching, and CORS guidance
- **Developer DX**: clear local setup, documented vhosts, and quick cache commands

```mermaid
flowchart LR
  A[Admin (Laravel 12 + Filament 4)] -- REST API --> B[Public Site (Next.js)]
  A -- S3 API --> R2[(Cloudflare R2 Bucket)]
  B -- Images --> CDN[cdn.allanwebdesign.com]
```

The admin lives on the main domain in production (`https://allanwebdesign.com`). Media uploads are stored on Cloudflare R2 and publicly served via `https://cdn.allanwebdesign.com`. The public site is deployed on Vercel at `https://ronaldallanrivera.com`.

## Table of Contents

- [Overview & Highlights](#overview--highlights)
- [Prerequisites](#prerequisites)
- [Local development](#local-development)
  - [Local domains](#local-domains)
  - [1) Hosts entries](#1-hosts-entries)
  - [2) Apache vhosts (Laragon)](#2-apache-vhosts-laragon)
  - [3) Admin app (Laravel + Filament)](#3-admin-app-laravel--filament)
- [Experience Module (Admin)](#experience-module-admin)
- [Education Module (Admin)](#education-module-admin)
  - [UI](#ui)
  - [Data model](#data-model)
  - [API](#api)
  - [Filament 4 decisions](#filament-4-decisions)
  - [Future me notes](#future-me-notes)
- [API endpoints](#api-endpoints)
- [API testing with Postman](#api-testing-with-postman)
  - [Environment](#environment)
  - [Collection requests](#collection-requests)
  - [Tests](#tests)
  - [cURL equivalents](#curl-equivalents)
  - [Troubleshooting](#troubleshooting)
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
- **Data model**: `App\Models\Experience` with many‑to‑many `skills` (pivot `experience_skill`), JSON `media`, scopes, and `user` relation.
- **API**: `routes/api.php` + `App\Http\Controllers\Api\ExperienceController`
  - `GET /api/v1/experiences`
  - `GET /api/v1/experiences/current`
  - `GET /api/v1/experiences/{id}`
  - Returns formatted dates, duration, and media URLs.
- **Filament 4 decisions**:
  - Resource signature is `form(Schema $schema): Schema`.
  - Navigation types match parent: `$navigationIcon` as `BackedEnum|string|null`, `$navigationGroup` as `UnitEnum|string|null`.

## Projects Module (Admin)

- **Location**: `apps/admin-laravel/app/Filament/Resources/ProjectResource.php`
- **UI**:
  - Filament 4 Schemas with fieldsets for Basic, Duration, Description, Skills, Media, Links, and Display Order
  - Actions via `Filament\Actions` (`EditAction`, `DeleteAction`, `BulkActionGroup`)
  - Drag-and-drop reordering on `sort_order`
- **Data model**: `App\Models\Project`
  - Fields: `name`, `description`, `is_current`, `start_date`, `end_date`, `media`, `sort_order`
  - Relations: `skills` (many-to-many via `project_skill`), `links` (many-to-many via `link_project`), `experience` (belongsTo – "Associated with" current company)
- **Links taxonomy (Admin)**: `apps/admin-laravel/app/Filament/Resources/LinkResource.php`
  - Manage reusable links (fields: `label`, `url`, `type` such as Live/Repo/Docs/Demo/Case Study)
  - Used by Project form via multi-select with inline create
- **API**: `routes/api.php` + `App\Http\Controllers\Api\ProjectController`
  - `GET /api/v1/projects`
  - `GET /api/v1/projects/current`
  - `GET /api/v1/projects/{id}`
  - Returns formatted dates, duration, media URLs, related skills, links, and associated experience

## Settings Module (Admin)

- **Location**: `apps/admin-laravel/app/Filament/Resources/SettingResource.php`
- **Model**: `apps/admin-laravel/app/Models/Setting.php`
- **Migration**: `database/migrations/2025_10_02_000013_create_settings_table.php`
  - Alter migration for existing DBs: `2025_10_02_140324_add_profile_picture_to_settings_table.php`
- **UI** (Filament 4 Schemas):
  - Fieldsets: Profile, Media, SEO, Contact Info, Social Links, Personal Information, Address, Availability, Display Order
  - Media uploads:
    - Logo → `public/settings/logo`
    - Favicon → `public/settings/favicon`
    - Profile Picture → `public/settings/profile` (square editor, max 800×800)
- **Fields**:
  - Headline, About Me
  - SEO Title, SEO Description, SEO Keywords[]
  - Contact: email, phone, WhatsApp
  - Social: GitHub, LinkedIn, Twitter/X, YouTube, Dribbble, Behance
  - Personal: date_of_birth, gender, marital_status, nationality
  - Address (structured): line1, line2, city, state, postal_code, country
  - Availability: open_to_work, hourly_rate, preferred_roles[]
  - Appearance: active_public_template, brand_primary_color, brand_secondary_color
- **Notes**:
  - Multiple settings records supported; ordered via `sort_order`
  - Planned API: `GET /api/v1/settings`, `GET /api/v1/settings/{id}`, optional `GET /api/v1/settings/primary`

### Future me notes

  ```bash
  php artisan optimize:clear
  php artisan route:list --path=api
  ```

## Education Module (Admin)

- **Location**: `apps/admin-laravel/app/Filament/Resources/EducationResource.php`
- **UI**:
  - Layouts via `Filament\Schemas\Components\Fieldset` (Filament 4).
  - Actions via `Filament\Actions` (`EditAction`, `DeleteAction`, `BulkActionGroup`).
  - Drag-and-drop reordering on `sort_order`.
  - Boolean icon for current study; optional grade column.
- **Data model**: `App\Models\Education` with many‑to‑many `skills` (pivot `education_skill`), JSON `media`, scopes (`current`, `ordered`), and `user` relation.
- **API**: `routes/api.php` + `App\Http\Controllers\Api\EducationController`
  - `GET /api/v1/educations`
  - `GET /api/v1/educations/current`
  - `GET /api/v1/educations/{id}`
  - Returns formatted dates, duration, and media URLs.
- **Filament 4 decisions**:
  - Resource signature is `form(Schema $schema): Schema`.
  - Navigation types match parent: `$navigationIcon` as `BackedEnum|string|null`, `$navigationGroup` as `UnitEnum|string|null`.

## API endpoints

- **Base URL** (local): `http://admin.allanwebdesign.com.2025.test/api/v1`
- **Experiences** (`App\\Http\\Controllers\\Api\\ExperienceController`)
  - `GET /experiences`
  - `GET /experiences/current`
  - `GET /experiences/{id}`
- **Educations** (`App\\Http\\Controllers\\Api\\EducationController`)
  - `GET /educations`
  - `GET /educations/current`
  - `GET /educations/{id}`

- **Certifications** (`App\\Http\\Controllers\\Api\\CertificationController`)
  - `GET /certifications`
  - `GET /certifications/current`
  - `GET /certifications/{id}`

- **Projects** (`App\\Http\\Controllers\\Api\\ProjectController`)
  - `GET /projects`
  - `GET /projects/current`
  - `GET /projects/{id}`

Routes file: `apps/admin-laravel/routes/api.php`.

## API testing with Postman

### Environment

- Create environment: `Allan Portfolio Local`
- Variables:
  - `base_url`: `http://admin.allanwebdesign.com.2025.test`
  - `api_base`: `{{base_url}}/api/v1`

### Collection requests

Create collection: `Portfolio API v1` and add requests below (add `Accept: application/json`).

- **Experiences**
  - GET `{{api_base}}/experiences`
  - GET `{{api_base}}/experiences/current`
  - GET `{{api_base}}/experiences/1`

- ** Educations**
  - GET `{{api_base}}/educations`
  - GET `{{api_base}}/educations/current`
  - GET `{{api_base}}/educations/1`

- ** Projects**
  - GET `{{api_base}}/projects`
  - GET `{{api_base}}/projects/current`
  - GET `{{api_base}}/projects/1`

### Tests

Use this in the Postman Tests tab for list endpoints:

```javascript
pm.test("Status 200", () => pm.response.to.have.status(200));
pm.test("JSON", () => pm.response.to.be.json);
const json = pm.response.json();
pm.test("success === true", () => json.success === true);
pm.test("data is array", () => Array.isArray(json.data));
```

### cURL equivalents

```bash
# Experiences
curl -H "Accept: application/json" "{{base_url}}/api/v1/experiences"
curl -H "Accept: application/json" "{{base_url}}/api/v1/experiences/current"
curl -H "Accept: application/json" "{{base_url}}/api/v1/experiences/1"

# Educations
curl -H "Accept: application/json" "{{base_url}}/api/v1/educations"
curl -H "Accept: application/json" "{{base_url}}/api/v1/educations/current"
curl -H "Accept: application/json" "{{base_url}}/api/v1/educations/1"

# Projects
curl -H "Accept: application/json" "{{base_url}}/api/v1/projects"
curl -H "Accept: application/json" "{{base_url}}/api/v1/projects/current"
curl -H "Accept: application/json" "{{base_url}}/api/v1/projects/1"
```

### Troubleshooting

- Ensure you call endpoints with `/api/v1/...` prefix (not `/...`).
- If media URLs 404, run `php artisan storage:link`.
- Clear caches after adding controllers/routes:
  ```bash
  php artisan optimize:clear
  php artisan route:list --path=api
  ```

- If browser blocks images with CORS on local:
  - Use the R2 public URL in admin `.env` (`AWS_URL=https://pub-<id>.r2.dev`) and set Next.js `.env.local` `NEXT_PUBLIC_CDN_HOST=pub-<id>.r2.dev`.
  - Keep `cdn.allanwebdesign.com` for production and add CORS headers at Cloudflare if needed.

### 4) Public app (Next.js)

From `apps/web-next/`:

```
{{ ... }}
npm run dev
```

Create `.env.local` with:
```
NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test
```

Open `http://allanwebdesign.com.2025.test`.

#### SEO-friendly routes and server navigation

- **Sections as routes**: The public site supports section URLs:
  - `/experience`, `/education`, `/projects`, `/certifications`
  - Implemented via `apps/web-next/app/(site)/[section]/page.tsx`
- **Server navigation**: `SectionNav` (server component) renders accessible links and highlights the active section via `aria-current="page"`.
- **Server-rendered template**: `templates/classic/index.tsx` renders content based on the route-provided `ui.sec` on the server (no client filtering).

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
FILESYSTEM_DISK=r2
AWS_URL=https://pub-<id>.r2.dev   # local dev: public R2 URL to avoid CORS
```

Next.js (`apps/web-next/.env.local`):
```
NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test
NEXT_PUBLIC_CDN_HOST=pub-<id>.r2.dev   # local dev: same host allowed by next.config.ts
```

**Production**:
```
NEXT_PUBLIC_API_BASE_URL=https://allanwebdesign.com
NEXT_PUBLIC_CDN_HOST=cdn.allanwebdesign.com
```

## Git ignore for uploads

Uploads are not committed to Git. At repo root `.gitignore`:
```
apps/admin-laravel/storage/app/public/**
apps/admin-laravel/public/storage/**
apps/admin-laravel/public/uploads/**
```

## Production Deployment

### Architecture
- **Admin (Laravel)**: `https://allanwebdesign.com` (Hostinger)
  - Document root: `public_html` (Laravel's `public` folder)
  - Serves as headless CMS with REST API
  - Handles file uploads and media storage
- **Public (Next.js)**: `https://ronaldallanrivera.com` (Vercel)
  - Free tier with global CDN for maximum speed
  - Instant loading, no wake-up delays
  - Consumes Laravel API endpoints

### Performance Strategy
- **Portfolio site speed**: Vercel's edge network ensures fast loading worldwide
- **Cost optimization**: $0 additional cost (Vercel free + existing Hostinger)
- **Cross-domain handling**: API calls from `ronaldallanrivera.com` → `allanwebdesign.com`
  - Implement response caching in Next.js to minimize API calls
  - Configure CORS in Laravel to allow `ronaldallanrivera.com`

### Deployment Steps
1. **Laravel on Hostinger**:
   - Deploy `apps/admin-laravel` to `public_html`
   - Set document root to `public_html/public`
   - Configure environment variables
   - Run migrations via cron jobs

2. **Next.js on Vercel**:
   - Connect GitHub repository
   - Set root directory to `apps/web-next`
   - Add custom domain `ronaldallanrivera.com`
   - Configure `NEXT_PUBLIC_API_BASE_URL=https://allanwebdesign.com`
   - Vercel handles SSL and CDN automatically

### CORS Configuration
```php
// config/cors.php
'allowed_origins' => ['https://ronaldallanrivera.com'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
'allowed_headers' => ['*'],
```

### Domains & DNS Strategy

- **Registrar choice**: Buy/manage domains on Hostinger (often 1st year free with hosting; cheaper renewals). Avoid purchasing on Vercel (more convenient, but generally pricier and no free year).
- **Assignments**:
  - `allanwebdesign.com` → Hostinger (Laravel admin/API)
  - `ronaldallanrivera.com` → Vercel (Next.js), but keep DNS on Hostinger
- **DNS (set in Hostinger DNS Zone)**:
  - `allanwebdesign.com` apex: A `@` → <HOSTINGER_IP>
  - `www.allanwebdesign.com`: CNAME `www` → `@`
  - `ronaldallanrivera.com` apex: A `@` → `76.76.21.21` (Vercel)
  - `www.ronaldallanrivera.com`: CNAME `www` → `cname.vercel-dns.com`
  - Vercel verification: TXT `_vercel-verification` → token provided by Vercel when adding the domain

See `DEPLOYMENT_PLAN.md` for the full, step-by-step configuration.

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
