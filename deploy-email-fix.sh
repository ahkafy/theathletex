#!/bin/bash

# Quick Production Email Fix Deployment Script
# Run this on your PRODUCTION SERVER

echo "=========================================="
echo "Production Email Fix - Deployment Script"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Step 1: Pulling latest code from Git...${NC}"
git pull origin main
if [ $? -ne 0 ]; then
    echo -e "${RED}Git pull failed! Please resolve conflicts first.${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Code updated${NC}"
echo ""

echo -e "${YELLOW}Step 2: Installing AWS SDK for PHP...${NC}"
composer require aws/aws-sdk-php --ignore-platform-req=ext-zip
if [ $? -ne 0 ]; then
    echo -e "${RED}Composer install failed!${NC}"
    exit 1
fi
echo -e "${GREEN}✓ AWS SDK installed${NC}"
echo ""

echo -e "${YELLOW}Step 3: Clearing Laravel caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✓ Caches cleared${NC}"
echo ""

echo -e "${YELLOW}Step 4: Rebuilding configuration cache...${NC}"
php artisan config:cache
echo -e "${GREEN}✓ Config cached${NC}"
echo ""

echo -e "${YELLOW}Step 5: Verifying mail configuration...${NC}"
MAIL_DRIVER=$(php artisan tinker --execute="echo config('mail.default');")
echo "Current mail driver: $MAIL_DRIVER"

if [ "$MAIL_DRIVER" != "ses" ]; then
    echo -e "${RED}✗ Mail driver is not 'ses'!${NC}"
    echo -e "${YELLOW}Please update .env file:${NC}"
    echo "  MAIL_MAILER=ses"
    echo "  AWS_ACCESS_KEY_ID=REDACTED_COMPROMISED_KEY_REMOVED_2025-11-05"
    echo "  AWS_SECRET_ACCESS_KEY=REDACTED_COMPROMISED_SECRET_REMOVED_2025-11-05"
    echo "  AWS_DEFAULT_REGION=eu-north-1"
    echo ""
    echo "Then run: php artisan config:clear && php artisan config:cache"
    exit 1
else
    echo -e "${GREEN}✓ Mail driver correctly set to 'ses'${NC}"
fi
echo ""

echo -e "${YELLOW}Step 6: Testing email configuration...${NC}"
echo "Enter your email address to receive a test email:"
read TEST_EMAIL

if [ -n "$TEST_EMAIL" ]; then
    php artisan tinker --execute="use Illuminate\Support\Facades\Mail; Mail::raw('Production email test successful! AWS SES is working.', function(\$m) { \$m->to('$TEST_EMAIL')->subject('Production Email Test'); }); echo 'Test email sent to $TEST_EMAIL';"
    echo -e "${GREEN}✓ Test email sent!${NC}"
    echo "Check your inbox (may take 1-2 minutes)"
else
    echo -e "${YELLOW}Skipping email test${NC}"
fi
echo ""

echo "=========================================="
echo -e "${GREEN}Deployment Complete!${NC}"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Check your test email inbox"
echo "2. Monitor logs: tail -f storage/logs/laravel.log"
echo "3. Test actual user registration/emails"
echo ""
echo "If you need to restart services:"
echo "  sudo systemctl restart php8.2-fpm"
echo "  sudo systemctl restart nginx"
echo ""
