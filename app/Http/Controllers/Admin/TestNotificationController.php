<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Participant;
use App\Models\Event;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestNotificationController extends Controller
{
    /**
     * Show the test notification panel.
     */
    public function index()
    {
        $latestTransactions = Transaction::with(['participant', 'event'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('admin.test-notifications', compact('latestTransactions'));
    }

    /**
     * Send test SMS.
     */
    public function sendSms(Request $request)
    {
        $request->validate([
            'phone'      => 'required|string',
            'name'       => 'required|string',
            'event_name' => 'required|string',
            'order_id'   => 'required|string',
            'category'   => 'required|string',
        ]);

        $phone     = trim($request->input('phone'));
        $name      = trim($request->input('name'));
        $eventName = trim($request->input('event_name'));
        $orderId   = trim($request->input('order_id'));
        $category  = trim($request->input('category'));

        // Build exact confirmation message
        $msg = "Dear {$name} your registration for {$eventName} is confirmed! Order # {$orderId} Category: {$category}. Best regards, The Athlete X";
        
        // Transliterate to clean ASCII
        $asciiMsg = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $msg) ?: $msg;

        // Send via BulkSMSBD API
        $apiUrl   = "http://bulksmsbd.net/api/smsapi";
        $apiKey   = "MRk54VtStyOPwoApfyuP";
        $senderId = env('BULKSMSBD_SENDER_ID', 'theatheletex');

        $postData = [
            "api_key"  => $apiKey,
            "senderid" => $senderId,
            "number"   => $phone,
            "message"  => $asciiMsg
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                return redirect()->back()
                    ->withInput()
                    ->with('sms_error', 'cURL Error: ' . $curlError)
                    ->with('last_sms_sent', $asciiMsg);
            }

            return redirect()->back()
                ->withInput()
                ->with('sms_success', 'SMS dispatched! Gateway Response: ' . $response)
                ->with('last_sms_sent', $asciiMsg);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('sms_error', 'Exception: ' . $e->getMessage())
                ->with('last_sms_sent', $asciiMsg);
        }
    }

    /**
     * Send test Email.
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email'          => 'required|email',
            'transaction_id' => 'nullable|string',
            'name'           => 'required|string',
            'event_name'     => 'required|string',
            'order_id'       => 'required|string',
            'category'       => 'required|string',
            'tshirt_size'    => 'nullable|string',
            'gender'         => 'nullable|string',
        ]);

        $email = trim($request->input('email'));

        if ($request->filled('transaction_id')) {
            $transaction = Transaction::with(['participant', 'event'])->find($request->transaction_id);
        } else {
            $transaction = null;
        }

        if (!$transaction) {
            // Build in-memory model for testing
            $transaction = new Transaction();
            $transaction->id = $request->input('order_id', '15K00304');
            $transaction->amount = 1000;
            
            $participant = new Participant();
            $participant->name = $request->input('name', 'Test Participant');
            $participant->participant_id = $request->input('order_id', '15K00304');
            $participant->category = $request->input('category', '15KM');
            $participant->tshirt_size = $request->input('tshirt_size', 'XL (C-44, L-30)');
            $participant->gender = $request->input('gender', 'Male');
            
            $event = new Event();
            $event->name = $request->input('event_name', 'BYLC Running with Purpose 2026');

            $transaction->setRelation('participant', $participant);
            $transaction->setRelation('event', $event);
        }

        try {
            Mail::to($email)->send(new PaymentConfirmation($transaction));

            return redirect()->back()
                ->withInput()
                ->with('email_success', "Test confirmation email successfully sent to {$email}!");
        } catch (\Exception $e) {
            Log::error('Test email sending failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('email_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
