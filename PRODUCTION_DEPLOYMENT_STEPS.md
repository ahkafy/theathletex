# Production Deployment Steps - Email Fix

## ⚠️ IMPORTANT: Run These Commands on Your Production Server

The email error will persist until you complete ALL steps below on your **production server** (not locally).

---

## Step 1: Connect to Production Server

SSH into your production server:
```bash
ssh your-username@your-production-server.com
```

Navigate to your application directory:
```bash
cd /path/to/your/theathletex/application
```

---

## Step 2: Pull Latest Code from Git

```bash
git pull origin main
```

Expected output: Should show the navbar fix and AWS SES configuration updates.

---

## Step 3: Install AWS SDK for PHP

```bash
composer require aws/aws-sdk-php
```

**If you get "ext-zip" error**, use:
```bash
composer require aws/aws-sdk-php --ignore-platform-req=ext-zip
```

**Verify installation:**
```bash
composer show aws/aws-sdk-php
```

You should see version information (3.356.x or similar).

---

## Step 4: Update Production `.env` File

Edit your production `.env` file:
```bash
nano .env
# OR
vi .env
```

**Find and update these lines:**

### Change Mail Driver from SMTP to SES:
```env
# OLD - Remove or comment out
# MAIL_MAILER=smtp
# MAIL_HOST=email-smtp.eu-north-1.amazonaws.com
# MAIL_PORT=587
# MAIL_USERNAME=
# MAIL_PASSWORD=
# MAIL_ENCRYPTION=tls

# NEW - Add these
MAIL_MAILER=ses
MAIL_FROM_ADDRESS="info@theathletex.net"
MAIL_FROM_NAME="The Athlete X Limited"
```

### Update AWS Credentials:
```env
AWS_ACCESS_KEY_ID=AKIAWV4V32WWV2JBF2E6
AWS_SECRET_ACCESS_KEY=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
AWS_DEFAULT_REGION=eu-north-1
```

**Save the file** (Ctrl+X, then Y, then Enter for nano).

---

## Step 5: Clear All Laravel Caches

Run these commands to clear cached configurations:

```bash
# Clear configuration cache
php artisan config:clear

# Clear application cache
php artisan cache:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Rebuild optimized config (optional but recommended)
php artisan config:cache
```

---

## Step 6: Verify AWS SES Configuration

Check if SES is properly configured:

```bash
php artisan tinker
```

Inside tinker, run:
```php
config('mail.default');
// Should return: "ses"

config('mail.from.address');
// Should return: "info@theathletex.net"

config('services.ses');
// Should return array with key, secret, and region

exit
```

---

## Step 7: Test Email Sending

Send a test email to verify everything works:

```bash
php artisan tinker
```

Inside tinker:
```php
use Illuminate\Support\Facades\Mail;

Mail::raw('Test email from production server', function($message) {
    $message->to('YOUR_EMAIL@example.com')
            ->subject('Production Email Test - AWS SES');
});

echo "Email sent successfully!\n";
exit
```

**Check your inbox** for the test email. It should arrive within 1-2 minutes.

---

## Step 8: Restart Services (if applicable)

If you're using any queue workers or supervisord:

```bash
# Restart queue workers
php artisan queue:restart

# If using supervisord
sudo supervisorctl restart all

# If using systemd for queues
sudo systemctl restart laravel-worker
```

If you're using PHP-FPM with nginx/Apache:

```bash
# For PHP-FPM
sudo systemctl restart php8.2-fpm
# OR
sudo systemctl restart php-fpm

# For Apache
sudo systemctl restart apache2

# For Nginx (if needed)
sudo systemctl restart nginx
```

---

## Verification Checklist

After completing all steps, verify:

- [ ] `git pull` completed successfully
- [ ] AWS SDK installed (`composer show aws/aws-sdk-php`)
- [ ] `.env` file updated with `MAIL_MAILER=ses`
- [ ] AWS credentials set in `.env`
- [ ] All caches cleared
- [ ] `php artisan tinker` shows `config('mail.default')` = "ses"
- [ ] Test email sent and received successfully
- [ ] Application emails working (registration confirmations, etc.)
- [ ] No errors in Laravel logs (`storage/logs/laravel.log`)

---

## Troubleshooting Production Issues

### Error: "Class 'Aws\Ses\SesClient' not found"
**Solution:**
```bash
composer dump-autoload
php artisan config:clear
```

### Error: "Credentials are not set"
**Solution:** 
Check `.env` file has these exact keys:
```env
AWS_ACCESS_KEY_ID=AKIAWV4V32WWV2JBF2E6
AWS_SECRET_ACCESS_KEY=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
AWS_DEFAULT_REGION=eu-north-1
```

Then run:
```bash
php artisan config:clear
php artisan config:cache
```

### Error: "Email address is not verified"
**Solution:** 
Your AWS SES account is in sandbox mode. Either:
1. Verify `info@theathletex.net` in AWS SES Console
2. Request production access (removes sandbox restrictions)

Go to AWS Console → SES → Email Addresses → Verify a New Email Address

### Error still persists with "host.cloudhousebd.com"
**Solution:**
This means `.env` wasn't updated or cache wasn't cleared. Repeat:
```bash
# Edit .env and ensure MAIL_MAILER=ses
nano .env

# Clear everything
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Verify
php artisan tinker --execute="echo config('mail.default');"
```

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

Look for mail-related errors.

---

## Quick Command Summary (Copy-Paste)

```bash
# Navigate to app directory
cd /path/to/theathletex

# Pull latest code
git pull origin main

# Install AWS SDK
composer require aws/aws-sdk-php --ignore-platform-req=ext-zip

# Update .env file (manually edit)
nano .env

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

# Test email
php artisan tinker --execute="Mail::raw('Test', fn(\$m) => \$m->to('your-email@example.com')->subject('Test')); echo 'Sent!';"

# Restart services (if applicable)
sudo systemctl restart php8.2-fpm
```

---

## After Successful Deployment

Once emails are working:
1. Monitor `storage/logs/laravel.log` for any new issues
2. Test actual user flows (registration, payment confirmations)
3. Update any monitoring/alerting systems
4. Document the change in your deployment log

---

**Need Help?**
- Check AWS SES Console for sending statistics and errors
- Review Laravel logs: `storage/logs/laravel.log`
- Verify AWS credentials are correct and have SES permissions
- Ensure AWS region matches your SES configuration (eu-north-1)

---

**Last Updated:** October 18, 2025
**Status:** Ready for production deployment
