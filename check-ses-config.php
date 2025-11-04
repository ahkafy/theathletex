<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=================================\n";
echo "AWS SES Configuration Check\n";
echo "=================================\n\n";

// Check environment variables
echo "1. Environment Variables:\n";
echo "   MAIL_MAILER: " . env('MAIL_MAILER') . "\n";
echo "   MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
echo "   MAIL_FROM_NAME: " . env('MAIL_FROM_NAME') . "\n";
echo "   AWS_ACCESS_KEY_ID: " . (env('AWS_ACCESS_KEY_ID') ? substr(env('AWS_ACCESS_KEY_ID'), 0, 8) . '...' : 'NOT SET') . "\n";
echo "   AWS_SECRET_ACCESS_KEY: " . (env('AWS_SECRET_ACCESS_KEY') ? '***SET***' : 'NOT SET') . "\n";
echo "   AWS_DEFAULT_REGION: " . env('AWS_DEFAULT_REGION') . "\n\n";

// Check config values
echo "2. Mail Configuration:\n";
echo "   Default Mailer: " . config('mail.default') . "\n";
echo "   From Address: " . config('mail.from.address') . "\n";
echo "   From Name: " . config('mail.from.name') . "\n\n";

echo "3. SES Configuration:\n";
echo "   SES Key: " . (config('services.ses.key') ? substr(config('services.ses.key'), 0, 8) . '...' : 'NOT SET') . "\n";
echo "   SES Secret: " . (config('services.ses.secret') ? '***SET***' : 'NOT SET') . "\n";
echo "   SES Region: " . config('services.ses.region') . "\n\n";

// Check if AWS SDK is available
echo "4. AWS SDK Check:\n";
try {
    $sesClient = new \Aws\Ses\SesClient([
        'version' => 'latest',
        'region'  => config('services.ses.region'),
        'credentials' => [
            'key'    => config('services.ses.key'),
            'secret' => config('services.ses.secret'),
        ],
    ]);

    echo "   ✓ AWS SDK initialized successfully\n";

    // Try to get sending quota
    try {
        $result = $sesClient->getSendQuota();
        echo "   ✓ Connection to AWS SES successful!\n";
        echo "\n5. AWS SES Account Status:\n";
        echo "   Max 24 Hour Send: " . $result['Max24HourSend'] . "\n";
        echo "   Max Send Rate: " . $result['MaxSendRate'] . " per second\n";
        echo "   Sent Last 24 Hours: " . $result['SentLast24Hours'] . "\n";

        if ($result['Max24HourSend'] == 200) {
            echo "\n   ⚠️  WARNING: Your account appears to be in SANDBOX mode!\n";
            echo "   In sandbox mode, you can only send emails to:\n";
            echo "   - Verified email addresses\n";
            echo "   - Verified domains\n";
            echo "   To send to any email address, request production access in AWS SES Console.\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Failed to get SES sending quota: " . $e->getMessage() . "\n";
    }

    // Try to list verified emails
    try {
        $result = $sesClient->listIdentities(['IdentityType' => 'EmailAddress']);
        echo "\n6. Verified Email Addresses:\n";
        if (empty($result['Identities'])) {
            echo "   ⚠️  No verified email addresses found!\n";
            echo "   You need to verify email addresses in AWS SES Console.\n";
        } else {
            foreach ($result['Identities'] as $email) {
                echo "   ✓ " . $email . "\n";
            }
        }
    } catch (Exception $e) {
        echo "\n6. Verified Email Addresses:\n";
        echo "   ❌ Failed to list verified emails: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=================================\n";
echo "Configuration check complete!\n";
echo "=================================\n";
