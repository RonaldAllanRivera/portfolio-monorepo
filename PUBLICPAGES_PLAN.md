# Public Pages Implementation Plan

## Overview
This document outlines the strategy and implementation details for the public-facing pages of the portfolio website, including file handling, performance considerations, and deployment strategies.

## Core Pages

### 1. Homepage (`/`)
- **Components**:
  - Hero section with call-to-action
  - Featured projects carousel
  - Skills/technologies showcase
  - Recent blog posts/updates
  - Contact CTA

### 2. Portfolio/Projects (`/projects`)
- **Features**:
  - Filterable grid layout
  - Project detail pages
  - Technology tags
  - Live demos (where applicable)
  - GitHub/Case study links

### 3. Certifications (`/certifications`)
- **Features**:
  - Grid/list view toggle
  - PDF preview functionality (already implemented in admin)
  - Filter by category/issuer
  - Issue/expiry dates

#### Netflix-style Modal View Plan (Frontend)
- **Architecture**: Reuse current template system; render in Classic template first under section key `cf`.
- **Data**: Use existing `/api/v1/certifications` endpoint; include `duration` (hours/minutes/label), `total_minutes`, `skills[]`, `media[]`.
- **Icons**: Map title/issuer to Iconify icons.
- **Components (client)**:
  - `CertificationCard`: clickable tile with Iconify logo and title overlay.
  - `CertificationsRail`: horizontal scrollable rail with snap, arrow controls.
  - `CertificationModal`: full-screen Tailwind modal showing all certification details.
  - `CertificationsNetflix` (container): manages open/close state for modal.
- **Steps**:
  1) Add `@iconify/react` to `apps/web-next`.
  2) Create `templates/shared/certifications-icons.ts` icon mapping utility.
  3) Build the three client components + container.
  4) Wire Classic template to render `CertificationsNetflix` instead of `CertificationsList` when `sec='cf'`.
  5) Test on `/certifications` and iterate styling to match Netflix look-and-feel.

### 4. About (`/about`)
- **Sections**:
  - Professional bio
  - Work experience timeline
  - Education background
  - Technical skills matrix

## File Handling Strategy

### Media Management
```php
// config/filesystems.php
return [
    'default' => env('FILESYSTEM_DISK', 'public'),
    
    // Custom disk indirection for uploads
    'uploads_disk' => env('UPLOADS_DISK', 'uploads'),

    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'uploads' => [ // Local development
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'r2' => [ // Production (Cloudflare R2)
            'driver' => 's3',
            // ... existing R2 configuration
        ]
    ]
];
```

### Frontend Implementation
- Use Next.js `next/image` for optimized image loading
- Implement lazy loading for below-the-fold content
- Use WebP format with fallbacks
- Implement responsive images with `srcset`

## Performance Optimization

### 1. Image Optimization
- Implement responsive images
- Use modern formats (WebP/AVIF)
- Lazy loading for images
- Blur-up placeholders

### 2. Code Splitting
- Dynamic imports for heavy components
- Route-based code splitting
- Critical CSS inlining

### 3. Caching Strategy
- Service Worker for offline support
- CDN caching for static assets
- Stale-while-revalidate pattern

## SEO & Accessibility

### 1. Metadata
- Dynamic OpenGraph tags
- JSON-LD structured data
- Sitemap generation
- RSS feed for blog

### 2. Accessibility
- WCAG 2.1 AA compliance
- Keyboard navigation
- Screen reader support
- Reduced motion preferences

## Development Workflow

### 1. Environment Variables
```env
# Local development
UPLOADS_DISK=public
NEXT_PUBLIC_MEDIA_URL=/storage

# Production
UPLOADS_DISK=r2
NEXT_PUBLIC_MEDIA_URL=https://cdn.allanwebdesign.com
```

### 2. Testing Strategy
- Unit tests for components
- Integration tests for critical paths
- Visual regression testing
- Performance budgets

## Deployment

### 1. Staging
- Preview deployments for PRs
- Environment-specific configurations
- Automated testing pipeline

### 2. Production
- Blue-green deployment
- Automated rollback
- Performance monitoring
- Error tracking

## Future Enhancements

### 1. Interactive Elements
- Dark/light theme toggle
- Text size adjustments
- High contrast mode

### 2. Performance
- Edge functions for personalization
- Image CDN transformations
- Edge caching

### 3. Analytics
- Privacy-focused analytics
- Performance metrics
- User interaction tracking

## Implementation Notes

1. **Progressive Enhancement**:
   - Core content available without JavaScript
   - Enhanced experience with JavaScript
   - Graceful degradation

2. **Performance Budgets**:
   - Max bundle size: 100KB (gzipped)
   - Max image size: 200KB
   - Time to Interactive: < 3s

3. **Browser Support**:
   - Modern browsers (last 2 versions)
   - Progressive enhancement for older browsers
   - Feature detection for modern APIs

## Timeline

### Phase 1: Foundation (Week 1-2)
- [ ] Set up Next.js project structure
- [ ] Implement core UI components
- [ ] Set up API integration

### Phase 2: Core Features (Week 3-4)
- [ ] Implement main pages
- [ ] Set up file uploads
- [ ] Implement search/filter

### Phase 3: Polish (Week 5-6)
- [ ] Performance optimization
- [ ] Accessibility improvements
- [ ] Cross-browser testing

### Phase 4: Launch (Week 7)
- [ ] Final testing
- [ ] Performance audit
- [ ] Go live
