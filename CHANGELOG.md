## [0.8.3] - 2025-10-24
### Changed
- Next.js: deduplicated Appearance API calls by introducing a cached `getAppearance()` wrapper around `fetchAppearance()` using React `cache()`.
- Next.js: reduced noisy dev logs for Appearance fetch; now warns once on failure in development and falls back to defaults.
- Updated all call sites to use `getAppearance()` (`app/layout.tsx`, `app/(site)/layout.tsx`, `app/(site)/page.tsx`, `app/(site)/[section]/page.tsx`).

### Documentation
- README: added guidance for local development to use `NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test:8000` when running `php artisan serve` instead of Laragon vhost.
- README: troubleshooting note for 404s on `/api/v1/appearance` and how to fix via `:8000` port or Apache vhost.

## [0.8.2] - 2025-10-22
### Added
- SEO-friendly section routes in Next.js public app:
  - New dynamic route `apps/web-next/app/(site)/[section]/page.tsx` enabling `/experience`, `/education`, `/projects`, `/certifications`.
- Server navigation component:
  - Renamed `ControlsBar` → `SectionNav` and converted to a Server Component with accessible `aria-current="page"`.
- Documentation:
  - Added `TEST.md` with Postman collection, CDN images/CORS tutorial (Cloudflare R2 + Cloudflare Rules), and local domain setup guide.

### Changed
- Classic template serverization and URL-driven rendering:
  - `apps/web-next/templates/classic/index.tsx` now uses `ui.sec` from route and renders sections on the server; removed client filtering.
  - `apps/web-next/app/(site)/page.tsx` no longer passes query-based UI state.
- Images configuration:
  - `apps/web-next/next.config.ts` restricted to CDN-only images via `NEXT_PUBLIC_CDN_HOST`.
- API client resiliency:
  - `apps/web-next/lib/api.ts` `fetchAppearance()` now logs status and returns a sane default when the Appearance API is unavailable (404), avoiding runtime errors.

### Fixed
- Education API error: "Call to a member function pluck() on string" in Laravel admin
  - `apps/admin-laravel/app/Http/Controllers/Api/EducationController.php` now uses the relation builder (`$education->skills()->pluck('name')`) to avoid attribute/relationship shadowing.
- Frontend crash on missing Appearance API
  - Next.js now gracefully falls back to default appearance values instead of throwing on 404.

## [0.8.1] - 2025-10-10
### Added
- PDF preview functionality in Certification edit form:
  - Added embedded PDF viewer with iframe support
  - Temporary signed URLs for secure previews
  - Fallback to direct URLs when signed URLs aren't available
- R2 configuration for Cloudflare CDN with custom domain support

### Changed
- Updated `CertificationResource` to use Filament v4 patterns:
  - Replaced `Forms\\View` with `Placeholder` component for better compatibility
  - Added proper type hints with `Filament\\Schemas\\Components\\Utilities\\Get`
  - Improved error handling for file operations
- Enhanced R2 disk configuration with proper CORS and visibility settings

### Fixed
- Resolved 500 errors in Certification edit form by:
  - Fixing type mismatches in form field closures
  - Adding proper error handling for file operations
  - Ensuring proper URL generation for R2 storage

## [0.8.0] - 2025-10-09
### Added
- Cloudflare R2 storage integration for media uploads:
  - All Filament FileUpload fields now use `->disk('r2')` in `apps/admin-laravel`.
  - API controllers build URLs with `Storage::disk('r2')->url(...)`.
- Migration command `app:migrate-to-r2` to copy existing files from `public` to `r2` with options:
  - `--dry-run`, `--all`, `--prefix`, `--delete-local`.
  - Skips dotfiles and hardens existence checks.
- Next.js `images.remotePatterns` now allow a CDN host via `NEXT_PUBLIC_CDN_HOST` (defaults to `cdn.allanwebdesign.com`).

### Changed
- `config/filesystems.php`: moved and finalized `r2` disk under `disks`.
- Documentation:
  - `apps/admin-laravel/README.md` updated with Option B (custom domain) instructions and `.env` example using `AWS_URL=https://cdn.allanwebdesign.com`.
  - Root `README.md` environment variables updated to use `FILESYSTEM_DISK=r2` plus R2 variables.

### Notes
- Ensure `league/flysystem-aws-s3-v3` is installed in the admin app.
- Set the same R2 env vars in Render/Vercel. For Next.js, add the CDN domain to `next.config.ts` or set `NEXT_PUBLIC_CDN_HOST`.

## [0.7.3] - 2025-10-03
### Added
- Tailwind CSS wired globally in Next.js app:
  - `apps/web-next/app/globals.css` with `@tailwind` directives
  - `apps/web-next/tailwind.config.ts` scanning `app/**`, `components/**`, `templates/**`
  - `apps/web-next/postcss.config.mjs` already configured with `@tailwindcss/postcss`
- Brand theming via CSS variables set on `<body>` in `apps/web-next/app/layout.tsx`:
  - `--brand-primary`, `--brand-secondary` derived from `appearance`

### Changed
- Refactored templates to Tailwind utilities:
  - `apps/web-next/templates/classic/index.tsx`
  - `apps/web-next/templates/modern/index.tsx`

## [0.7.2] - 2025-10-03
### Added
- Next.js dynamic template rendering:
  - `apps/web-next/templates/registry.ts` with dynamic loader (classic, modern)
  - Initial templates: `templates/classic/`, `templates/modern/`
  - Types: `types/appearance.ts` (`Appearance`, `SeoMeta`, `TemplateMeta`)
  - API client: `lib/api.ts` (`fetchAppearance`, `fetchTemplates`) using `NEXT_PUBLIC_API_BASE_URL`
  - App Router page wiring: `app/(site)/page.tsx` resolves template via `?template=<slug>` → active template → env fallback

### Changed
- `tsconfig.json` path alias `@/*` expanded to project root to resolve imports outside `src/`.

## [0.7.1] - 2025-10-03
### Added
- Next.js public app stabilization in `apps/web-next` (App Router):
  - Created `app/layout.tsx` and `app/(site)/page.tsx` minimal shell.
  - Added `app/api/hello/route.ts` sample route.
- Image domains are now configurable via `.env.local` and injected into `next.config.ts`:
  - `NEXT_PUBLIC_IMAGE_HOST_LOCAL=admin.allanwebdesign.com.2025.test`
  - `NEXT_PUBLIC_IMAGE_HOST_PROD=allanwebdesign.com`
  - Configured `images.remotePatterns` to allow `/storage/**` from both hosts.

### Changed
- Resolved router conflict by removing Pages Router routes at `src/pages` in favor of App Router.
- Documentation: added `FUTURE_PLAN.md` describing cloud uploads + SaaS migration.

### Notes
- Keep `NEXT_PUBLIC_API_BASE_URL` in `.env.local` for API calls from Next.js.

## [0.7.0] - 2025-10-02
### Added
- Templating backend scaffolding in `apps/admin-laravel`:
  - Config `config/templates.php` with initial templates (`classic`, `modern`).
  - Public API endpoints:
    - `GET /api/v1/templates`
    - `GET /api/v1/appearance`
    - `GET /api/v1/settings`
    - `GET /api/v1/settings/current`
    - `GET /api/v1/settings/{id}`
  - Controllers: `App\Http\Controllers\Api\TemplatesController`, `AppearanceController`, `SettingController`.
  - Migration `2025_10_02_190001_add_appearance_fields_to_settings_table.php` adding `active_public_template`, `brand_primary_color`, `brand_secondary_color` to `settings`.
  - Filament v4 `SettingResource` updated with an "Appearance" fieldset (template select, brand primary/secondary color pickers).
  - Routes updated in `routes/api.php`.

### Changed
- README: documented appearance fields under Settings module and listed new API endpoints (Templates, Appearance, Settings).

## [0.6.0] - 2025-10-02
### Added
- Settings module in `apps/admin-laravel`:
  - Migration `2025_10_02_000013_create_settings_table.php` with fields: headline, about_me, logo, favicon, seo_title, seo_description, seo_keywords[], contact (email/phone/whatsapp), social links (GitHub/LinkedIn/Twitter/X/YouTube/Dribbble/Behance), date_of_birth, gender, marital_status, nationality, structured address (line1/line2/city/state/postal_code/country), availability (open_to_work, hourly_rate, preferred_roles[]), sort_order, user relation.
  - Alter migration `2025_10_02_140324_add_profile_picture_to_settings_table.php` to add `profile_picture` for existing DBs; also included in the create migration for fresh installs.
  - Model `App\Models\Setting` with casts and scopes.
  - Filament 4 `SettingResource` with fieldsets for Profile, Media (including Profile Picture, max 800×800), SEO, Contact Info, Social Links, Personal Information, Address, Availability, and Display Order; reorderable by `sort_order`.

### Changed
- README updated with Settings module documentation and media constraints.

## [0.5.0] - 2025-09-30
### Added
- Projects module in `apps/admin-laravel`:
  - Migration `2025_09_30_000009_create_projects_table.php` with fields (name, description, is_current, start/end, media, sort_order) and relations (user_id, experience_id nullable).
  - Pivot `2025_09_30_000010_create_project_skill_table.php` for many-to-many `skills`.
  - Model `App\Models\Project` with casts, scopes (`current`, `ordered`), and relations (`skills`, `links`, `experience`, `user`).
  - Filament 4 `ProjectResource` with fieldsets for Basic, Duration, Description, Skills, Media, Links, and Display Order; reorderable by `sort_order`.
- Reusable Links taxonomy:
  - Migration `2025_09_30_000011_create_links_table.php`.
  - Pivot `2025_09_30_000012_create_link_project_table.php`.
  - Model `App\Models\Link` and Filament 4 `LinkResource` (Taxonomies group) with List/Create/Edit pages.
- Public API endpoints for Projects (`App\Http\Controllers\Api\ProjectController`):
  - `GET /api/v1/projects`
  - `GET /api/v1/projects/current`
  - `GET /api/v1/projects/{id}`

### Changed
- README updated with Projects module details, Links admin, API endpoints, Postman and cURL examples.

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
