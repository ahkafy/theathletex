<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmation;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Transaction;

echo "=================================\n";
echo "Demo Payment Confirmation Email\n";
echo "=================================\n\n";

try {
    // Create demo data in database
    echo "1. Creating demo data in database...\n";

    // Create event
    echo "   Creating event...\n";
    $event = Event::create([
        'name' => 'Dhaka Marathon 2025',
        'slug' => 'dhaka-marathon-2025-demo-' . time(),
        'description' => 'Annual marathon event in Dhaka city',
        'start_time' => now()->addDays(30),
        'end_time' => now()->addDays(30)->addHours(6),
        'capacity' => '500',
        'venue' => 'National Parliament House, Dhaka',
        'status' => 'open',
    ]);

    // Create participant
    echo "   Creating participant...\n";
    $participant = Participant::create([
        'event_id' => $event->id,
        'name' => 'Ahmed Kafy',
        'email' => 'ahkafy@gmail.com',
        'phone' => '+8801712345678',
        'dob' => '1990-01-15',
        'gender' => 'male',
        'tshirt_size' => 'L',
        'address' => 'House 123, Road 45, Gulshan',
        'district' => 'Dhaka',
        'thana' => 'Gulshan',
        'nationality' => 'Bangladeshi',
        'emergency_phone' => '+8801987654321',
        'category' => '10K Run',
        'reg_type' => 'Individual',
        'fee' => 1500.00,
        'additional_data' => [
            'Team Name' => 'Speed Runners',
            'Previous Marathon Experience' => 'Yes - 3 marathons',
            'Expected Completion Time' => '55 minutes',
            'Dietary Requirements' => 'None'
        ],
    ]);

    // Create transaction
    echo "   Creating transaction...\n";
    $transaction = Transaction::create([
        'event_id' => $event->id,
        'participant_id' => $participant->id,
        'amount' => 1500.00,
        'transaction_id' => 'DEMO' . time(),
        'status' => 'complete',
        'currency' => 'BDT',
        'payment_method' => 'SSLCommerz - VISA',
    ]);

    // Load relationships
    $transaction->load(['participant.event']);

    echo "   ✓ Demo data created successfully!\n";
    echo "   Event ID: " . $event->id . "\n";
    echo "   Participant ID: " . $participant->participant_id . "\n";
    echo "   Transaction ID: " . $transaction->transaction_id . "\n\n";

    echo "2. Sending payment confirmation email to ahkafy@gmail.com...\n";
    Mail::to('ahkafy@gmail.com')->send(new PaymentConfirmation($transaction));
    echo "   ✓ Email sent successfully!\n\n";

    echo "=================================\n";
    echo "✅ Demo email sent successfully!\n";
    echo "=================================\n";
    echo "\n📧 Email Details:\n";
    echo "   To: ahkafy@gmail.com\n";
    echo "   From: " . config('mail.from.address') . " (" . config('mail.from.name') . ")\n";
    echo "   Subject: Payment Confirmation - Order #" . $transaction->id . "\n\n";

    echo "📋 What's included in the email:\n";
    echo "   ✓ Participant ID: " . $participant->participant_id . "\n";
    echo "   ✓ Participant Name: " . $participant->name . "\n";
    echo "   ✓ Event: " . $event->name . "\n";
    echo "   ✓ Category: " . $participant->category . "\n";
    echo "   ✓ Registration Fee: " . number_format($transaction->amount, 2) . " BDT\n";
    echo "   ✓ Payment Status: " . $transaction->status . "\n";
    echo "   ✓ Transaction ID: " . $transaction->transaction_id . "\n";
    echo "   ✓ Payment Method: " . $transaction->card_brand . "\n";
    echo "   ✓ Participant Information (DOB, Gender, T-Shirt Size, Emergency Contact)\n";
    echo "   ✓ Billing Address\n\n";

    echo "🔍 System Check:\n";
    echo "   ✓ Mail Mailer: " . config('mail.default') . "\n";
    echo "   ✓ AWS SES Region: " . config('services.ses.region') . "\n";
    echo "   ✓ Email Template: resources/views/emails/payment-confirmation.blade.php\n";
    echo "   ✓ Mailable Class: App\\Mail\\PaymentConfirmation\n\n";

    echo "📬 Please check ahkafy@gmail.com inbox (and spam folder)!\n\n";

    echo "ℹ  Note: Demo data has been added to your database.\n";
    echo "   You can view it in the admin participants section.\n";

} catch (Exception $e) {
    echo "\n❌ Error occurred:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

