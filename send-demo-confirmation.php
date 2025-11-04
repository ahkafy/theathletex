<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmation;
use App\Models\Transaction;
use App\Models\Participant;
use App\Models\Event;

echo "=================================\n";
echo "Demo Payment Confirmation Email\n";
echo "=================================\n\n";

try {
    // Check if we have any real transaction data
    echo "1. Checking for existing transaction data...\n";
    $realTransaction = Transaction::with(['participant.event'])->latest()->first();

    if ($realTransaction) {
        echo "   ✓ Found real transaction data\n";
        echo "   Transaction ID: " . $realTransaction->id . "\n";
        echo "   Participant: " . $realTransaction->participant->name . "\n";
        echo "   Event: " . ($realTransaction->participant->event->name ?? 'N/A') . "\n";
        echo "   Amount: " . $realTransaction->amount . " BDT\n\n";

        echo "2. Sending payment confirmation email to ahkafy@gmail.com...\n";
        Mail::to('ahkafy@gmail.com')->send(new PaymentConfirmation($realTransaction));
        echo "   ✓ Email sent successfully!\n\n";

    } else {
        echo "   ℹ No transaction data found. Creating demo data...\n\n";

        // Create demo event
        echo "   Creating demo event...\n";
        $event = new Event();
        $event->name = "Dhaka Marathon 2025";
        $event->slug = "dhaka-marathon-2025";
        $event->description = "Annual marathon event in Dhaka";
        $event->start_time = now()->addDays(30)->format('Y-m-d H:i:s');
        $event->end_time = now()->addDays(30)->addHours(6)->format('Y-m-d H:i:s');
        $event->capacity = '500';
        $event->venue = "National Parliament House, Dhaka";
        $event->status = 'open';
        $event->additional_fields = ['Registration Deadline' => '2025-11-30'];
        $event->save();

        // Create demo participant
        echo "   Creating demo participant...\n";
        $participant = new Participant();
        $participant->event_id = $event->id;
        $participant->name = "Ahmed Kafy";
        $participant->email = "ahkafy@gmail.com";
        $participant->phone = "+8801712345678";
        $participant->date_of_birth = "1990-01-15";
        $participant->gender = "Male";
        $participant->tshirt_size = "L";
        $participant->blood_group = "O+";
        $participant->emergency_contact = "+8801987654321";
        $participant->emergency_name = "Sarah Kafy";
        $participant->address = "House 123, Road 45, Gulshan";
        $participant->city = "Dhaka";
        $participant->state = "Dhaka Division";
        $participant->zip = "1212";
        $participant->country = "Bangladesh";
        $participant->category = "10K Run";
        $participant->additional_data = json_encode([
            'Team Name' => 'Speed Runners',
            'Previous Marathon Experience' => 'Yes - 3 marathons',
            'Expected Completion Time' => '55 minutes',
            'Dietary Requirements' => 'None'
        ]);
        $participant->save();

        // Create demo transaction
        echo "   Creating demo transaction...\n";
        $transaction = new Transaction();
        $transaction->participant_id = $participant->id;
        $transaction->amount = 1500.00;
        $transaction->transaction_id = 'DEMO' . time();
        $transaction->status = 'Success';
        $transaction->currency = 'BDT';
        $transaction->card_type = 'VISA';
        $transaction->card_brand = 'VISA-Dutch Bangla';
        $transaction->card_issuer = 'Dutch-Bangla Bank';
        $transaction->card_issuer_country = 'Bangladesh';
        $transaction->payment_date = now();
        $transaction->save();

        // Reload with relationships
        $transaction->load(['participant.event']);

        echo "   ✓ Demo data created successfully!\n";
        echo "   Participant ID: " . $participant->id . "\n";
        echo "   Transaction ID: " . $transaction->transaction_id . "\n\n";

        echo "2. Sending payment confirmation email to ahkafy@gmail.com...\n";
        Mail::to('ahkafy@gmail.com')->send(new PaymentConfirmation($transaction));
        echo "   ✓ Email sent successfully!\n\n";
    }

    echo "=================================\n";
    echo "✅ Demo email sent successfully!\n";
    echo "=================================\n";
    echo "\nPlease check ahkafy@gmail.com inbox (and spam folder) for:\n";
    echo "- Subject: Payment Confirmation - TheAthleteX\n";
    echo "- The email should include all participant details, event info, and payment information\n\n";

    echo "System Check:\n";
    echo "✓ Mail configuration: " . config('mail.default') . "\n";
    echo "✓ From address: " . config('mail.from.address') . "\n";
    echo "✓ AWS SES Region: " . config('services.ses.region') . "\n";
    echo "✓ Email template: resources/views/emails/payment-confirmation.blade.php\n";
    echo "✓ Mailable class: App\\Mail\\PaymentConfirmation\n";

} catch (Exception $e) {
    echo "\n❌ Error occurred:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
