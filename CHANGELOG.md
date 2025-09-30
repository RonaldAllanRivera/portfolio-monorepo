# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project adheres to Semantic Versioning (where practical).

## [0.2.0] - 2025-09-30
### Added
- Experience module in `apps/admin-laravel`:
  - Migration `2025_09_30_000001_create_experiences_table.php` with LinkedIn-style fields.
  - Model `App\Models\Experience` with casts, scopes, relationships.
  - Filament 4 Resource `App\Filament\Resources\ExperienceResource` with rich form/table.
  - Resource pages: List, Create, Edit under `App\Filament\Resources\ExperienceResource\Pages`.
  - REST API (`routes/api.php`, `App\Http\Controllers\Api\ExperienceController`) with endpoints:
    - `GET /api/v1/experiences`
    - `GET /api/v1/experiences/current`
    - `GET /api/v1/experiences/{id}`

### Changed
- Upgraded Resource method signature to Filament 4 Schemas: `form(Schema $schema): Schema`.
- Replaced deprecated layout usage with Schemas components (`Filament\Schemas\Components\Fieldset`).
- Migrated table actions to unified `Filament\Actions` namespace (`EditAction`, `DeleteAction`, `BulkActionGroup`).
- Navigation properties typed to match Filament 4 (`BackedEnum|string|null` and `UnitEnum|string|null`).

### Fixed
- Fatal errors during bootstrapping caused by outdated namespaces and method signatures.
- Removed stray placeholder content in `ExperienceResource` that broke class parsing.

### Notes
- Admin panel URL (local): `http://admin.allanwebdesign.com.2025.test/admin`.
- Media stored on Laravel `public` disk; `php artisan storage:link` required after deployment.

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
