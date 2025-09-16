# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project adheres to Semantic Versioning (where practical).

## [0.1.0] - 2025-09-16
### Added
- Monorepo initialized at `e:/laragon/www/allanwebdesign.com.2025/`.
- Folder layout created: `apps/admin-laravel`, `apps/web-next`, `infra/`, `packages/shared/` (placeholder).
- Root `.gitignore` configured to ignore Laravel uploads (`storage/app/public`, `public/storage`, `public/uploads`).
- Local Laragon vhosts set up:
  - `admin.allanwebdesign.com.2025.test` → Laravel public dir.
  - `allanwebdesign.com.2025.test` → Reverse proxy to Next.js dev server on 3000.
- Laravel 12 + Filament 4 installed.
- Seeded Filament admin user via `Database\Seeders\FilamentAdminSeeder` with env overrides.
- Next.js app scaffolded with Tailwind & App Router.
- README with local setup instructions.

### Fixed
- Resolved 404 for `/admin` by installing Filament and creating a panel (if needed) and ensuring Apache vhosts apply.

### Notes
- Media storage currently uses Laravel `public` disk on Hostinger.
- Next steps: design API endpoints, create Filament resources (Profile, Experience, Project), configure CORS, and set up CI/CD.
