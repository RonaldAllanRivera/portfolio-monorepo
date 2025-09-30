# Experience Module - Setup Commands

## Overview
Complete CRUD system for managing work experiences with Filament 4 admin panel and REST API for Next.js frontend.

## Features Created
- ✅ Database migration with all LinkedIn-like fields
- ✅ Experience model with relationships and scopes
- ✅ Filament Resource with rich form builder
- ✅ REST API endpoints for Next.js consumption
- ✅ Media upload support (local storage)
- ✅ Skills tagging system
- ✅ Sortable/reorderable experiences
- ✅ Current position tracking

---

## Step 1: Run Migration

```bash
cd e:/laragon/www/allanwebdesign.com.2025/apps/admin-laravel
php artisan migrate
```

This creates the `experiences` table with fields:
- Basic info (title, employment_type, company_name)
- Dates (start_date, end_date, is_current)
- Location (location, location_type)
- Content (description, profile_headline)
- Skills (JSON array)
- Media (JSON array for file paths)
- Sorting (sort_order)

---

## Step 2: Clear Caches

```bash
php artisan optimize:clear
```

This clears:
- Route cache
- Config cache
- View cache
- Application cache

---

## Step 3: Verify API Routes

```bash
php artisan route:list --path=api
```

You should see these endpoints:
- `GET /api/v1/experiences` - Get all experiences
- `GET /api/v1/experiences/current` - Get current positions only
- `GET /api/v1/experiences/{id}` - Get single experience

---

## Step 4: Access Filament Admin

1. **Login URL**: `http://admin.allanwebdesign.com.2025.test/admin`
2. **Credentials**: 
   - Email: `jaeron.rivera@gmail.com`
   - Password: `123456`

3. **Navigate to Experiences**:
   - Look for "Portfolio" group in sidebar
   - Click "Experiences" (briefcase icon)

---

## Step 5: Test API Endpoints

### Get All Experiences
```bash
curl http://admin.allanwebdesign.com.2025.test/api/v1/experiences
```

### Get Current Experiences Only
```bash
curl http://admin.allanwebdesign.com.2025.test/api/v1/experiences/current
```

### Get Single Experience
```bash
curl http://admin.allanwebdesign.com.2025.test/api/v1/experiences/1
```

---

## API Response Format

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Senior Full Stack Developer",
      "employment_type": "Full-time",
      "company_name": "Tech Corp Inc.",
      "is_current": true,
      "start_date": "2023-01-15",
      "start_date_formatted": "Jan 2023",
      "end_date": null,
      "end_date_formatted": "Present",
      "duration": "1 year 8 months",
      "location": "Manila, Philippines",
      "location_type": "Remote",
      "description": "<p>Responsibilities and achievements...</p>",
      "profile_headline": "Building scalable web applications",
      "skills": ["Laravel", "React", "Next.js", "TypeScript"],
      "media": [
        {
          "path": "experiences/media/screenshot.jpg",
          "url": "/storage/experiences/media/screenshot.jpg",
          "full_url": "http://admin.allanwebdesign.com.2025.test/storage/experiences/media/screenshot.jpg"
        }
      ],
      "sort_order": 0,
      "created_at": "2025-09-30T08:00:00+08:00",
      "updated_at": "2025-09-30T08:00:00+08:00"
    }
  ]
}
```

---

## Filament Features

### Form Sections
1. **Basic Information**: Title, Employment Type, Company
2. **Duration**: Start/End dates with "Currently working" toggle
3. **Location**: Location and type (On-site/Remote/Hybrid)
4. **Description & Details**: Rich text editor for responsibilities
5. **Skills**: Tag input with suggestions
6. **Media**: Image uploader with editor (max 5MB per file)
7. **Display Order**: Manual sorting control

### Table Features
- Search by title and company
- Filter by employment type, current status, location type
- Sort by any column
- Drag-and-drop reordering
- Bulk delete
- Badge colors for employment types
- Navigation badge showing total count

---

## File Structure Created

```
apps/admin-laravel/
├── app/
│   ├── Filament/
│   │   └── Resources/
│   │       └── ExperienceResource.php (Complete CRUD UI)
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── ExperienceController.php (REST API)
│   └── Models/
│       ├── Experience.php (Model with relationships)
│       └── User.php (Updated with experiences relationship)
├── database/
│   └── migrations/
│       └── 2025_09_30_000001_create_experiences_table.php
├── routes/
│   └── api.php (API routes)
└── bootstrap/
    └── app.php (Updated to register API routes)
```

---

## Next Steps

### For Admin Panel
1. Add more experiences via Filament
2. Upload media files
3. Tag skills
4. Reorder using drag-and-drop

### For Next.js Frontend (Later)
1. Create API client in `apps/web-next`
2. Fetch experiences from `/api/v1/experiences`
3. Display in portfolio timeline
4. Show current positions separately
5. Render rich text descriptions
6. Display media gallery

---

## Environment Variables

### Laravel (.env)
```env
APP_URL=http://admin.allanwebdesign.com.2025.test
FILESYSTEM_DISK=public
```

### Next.js (.env.local) - For Later
```env
NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test
```

---

## Production Deployment Notes

### Hostinger (Laravel)
```bash
# After deployment
composer install --no-dev -o
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### CORS Configuration
Update `config/cors.php` to allow Next.js domain:
```php
'allowed_origins' => [
    'https://www.allanwebdesign.com',
    'https://allanwebdesign.com',
],
```

---

## Troubleshooting

### Issue: 404 on API routes
**Solution**: 
```bash
php artisan route:clear
php artisan optimize:clear
```

### Issue: Media not displaying
**Solution**:
```bash
php artisan storage:link
# Verify symlink exists: public/storage -> storage/app/public
```

### Issue: Filament not showing Experiences
**Solution**:
```bash
php artisan filament:optimize
php artisan optimize:clear
```

---

## Summary

✅ **Migration**: Creates experiences table with all fields
✅ **Model**: Experience with User relationship, scopes, casts
✅ **Filament**: Full CRUD with rich forms, filters, sorting
✅ **API**: 3 endpoints returning formatted JSON
✅ **Media**: File upload to public disk with URL generation
✅ **Skills**: Array storage with tag input UI

**Ready to use!** Start adding experiences via Filament admin panel.
