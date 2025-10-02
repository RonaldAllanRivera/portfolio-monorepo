# Deployment Plan

## Overview
- Admin (Laravel) runs on Hostinger at `https://allanwebdesign.com`.
- Public site (Next.js) runs on Vercel at `https://ronaldallanrivera.com`.

## Registrar & Cost Decision
- Prefer buying/renewing domains on Hostinger.
  - Typically includes a free domain for the first year with hosting plans.
  - Renewal for .com is usually ~$10–15/year.
- Avoid buying domains on Vercel (convenient but generally ~$15–20/year, no free year).

## Domains
- `allanwebdesign.com`
  - Registrar: Hostinger
  - Hosting: Hostinger (Laravel admin/API)
- `ronaldallanrivera.com`
  - Registrar: Hostinger (DNS also managed on Hostinger)
  - Hosting: Vercel (Next.js)

## DNS Records (configure in Hostinger DNS)

### allanwebdesign.com → Hostinger (Laravel)
- A @ → <HOSTINGER_IP>
- CNAME www → @  (or A www → <HOSTINGER_IP>)

### ronaldallanrivera.com → Vercel (Next.js)
- A @ → 76.76.21.21  (Vercel apex IP)
- CNAME www → cname.vercel-dns.com
- TXT _vercel-verification → <TOKEN_FROM_VERCEL>  (added by Vercel after domain is connected)

Notes:
- Keep DNS on Hostinger for centralized management and lower cost.
- Alternatively, you can switch nameservers to Vercel DNS, but this plan assumes Hostinger DNS.

## Vercel Project Setup (apps/web-next)
1. In Vercel, open the project for `apps/web-next`.
2. Go to Settings → Domains → Add `ronaldallanrivera.com`.
3. Choose “Configure DNS records manually”.
4. Add A, CNAME, and TXT records in Hostinger as listed above.
5. Wait for DNS propagation, then click “Verify”.
6. Optionally add `www.ronaldallanrivera.com` as a redirect to apex.

## Laravel (Hostinger)
- APP_URL: `https://allanwebdesign.com`
- Storage: `public` disk with symlink.
- CORS (`config/cors.php`): allow `https://ronaldallanrivera.com`.
- Ensure document root is set to Laravel `public/`.

## Namecheap Laravel Deployment Tutorial (Git Subtree Method)

### Overview
Deploy Laravel app from monorepo subdirectory (`apps/admin-laravel`) to Namecheap shared hosting using Git subtree.

### Prerequisites
- Git repository at `E:\laragon\www\allanwebdesign.com.2025\`
- Laravel app in `apps/admin-laravel/` subdirectory
- Namecheap cPanel with SSH access
- Git installed on Namecheap server

### Step 1: Set up Git subtree (Local)

#### Initial setup
```bash
# Navigate to your git root
cd E:\laragon\www\allanwebdesign.com.2025

# Split the admin-laravel subdirectory into a separate branch
git subtree split --prefix=apps/admin-laravel --branch=laravel-deploy

# Push this branch to your repository
git push origin laravel-deploy
```

#### For subsequent deployments
```bash
# After making changes to your Laravel app
git add apps/admin-laravel/
git commit -m "Update Laravel app"

# Deploy to Namecheap
git subtree push --prefix=apps/admin-laravel origin laravel-deploy
```

### Step 2: Set up Namecheap server

#### Connect via SSH
```bash
ssh username@yourdomain.com
```

#### Clone the repository
```bash
# Navigate to your web directory (usually public_html or a subdomain folder)
cd public_html

# Clone the laravel-deploy branch
git clone -b laravel-deploy your-repo-url .
```

#### Install dependencies
```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Create storage symlink
php artisan storage:link

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/*.log
```

#### Configure environment
```bash
# Copy and configure .env file
cp .env.example .env
nano .env
```

Set these values:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

#### Set up cron jobs (if needed)
```bash
crontab -e
```

Add Laravel scheduler:
```
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### Step 3: Update deployment workflow

#### To deploy new changes
```bash
# On your local machine (after committing changes)
git subtree push --prefix=apps/admin-laravel origin laravel-deploy

# On Namecheap server
git pull origin laravel-deploy
composer install --no-dev --optimize-autoloader
php artisan migrate
```

#### Optional: Create a deployment script
Create `deploy.sh` on your server:
```bash
#!/bin/bash
cd /path/to/your/project
git pull origin laravel-deploy
composer install --no-dev --optimize-autoloader
php artisan migrate
php artisan storage:link
chmod -R 755 storage bootstrap/cache
```

Make it executable:
```bash
chmod +x deploy.sh
```

### Troubleshooting

#### If subtree split fails
```bash
# Ensure you have committed all changes
git status

# If you have uncommitted changes, commit them first
git add apps/admin-laravel/
git commit -m "Prepare for deployment"

# Try subtree split again
git subtree split --prefix=apps/admin-laravel --branch=laravel-deploy
```

#### If composer install fails
```bash
# Increase PHP memory limit
php -d memory_limit=-1 composer install --no-dev --optimize-autoloader
```

#### If permissions issues persist
```bash
# Fix ownership (if you have sudo access)
sudo chown -R user:user .
# Replace 'user' with your cPanel username
```

### Benefits of this approach
- Keeps monorepo structure intact locally
- Clean separation for deployment
- Easy to update with single command
- No complex server-side setup
- Version control for deployments

## Next.js (Vercel)
- Environment: `NEXT_PUBLIC_API_BASE_URL=https://allanwebdesign.com`
- Use caching/revalidation to reduce API calls.

## Checklist Before Go-Live
- [ ] Domains purchased on Hostinger and set to auto-renew.
- [ ] DNS records match the above tables and have propagated.
- [ ] Laravel CORS configured for `ronaldallanrivera.com`.
- [ ] Vercel domain verified and set as primary.
- [ ] Health-check both sites over HTTPS.
