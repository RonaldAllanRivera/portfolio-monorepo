# Future Plan: Uploads, Cloud Storage, and SaaS Readiness

This document outlines the plan to evolve media uploads from local disk to cloud storage, preparing the platform for multi-tenant SaaS.

## 1) Current Architecture (Today)

- **Disk**: Laravel `public` disk (physical path: `apps/admin-laravel/storage/app/public`), served via `/storage` symlink.
- **Folders (module-based)**:
  - Settings: `settings/logo`, `settings/favicon`, `settings/profile`
  - Experience: `experiences/media`
  - Education: `educations/media`
  - Projects: `projects/media`
  - Certifications: `certifications/media`
- **URLs**: Built via `Storage::disk('public')->url($path)` in API controllers.
- **Filament**: `FileUpload` components specify `->disk('public')->directory('<module>/...')` with image constraints.

## 2) Goals

- **Cloud-ready**: Switchable to S3-compatible storage without code churn.
- **SaaS-ready**: Tenant-isolated prefixes, optional quotas, and cleanup.
- **Performance**: CDN-backed delivery, optional responsive sizes, optimized images.
- **Security**: Public vs private asset policies, MIME validation, optional signed URLs.

## 3) Proposed Design

- **Disk Indirection**
  - Add a dedicated logical disk alias `uploads` and reference it everywhere.
  - Fallback to `public` locally for zero-friction development.

```php
// config/filesystems.php
return [
    // ...
    'default' => env('FILESYSTEM_DISK', 'public'),

    'uploads_disk' => env('UPLOADS_DISK', 'uploads'), // custom indirection key

    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'uploads' => [ // local default
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        // 'uploads' => [ // example S3 config
        //     'driver' => 's3',
        //     'key' => env('AWS_ACCESS_KEY_ID'),
        //     'secret' => env('AWS_SECRET_ACCESS_KEY'),
        //     'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
        //     'bucket' => env('AWS_BUCKET'),
        //     'url' => env('AWS_URL'), // or CDN URL
        //     'endpoint' => env('AWS_ENDPOINT'), // e.g., R2/DO Spaces
        //     'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        //     'visibility' => 'public',
        // ],
    ],
];
```

```env
# .env
UPLOADS_DISK=uploads
# For S3-compatible providers
# AWS_ACCESS_KEY_ID=...
# AWS_SECRET_ACCESS_KEY=...
# AWS_DEFAULT_REGION=ap-southeast-1
# AWS_BUCKET=allan-portfolio-uploads
# AWS_URL=https://cdn.example.com   # optional CDN
# AWS_ENDPOINT=                     # for R2/Spaces/B2
# AWS_USE_PATH_STYLE_ENDPOINT=false
```

- **Code Usage**
  - Filament components: `->disk(config('filesystems.uploads_disk', 'uploads'))`
  - Controllers: `Storage::disk(config('filesystems.uploads_disk', 'uploads'))->url($path)`

- **Tenant-aware Paths**
  - Compute per-tenant prefix: `tenants/{tenant_id}/<module>/...`
  - Use closures in `->directory(fn () => tenantPrefix('<module>/media'))`.

- **Public vs Private**
  - Public assets (logos, project screenshots) remain public + CDN.
  - Future private assets: store with private ACL; expose via short-lived signed URLs.

- **Optimization Pipeline**
  - Server-side: `spatie/image` and `spatie/image-optimizer` on upload.
  - Optional: generate responsive sizes (e.g., 320/640/1280) and store with suffixes.
  - CDN in front of bucket for global caching.

- **Lifecycle & Cleanup**
  - Bucket lifecycle rules: transition to infrequent access, expire orphaned temp files.
  - Model observers to delete replaced/removed files and avoid orphans.

## 4) Migration Plan

- **Phase A: Indirection (Local only)**
  - Introduce `uploads` disk; switch all FileUpload and controllers to use it.
  - No behavior change locally; everything continues to work via `/storage`.

- **Phase B: S3 Switch (Staging)**
  - Point `uploads` disk to S3-compatible storage.
  - Verify uploads via Filament, confirm URLs and CDN headers.

- **Phase C: Data Migration (Production)**
  - Sync existing files: `storage/app/public/` → `s3://<bucket>/<prefix>/` (preserve structure).
  - If prefixes remain identical, DB paths remain valid.

- **Phase D: Enhancements**
  - Add responsive variants & optimization, configure CDN cache policies.
  - Implement observers for cleanup, add quotas per tenant.

## 5) Touch Points to Update

- **Filament Resources** (directories shown for reference; switch `->disk()` to the new indirection):
  - `App/Filament/Resources/SettingResource.php`
    - `settings/logo`, `settings/favicon`, `settings/profile`
  - `App/Filament/Resources/ExperienceResource.php`
    - `experiences/media`
  - `App/Filament/Resources/EducationResource.php`
    - `educations/media`
  - `App/Filament/Resources/ProjectResource.php`
    - `projects/media`
  - `App/Filament/Resources/CertificationResource.php`
    - `certifications/media`
  - `App/Filament/Pages/SiteAppearanceSettings.php`
    - `settings/logo`, `settings/favicon`

- **Controllers**
  - `App/Http/Controllers/Api/AppearanceController.php`
  - `App/Http/Controllers/Api/SettingController.php`
  - Replace `Storage::disk('public')` with `Storage::disk(config('filesystems.uploads_disk','uploads'))`.

## 6) API & Frontend Impact

- **API**: No schema change; only URL origins change (now served via CDN/bucket).
- **Next.js**: No change if consuming absolute URLs. Can add caching hints and image domain allowlist in `next.config.js`.

## 7) Risks & Mitigations

- **Mixed content / CORS**: Set correct CORS on bucket/CDN; always use HTTPS urls in production.
- **Cache invalidation**: Use content-hashed filenames or purge CDN on replace; otherwise append cache-busting query.
- **Costs**: Enable lifecycle policies and avoid multiple redundant variants.

## 8) Checklist

- **Config**
  - [ ] Add `uploads` disk indirection & env vars
  - [ ] Update Filament `->disk(...)` usages
  - [ ] Update controllers `Storage::disk(...)->url()` usages

- **Cloud**
  - [ ] Provision bucket & CDN
  - [ ] Configure CORS & cache headers
  - [ ] Add lifecycle rules

- **Data**
  - [ ] Sync historical files to bucket
  - [ ] Validate URLs in the app & API

- **Quality**
  - [ ] Add observers for cleanup
  - [ ] Optional: image optimization + responsive sizes

---

This plan keeps local DX intact while enabling a clean, low-risk migration to cloud storage and a scalable multi-tenant future.
