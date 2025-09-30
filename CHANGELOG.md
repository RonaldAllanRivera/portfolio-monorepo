## [0.4.0] - 2025-09-30
### Added
- Certifications module in `apps/admin-laravel`:
  - Migrations `2025_09_30_000003_create_organizations_table.php` and `2025_09_30_000004_create_certifications_table.php`.
  - Models: `App\Models\Organization`, `App\Models\Certification` (SoftDeletes, scopes).
  - Filament 4 Resources: `OrganizationResource`, `CertificationResource` with forms/tables.
  - Public API endpoints (`App\Http\Controllers\Api\CertificationController`):
    - `GET /api/v1/certifications`
    - `GET /api/v1/certifications/current`
    - `GET /api/v1/certifications/{id}`
- Skills taxonomy module:
  - Migration `2025_09_30_000005_create_skills_table.php`.
  - Pivot tables: `certification_skill`, `experience_skill`, `education_skill`.
  - Model: `App\Models\Skill`.
  - Filament 4 `SkillResource` with category grouping (collapsible), category filter, and default "All" pagination.
- Seeders:
  - `OrganizationSeeder` (LinkedIn Learning, Udemy).
  - `SkillSeeder` (curated categories and skills list).
  - `CertificationSeeder` (LinkedIn Learning certifications from CSV converted to code).

### Changed
- Experience and Education now use many-to-many `skills` relations (removed JSON skills), updated Filament forms to searchable multi-select with inline create.
- Certification, Experience, and Education API responses now return `skills` as an array of names; eager loading of `skills` in controllers.

### Fixed
- `education_skill` FK now references the correct `educations` table; added guard to drop existing table before create to recover from partial runs.

### Notes
- Skills page UX: grouped by category (collapsible) with a category filter; pagination defaults to "All" and can be adjusted.

# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project adheres to Semantic Versioning (where practical).

## [0.3.0] - 2025-09-30
### Added
- Education module in `apps/admin-laravel`:
  - Migration `2025_09_30_000002_create_educations_table.php` with fields (school, degree, field_of_study, start/end, is_current, grade, activities_and_societies, description, skills, media, sort_order).
  - Model `App\Models\Education` with casts, SoftDeletes, scopes (`current`, `ordered`), and `user` relation.
  - Filament 4 Resource `App\Filament\Resources\EducationResource` with Fieldset layouts, unified Actions, and reorderable table.
  - Resource pages: List, Create, Edit.
- Public API endpoints for Educations:
  - `GET /api/v1/educations`
  - `GET /api/v1/educations/current`
  - `GET /api/v1/educations/{id}`

### Changed
- README updated with overview, Education module details, API endpoints, and Postman testing guide.

### Fixed
- Eloquent table mapping for Education model (`$table = 'educations'`) to prevent "Base table ... education doesn't exist" errors.


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
