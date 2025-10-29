# Deployment Guide for Salon Management Pro

## 🎯 Overview

This guide covers deploying the Salon Management application on **Render** (free tier) for testing using **SQLite**, while maintaining **MySQL** for local development.

---

## 📋 Environment Setup

### Local Development (MySQL)
- Uses **MySQL** via XAMPP/phpMyAdmin
- Database: `salon_management`
- Connection: `mysql`

### Production/Render (SQLite)
- Uses **SQLite** (file-based database)
- Database file: `database/database.sqlite`
- Connection: `sqlite`
- **Free tier** - no external database needed

---

## 🚀 Render Deployment Steps

### 1. Generate APP_KEY

Run locally to generate your application key:

```bash
php artisan key:generate --show
```

**Output example:**
```
base64:abcd1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ=
```

**Save this key!** You'll need it for Render environment variables.

---

### 2. Push to GitHub

Make sure all files are committed:

```bash
git add .
git commit -m "Configure SQLite for Render deployment"
git push origin main
```

---

### 3. Deploy on Render

#### Option A: Using Blueprint (Recommended)

1. Go to [Render Dashboard](https://dashboard.render.com/)
2. Click **"New +"** → **"Blueprint"**
3. Connect your GitHub repository: `https://github.com/NEEL6202/salon-management`
4. Render will detect `render.yaml` automatically
5. Click **"Apply"**

#### Option B: Manual Setup

1. Go to [Render Dashboard](https://dashboard.render.com/)
2. Click **"New +"** → **"Web Service"**
3. Connect your GitHub repository
4. Configure:
   - **Name**: `salon-management`
   - **Runtime**: **Docker**
   - **Branch**: `main`
   - **Build Command**: `./build.sh`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

---

### 4. Configure Environment Variables

In Render Dashboard → Your Service → **Environment** tab, add these:

```env
APP_NAME=Salon Management Pro
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_GENERATED_KEY_FROM_STEP_1
APP_URL=https://your-app-name.onrender.com

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
LOG_LEVEL=error
```

**Important:** Replace:
- `YOUR_GENERATED_KEY_FROM_STEP_1` with the actual key from step 1
- `your-app-name.onrender.com` with your actual Render URL

---

### 5. Deploy!

- Click **"Manual Deploy"** → **"Deploy latest commit"**
- Wait for build to complete (5-10 minutes)
- Check **Logs** for any errors

---

## ✅ Post-Deployment

### Access Your Application

Your app will be available at:
```
https://your-app-name.onrender.com
```

### Create Super Admin User

1. Go to Render Dashboard → Your Service → **Shell**
2. Run:

```bash
php artisan tinker
```

3. Create admin user:

```php
$user = new App\Models\User();
$user->name = 'Super Admin';
$user->email = 'admin@salonpro.com';
$user->password = Hash::make('Admin@123');
$user->save();
$user->assignRole('super_admin');
exit
```

4. Now login at: `https://your-app-name.onrender.com/login`
   - Email: `admin@salonpro.com`
   - Password: `Admin@123`

---

## 🔄 Database Differences

### Local (MySQL)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=salon_management
DB_USERNAME=root
DB_PASSWORD=
```

### Render (SQLite)
```env
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

The application automatically uses the correct database based on environment.

---

## ⚠️ Known Limitations (Render Free Tier)

1. **Service Spin Down**: App sleeps after 15 minutes of inactivity
   - First request after sleep takes 30-60 seconds
   
2. **SQLite Limitations**:
   - File-based database (limited concurrent writes)
   - Suitable for testing, not heavy production use
   
3. **Storage**: 
   - Uploaded files are ephemeral (lost on redeploy)
   - Use external storage (S3, Cloudinary) for production

---

## 🔧 Troubleshooting

### Error: "Database file not found"

**Solution:**
```bash
# In Render Shell
touch database/database.sqlite
chmod 775 database/database.sqlite
php artisan migrate --force
```

### Error: "APP_KEY not set"

**Solution:**
- Make sure you added the APP_KEY in environment variables
- Key must start with `base64:`

### Error: "Permission denied"

**Solution:**
```bash
# In Render Shell
chmod -R 775 storage bootstrap/cache database
```

### Migrations Not Running

**Solution:**
```bash
# In Render Shell
php artisan migrate:fresh --force --seed
```

---

## 📊 Monitoring

### Check Application Status

```bash
# In Render Shell
php artisan --version
php artisan migrate:status
php artisan config:show database
```

### View Logs

- Render Dashboard → Your Service → **Logs** tab
- Real-time log streaming

---

## 🔐 Security Checklist

Before going live:

- ✅ `APP_DEBUG=false` (Never true in production!)
- ✅ `APP_ENV=production`
- ✅ Unique `APP_KEY` generated
- ✅ Change default admin password
- ✅ Review all environment variables
- ✅ Enable HTTPS (automatic on Render)

---

## 🎓 Future Production Deployment

When ready for real production:

1. **Upgrade Database**:
   - Use Render PostgreSQL (paid plan)
   - Or external: PlanetScale, AWS RDS, etc.

2. **Storage**:
   - Configure S3 for file uploads
   - Update `.env`: `FILESYSTEM_DISK=s3`

3. **Performance**:
   - Enable Redis for caching
   - Use queue workers for background jobs

4. **Monitoring**:
   - Setup error tracking (Sentry, Bugsnag)
   - Application monitoring (New Relic, DataDog)

---

## 📞 Support

For issues:
1. Check Render logs first
2. Review this deployment guide
3. Verify environment variables
4. Test locally with SQLite to reproduce

---

## ✨ Quick Commands Reference

```bash
# Generate APP_KEY
php artisan key:generate --show

# Create database file
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Clear caches
php artisan optimize:clear

# Cache config
php artisan optimize

# Create admin user
php artisan tinker
# Then: [user creation code]

# Check migration status
php artisan migrate:status
```

---

**Happy Deploying! 🚀**
