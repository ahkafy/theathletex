# AWS SES Email Fix - Production Deployment Guide

## Problem Fixed
- **Error**: `stream_socket_enable_crypto(): Peer certificate CN='host.cloudhousebd.com' did not match expected CN='email-smtp.eu-north-1.amazonaws.com'`
- **Root Cause**: Production hosting provider (CloudHouse BD) intercepts SMTP connections with proxy/relay
- **Solution**: Switch from SMTP to AWS SES API (bypasses SMTP ports entirely)

---

## Changes Made

### 1. ✅ Installed AWS SDK for PHP
```bash
composer require aws/aws-sdk-php
```

### 2. ✅ Updated `.env` Configuration
Changed from SMTP to SES mailer:

```env
# OLD (SMTP - doesn't work on production)
MAIL_MAILER=smtp
MAIL_HOST=email-smtp.eu-north-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=REDACTED_COMPROMISED_KEY_REMOVED_2025-11-05
MAIL_PASSWORD=4lPRil++o2/mP6k/VJErNa1P1FctI/GOT9OC/D5m
MAIL_ENCRYPTION=tls

# NEW (SES API - works everywhere)
MAIL_MAILER=ses
MAIL_FROM_ADDRESS="info@theathletex.net"
MAIL_FROM_NAME="The Athlete X Limited"

# AWS Credentials (required for SES)
AWS_ACCESS_KEY_ID=REDACTED_COMPROMISED_KEY_REMOVED_2025-11-05
AWS_SECRET_ACCESS_KEY=REDACTED_COMPROMISED_SECRET_REMOVED_2025-11-05
AWS_DEFAULT_REGION=eu-north-1
```

---

## Production Deployment Steps

### Step 1: Update Production `.env`
On your production server, update the `.env` file with:

```bash
# Change mail mailer
MAIL_MAILER=ses

# Remove or comment out SMTP settings
# MAIL_HOST=...
# MAIL_PORT=...
# MAIL_USERNAME=...
# MAIL_PASSWORD=...
# MAIL_ENCRYPTION=...

# Set AWS credentials
AWS_ACCESS_KEY_ID=AKIAWV4V32WWV2JBF2E6
AWS_SECRET_ACCESS_KEY=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
AWS_DEFAULT_REGION=eu-north-1

# Keep mail from settings
MAIL_FROM_ADDRESS="info@theathletex.net"
MAIL_FROM_NAME="The Athlete X Limited"
```

### Step 2: Install AWS SDK on Production
```bash
cd /path/to/production/theathletex
composer require aws/aws-sdk-php
```

### Step 3: Clear Laravel Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### Step 4: Test Email Sending
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from AWS SES', function($message) { 
    $message->to('test@example.com')->subject('Production Test Email'); 
});
```

Expected output: No errors, email should send successfully.

---

## Why This Works

### SMTP Method (Port 587) - ❌ Failed
- Requires direct connection to `email-smtp.eu-north-1.amazonaws.com:587`
- CloudHouse BD hosting intercepts outbound SMTP with proxy
- Proxy presents wrong TLS certificate (`host.cloudhousebd.com`)
- PHP refuses connection due to certificate mismatch

### AWS SES API Method - ✅ Works
- Uses HTTPS API calls instead of SMTP ports
- No SMTP interception by hosting provider
- More reliable and faster than SMTP
- Better error handling and debugging
- No certificate/TLS issues

---

## Alternative Solutions (If SES API Fails)

### Option A: Use Port 465 (SSL/TLS) instead of 587 (STARTTLS)
```env
MAIL_MAILER=smtp
MAIL_HOST=email-smtp.eu-north-1.amazonaws.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=AKIAWV4V32WWV2JBF2E6
MAIL_PASSWORD=REDACTED_COMPROMISED_SECRET_REMOVED_2025-11-05
```

### Option B: Contact CloudHouse BD Support
Ask them to:
1. Allow direct SMTP connection to AWS SES (whitelist `email-smtp.eu-north-1.amazonaws.com`)
2. Disable SMTP proxy/relay for your account
3. Update their TLS certificate to match their hostname

### Option C: Disable Certificate Verification (NOT RECOMMENDED - Security Risk)
Only use for temporary testing:

In `config/mail.php`, add to `smtp` mailer:
```php
'smtp' => [
    'transport' => 'smtp',
    'host' => env('MAIL_HOST', '127.0.0.1'),
    'port' => env('MAIL_PORT', 2525),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'timeout' => null,
    'local_domain' => env('MAIL_EHLO_DOMAIN'),
    'stream' => [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ],
],
```

---

## Verification Checklist

- [ ] AWS SDK installed on production (`composer require aws/aws-sdk-php`)
- [ ] `.env` updated with `MAIL_MAILER=ses`
- [ ] AWS credentials set in `.env`
- [ ] AWS region set to `eu-north-1`
- [ ] Config cache cleared (`php artisan config:clear`)
- [ ] Test email sent successfully
- [ ] Application logs show no mail errors
- [ ] Production emails working (registration confirmations, payment receipts, etc.)

---

## Troubleshooting

### Error: "Credentials are not set"
**Fix**: Ensure `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY` are set in production `.env`

### Error: "Access denied"
**Fix**: Verify AWS SES credentials are correct and have SES sending permissions

### Error: "Email address not verified"
**Fix**: In AWS SES console, verify `info@theathletex.net` sender email (or move out of SES sandbox)

### Emails still not sending
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify AWS region matches SES setup
3. Test with simple `Mail::raw()` in tinker first
4. Check AWS SES console for sending statistics and errors

---

## Benefits of This Solution

✅ **No SMTP port blocking issues**
✅ **No TLS/SSL certificate mismatch**
✅ **Better performance** (direct API vs SMTP relay)
✅ **More reliable** (no hosting provider interference)
✅ **Better error reporting** (AWS SDK provides detailed errors)
✅ **Works on any hosting provider** (CloudHouse BD, AWS, others)

---

**Last Updated**: October 18, 2025
**Status**: Ready for production deployment
