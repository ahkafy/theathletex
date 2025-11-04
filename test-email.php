<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmation;
use App\Models\Transaction;
use App\Models\Participant;

try {
    // Test 1: Send a simple raw email
    echo "Test 1: Sending simple test email...\n";
    Mail::raw('This is a test email from TheAthleteX to verify AWS SES email functionality.', function($message) {
        $message->to('ahkafy@gmail.com')
                ->subject('Test Email from TheAthleteX - ' . date('Y-m-d H:i:s'));
    });
    echo "✓ Simple test email sent successfully!\n\n";

    // Test 2: Check latest transaction and send payment confirmation
    echo "Test 2: Checking for recent transactions...\n";
    $latestTransaction = Transaction::with(['participant.event'])
                                    ->where('status', 'Success')
                                    ->latest()
                                    ->first();

    if ($latestTransaction) {
        echo "Found transaction ID: " . $latestTransaction->id . "\n";
        echo "Participant: " . $latestTransaction->participant->name . "\n";
        echo "Email: " . $latestTransaction->participant->email . "\n";
        echo "Sending payment confirmation email...\n";

        Mail::to('ahkafy@gmail.com')->send(new PaymentConfirmation($latestTransaction));
        echo "✓ Payment confirmation email sent successfully!\n\n";
    } else {
        echo "No successful transactions found in database.\n\n";
    }

    echo "=================================\n";
    echo "All tests completed successfully!\n";
    echo "=================================\n";
    echo "Please check ahkafy@gmail.com inbox (and spam folder) for test emails.\n";

} catch (Exception $e) {
    echo "❌ Error occurred:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
