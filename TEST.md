# API Testing with Postman

This document provides instructions for testing the Portfolio API endpoints using Postman.

## Postman Collection

Below is a Postman collection that you can import to test all available API endpoints.

### Collection JSON

```json
{
  "info": {
    "name": "Portfolio API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Experiences",
      "item": [
        {
          "name": "List Experiences",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Accept",
                "value": "application/json"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/v1/experiences",
              "host": ["{{base_url}}"],
              "path": ["api", "v1", "experiences"]
            }
          }
        },
        {
          "name": "Get Current Experience",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Accept",
                "value": "application/json"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/v1/experiences/current",
              "host": ["{{base_url}}"],
              "path": ["api", "v1", "experiences", "current"]
            }
          }
        },
        {
          "name": "Get Experience by ID",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Accept",
                "value": "application/json"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/v1/experiences/1",
              "host": ["{{base_url}}"],
              "path": ["api", "v1", "experiences", "1"],
              "description": "Replace '1' with the actual experience ID"
            }
          }
        }
      ]
    },
    {
      "name": "Educations",
      "item": [
        {
          "name": "List Educations",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Accept",
                "value": "application/json"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/v1/educations",
              "host": ["{{base_url}}"],
              "path": ["api", "v1", "educations"]
            }
          }
        },
        {
          "name": "Get Current Education",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Accept",
                "value": "application/json"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/v1/educations/current",
              "host": ["{{base_url}}"],
              "path": ["api", "v1", "educations", "current"]
            }
          }
        },
        {
          "name": "Get Education by ID",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Accept",
                "value": "application/json"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/v1/educations/1",
              "host": ["{{base_url}}"],
              "path": ["api", "v1", "educations", "1"],
              "description": "Replace '1' with the actual education ID"
            }
          }
        }
      ]
    },
    {
      "name": "Certifications",
      "item": [
        {
          "name": "List Certifications",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Accept",
                "value": "application/json"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/v1/certifications",
              "host": ["{{base_url}}"],
              "path": ["api", "v1", "certifications"]
            }
          }
        }
      ]
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://127.0.0.1:8000",
      "type": "string"
    }
  ]
}
```

## How to Use This Collection

### 1. Import the Collection into Postman
1. Open Postman
2. Click "Import" in the top-left corner
3. Select "Raw text" and paste the JSON above
4. Click "Continue" and then "Import"

### 2. Set Up Environment Variables
1. In Postman, click on the "Environments" tab
2. Create a new environment (e.g., "Local Development")
3. Add a variable:
   - Variable: `base_url`
   - Initial value: `http://admin.allanwebdesign.com.2025.test` (or your local URL)

### 3. Using the Collection
- The collection is organized into folders (Experiences, Educations, Certifications)
- Each endpoint includes the necessary headers
- The base URL is set as a variable for easy switching between environments

## Available Endpoints

### Experiences
- `GET /api/v1/experiences` - List all experiences
- `GET /api/v1/experiences/current` - Get current experience
- `GET /api/v1/experiences/{id}` - Get experience by ID

### Educations
- `GET /api/v1/educations` - List all educations
- `GET /api/v1/educations/current` - Get current education
- `GET /api/v1/educations/{id}` - Get education by ID

### Certifications
- `GET /api/v1/certifications` - List all certifications

## Notes
- Replace the `base_url` in the environment variables if your local development URL is different
- For endpoints that require authentication, you may need to add your authentication token to the headers
- The ID in the URL (e.g., `/experiences/1`) should be replaced with actual IDs from your database

## CDN Images and CORS (Cloudflare R2 + CDN)

- **[symptom]** Browser shows CORS errors like: No 'Access-Control-Allow-Origin' header when loading `https://cdn.allanwebdesign.com/...` from your local/admin origins.
- **[cause]** Your CDN responses don’t include CORS headers that allow your site’s origins (admin or Next.js dev).

### Option A — Configure R2 Bucket CORS (preferred)
1. Cloudflare Dashboard → R2 → Your bucket → Settings → CORS.
2. Set a policy similar to:

```json
[
  {
    "AllowedOrigins": [
      "http://admin.allanwebdesign.com.2025.test",
      "http://localhost:3000",
      "https://allanwebdesign.com",
      "https://www.allanwebdesign.com"
    ],
    "AllowedMethods": ["GET", "HEAD", "OPTIONS"],
    "AllowedHeaders": ["*"],
    "ExposeHeaders": ["ETag", "Content-Length", "Content-Type"],
    "MaxAgeSeconds": 86400
  }
]
```

3. Purge CDN cache for changed objects so new headers are served.

### Option B — Cloudflare Response Header Rule (works without R2 CORS)
1. Cloudflare → Rules → Transform Rules → HTTP Response Header Modification → Create rule.
2. Match: Hostname equals `cdn.allanwebdesign.com`.
3. Set headers:
   - `Access-Control-Allow-Origin: http://admin.allanwebdesign.com.2025.test`
   - `Access-Control-Allow-Methods: GET, HEAD, OPTIONS`
   - `Access-Control-Allow-Headers: *`
   - `Access-Control-Expose-Headers: ETag, Content-Length, Content-Type`
   - `Access-Control-Max-Age: 86400`
4. Add `http://localhost:3000` too if your Next.js dev site pulls images from the CDN.
5. Purge cache.

### Verify CORS headers
- PowerShell:

```powershell
iwr -Method Head -Uri "https://cdn.allanwebdesign.com/settings/logo/test.png" -Headers @{ Origin = "http://admin.allanwebdesign.com.2025.test" } -UseBasicParsing | Select-Object Headers
```

- curl:

```bash
curl -I -H "Origin: http://admin.allanwebdesign.com.2025.test" https://cdn.allanwebdesign.com/settings/logo/test.png
```

Expect `Access-Control-Allow-Origin` in the response.

### Next.js image configuration (CDN-only)
`apps/web-next/next.config.ts` should allow only your CDN host:

```ts
// next.config.ts
import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  images: {
    remotePatterns: [
      { protocol: 'https', hostname: process.env.NEXT_PUBLIC_CDN_HOST as string, pathname: '/**' },
    ],
  },
};

export default nextConfig;
```

### Tips
- **HTTPS**: Serve CDN over HTTPS to avoid mixed content issues.
- **Origins**: List all dev/prod origins that will display the images.
- **Caching**: CDN caching can mask header changes—purge after updates.
- **Lockdown**: For quick dev you can temporarily use `*` for `Access-Control-Allow-Origin`, but restrict in production.

## Make the admin domain resolve locally

If you want to use pretty local domains like `http://admin.allanwebdesign.com.2025.test` and `http://allanwebdesign.com.2025.test` on Windows:

### 1) Map hosts to 127.0.0.1 (Windows)
Edit `C:\Windows\System32\drivers\etc\hosts` (as Administrator) and add:

```text
127.0.0.1 admin.allanwebdesign.com.2025.test
127.0.0.1 allanwebdesign.com.2025.test
```

### 2) Serve Laravel on that host
- Laragon (recommended): enable Virtual Hosts and create a site for `admin.allanwebdesign.com.2025.test` pointing to `apps/admin-laravel/public`.
- Or use the builtin server with host binding:

```bash
php artisan serve --host=admin.allanwebdesign.com.2025.test --port=8000
```

### 3) .env settings
- `apps/admin-laravel/.env`:

```env
APP_URL=http://admin.allanwebdesign.com.2025.test
PUBLIC_SITE_URL=http://allanwebdesign.com.2025.test
```

- `apps/web-next/.env.local`:

```env
NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test
NEXT_PUBLIC_CDN_HOST=cdn.allanwebdesign.com
```

### 4) Restart and clear caches
```bash
php artisan config:clear
php artisan optimize:clear
```
Restart `npm run dev` for Next.js if you changed `.env.local` or `next.config.ts`.

### 5) Verify
- Browser: open `http://admin.allanwebdesign.com.2025.test:8000` (or via Laragon vhost without port).
- PowerShell (host resolution):

```powershell
ping admin.allanwebdesign.com.2025.test
```

- HTTP check:

```powershell
iwr http://admin.allanwebdesign.com.2025.test:8000 -UseBasicParsing | Select-Object StatusCode,Headers
```

If it doesn’t resolve, re-check the hosts file and that the server is bound to the hostname.
