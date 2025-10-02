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

## SiteGround Laravel Deployment Tutorial (Git Subtree Method)

### Overview
Deploy Laravel app from monorepo subdirectory (`apps/admin-laravel`) to SiteGround shared hosting using Git subtree.

### Prerequisites
- Git repository at `E:\laragon\www\allanwebdesign.com.2025\`
- Laravel app in `apps/admin-laravel/` subdirectory
- SiteGround cPanel with SSH access
- Git installed on SiteGround server
- Repository: `https://github.com/RonaldAllanRivera/portfolio-monorepo.git`

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

# Deploy to SiteGround
git subtree push --prefix=apps/admin-laravel origin laravel-deploy
```

### Step 2: Set up SiteGround server

#### Connect via SSH
```bash
ssh username@yourdomain.com
```

#### Navigate to public_html
```bash
cd ~/www/yourdomain.com/public_html
```

#### Clone the repository
```bash
# Initialize git repo
git init

# Add remote
git remote add origin https://github.com/RonaldAllanRivera/portfolio-monorepo.git

# Fetch the laravel-deploy branch
git fetch origin laravel-deploy

# Switch to the branch
git checkout laravel-deploy
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

# On SiteGround
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

#### If public_html is not empty
If `public_html` contains existing files:
```bash
# Option 1: Clone into subdirectory
mkdir laravel-admin
cd laravel-admin
git init
git remote add origin https://github.com/RonaldAllanRivera/portfolio-monorepo.git
git fetch origin laravel-deploy
git checkout laravel-deploy

# Then set document root to point to public_html/laravel-admin/public in cPanel

# Option 2: Backup and replace
mv existing_folder ../
git init
git remote add origin https://github.com/RonaldAllanRivera/portfolio-monorepo.git
git fetch origin laravel-deploy
git checkout laravel-deploy
```

### Benefits of this approach
- Keeps monorepo structure intact locally
- Clean separation for deployment
- Easy to update with single command
- Works with SiteGround's SSH access
- Version control for deployments

## Hostinger Laravel Deployment Tutorial (Manual FTP Method)

### Overview
Deploy Laravel app from monorepo subdirectory (`apps/admin-laravel`) to Hostinger shared hosting using manual FTP upload.

### Prerequisites
- FTP client (FileZilla, WinSCP, etc.)
- Hostinger cPanel credentials
- Laravel app in `apps/admin-laravel/` subdirectory
- Domain or subdomain configured in Hostinger

### Step 1: Create Domain/Subdomain in Hostinger

#### For main domain deployment
1. Go to **cPanel** > **Domains** > **Domains**
2. Ensure your domain is pointed to Hostinger
3. Document root will be: `public_html/`

#### For subdomain deployment (e.g., admin.allanwebdesign.com)
1. Go to **cPanel** > **Domains** > **Subdomains**
2. Enter subdomain name (e.g., `admin`)
3. Select your domain
4. Document root will be: `public_html/admin/`
5. Click **Create**

### Step 2: Prepare Local Files

#### 2.1 Copy Laravel files to deployment folder
```bash
# Create deployment folder
mkdir -p ~/Desktop/laravel-deployment

# Copy Laravel files (excluding unnecessary files)
cp -r apps/admin-laravel/* ~/Desktop/laravel-deployment/
cp apps/admin-laravel/.htaccess ~/Desktop/laravel-deployment/
cp apps/admin-laravel/.env.example ~/Desktop/laravel-deployment/.env
```

#### 2.2 Exclude unnecessary files
Delete these files from `~/Desktop/laravel-deployment/`:
- `.git/`
- `node_modules/`
- `.idea/`
- `vendor/`
- `tests/`
- `README.md`
- `composer.lock`
- `package.json`
- `package-lock.json`
- `webpack.mix.js`
- `tailwind.config.js`
- `vite.config.js`

### Step 3: Configure Environment

#### 3.1 Edit .env file
```bash
# Open .env in text editor
nano ~/Desktop/laravel-deployment/.env
```

Set these values:
```
APP_NAME=AdminPanel
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com  # or https://admin.yourdomain.com for subdomain

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

#### 3.2 Generate APP_KEY
```bash
# If you have PHP locally
cd ~/Desktop/laravel-deployment
php artisan key:generate --env=production
```

### Step 4: Upload via FTP

#### 4.1 Connect to Hostinger
- **Host**: `ftp.yourdomain.com` or your Hostinger FTP server
- **Username**: Your Hostinger cPanel username
- **Password**: Your Hostinger cPanel password
- **Port**: 21

#### 4.2 Upload files
- **For main domain**: Upload contents to `public_html/`
- **For subdomain**: Upload contents to `public_html/admin/`

### Step 5: Create Required Directories

#### 5.1 Create directories via FTP
Create these directories with 755 permissions:
- `storage/app/public/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`
- `storage/logs/`
- `bootstrap/cache/`

#### 5.2 Set directory permissions
Set these permissions via FTP client:
- `storage/` - 755
- `storage/app/` - 755
- `storage/app/public/` - 755
- `storage/framework/` - 755
- `storage/framework/cache/` - 755
- `storage/framework/sessions/` - 755
- `storage/framework/views/` - 755
- `storage/logs/` - 755
- `bootstrap/cache/` - 755

### Step 6: Install Dependencies

#### 6.1 Use Hostinger's Composer (Recommended)
Hostinger provides Composer in cPanel:
1. Go to **cPanel** > **Setup PHP App**
2. Select your domain/subdomain
3. Click **Run Composer Install**

#### 6.2 Alternative: Upload vendor folder
If Composer isn't available:
```bash
# Generate vendor locally
cd apps/admin-laravel
composer install --no-dev --optimize-autoloader

# Copy vendor to deployment folder
cp -r vendor ~/Desktop/laravel-deployment/
```
Then upload the `vendor/` folder via FTP.

### Step 7: Final Configuration

#### 7.1 Create .htaccess file
Create `.htaccess` in the root directory with this content:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### 7.2 Verify index.php
Ensure `index.php` exists and contains:
```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### Step 8: Database Setup

#### 8.1 Create database via cPanel
1. Go to **cPanel** > **MySQL Databases**
2. Create new database
3. Create database user
4. Grant all privileges to user

#### 8.2 Run migrations
Via Hostinger's terminal or cPanel:
```bash
# For main domain
cd public_html
php artisan migrate --force

# For subdomain
cd public_html/admin
php artisan migrate --force
```

#### 8.3 Create admin user
```bash
# For main domain
cd public_html
php artisan make:filament-user

# For subdomain
cd public_html/admin
php artisan make:filament-user
```

### Step 9: Clear Caches

#### 9.1 Clear all caches
```bash
# For main domain
cd public_html
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

# For subdomain
cd public_html/admin
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
```

### Step 10: Test the Application

#### 10.1 Test main site
- **Main domain**: Access `https://yourdomain.com`
- **Subdomain**: Access `https://admin.yourdomain.com`

#### 10.2 Test Filament admin
- **Main domain**: Access `https://yourdomain.com/admin`
- **Subdomain**: Access `https://admin.yourdomain.com/admin`

## Hostinger-Specific Configuration

### Update Filament Panel for Subdomain
If deploying to subdomain, update `app/Providers/Filament/AdminPanelProvider.php`:
```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->colors([
            'primary' => Color::Amber,
        ])
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->pages([
            Pages\Dashboard::class,
        ])
        ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
        ->widgets([
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
        ])
        ->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            ServeSeoMeta::class,
        ])
        ->authMiddleware([
            Authenticate::class,
        ]);
}
```

## Troubleshooting

### Common Issues:
1. **500 Error**: Check `.env` file, permissions, and `storage/logs/laravel.log`
2. **403 Error**: Check directory permissions and `.htaccess`
3. **404 Error**: Verify domain/subdomain points to correct directory
4. **White Screen**: Enable debug mode in `.env` (`APP_DEBUG=true`)
5. **Database Connection**: Verify database credentials in `.env`
6. **Composer Issues**: Use Hostinger's **Setup PHP App** > **Run Composer Install**

### File Permissions Summary:
- Directories: 755
- Files: 644
- `.env`: 600 (restrictive)
- `.htaccess`: 644

### Hostinger-Specific Notes:
- Use **Setup PHP App** in cPanel for PHP version and Composer
- Hostinger typically uses PHP 8.1 or 8.2
- Main domain document root: `public_html/`
- Subdomain document root: `public_html/admin/`
- Database host is typically `localhost`
- FTP host is typically `ftp.yourdomain.com`

### Benefits of this approach:
- No SSH access required
- Works with any FTP client
- Full control over file uploads
- Compatible with Hostinger's shared hosting
- Easy to troubleshoot individual files

## Next.js (Vercel)
- Environment: `NEXT_PUBLIC_API_BASE_URL=https://allanwebdesign.com`
- Use caching/revalidation to reduce API calls.

## Checklist Before Go-Live
- [ ] Domains purchased on Hostinger and set to auto-renew.
- [ ] DNS records match the above tables and have propagated.
- [ ] Laravel CORS configured for `ronaldallanrivera.com`.
- [ ] Vercel domain verified and set as primary.
- [ ] Health-check both sites over HTTPS.
