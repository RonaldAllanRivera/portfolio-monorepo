# Allan Web Design Monorepo

Monorepo for a two-app portfolio system:

- `apps/admin-laravel/` — Laravel 12 + Filament 4 admin (headless CMS/API)
- `apps/web-next/` — Next.js public site (SSR/ISR)
- `infra/` — CI/CD and deployment scripts (planned)
- `packages/shared/` — shared code and types (optional)

The admin lives on a subdomain in production (`https://admin.allanwebdesign.com`). The public site is deployed separately (Render or similar) at `https://www.allanwebdesign.com`.

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
