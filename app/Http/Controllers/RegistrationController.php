<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Event;
use App\Models\Transaction;


use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller
{

    public function otpForm($eventID)
    {
        $event = Event::find($eventID);
        if (!$event) {
            return redirect('/')->with('error', 'Event not found');
        }
        return view('registration.otp', compact('eventID', 'event'));
    }

    public function sendOTP(Request $request, $eventID)
    {
        $otp = rand(100000, 999999); // Example OTP generation
        session(['otp' => $otp]);
        session(['phone' => $request->phone]);

        $event = Event::find($eventID);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $msg = "Your OTP for registration in the event '{$event->name}' is: $otp";
        $this->smsSend($request->phone, $msg);

        // You need to get $eventID from the request or session if not passed directly

        return view('registration.verify', compact('eventID', 'event', 'otp'))->with('success', 'OTP sent successfully. Please check your mobile for the OTP.');
    }

    public function verifyOTP(Request $request, $eventID)
    {
        $inputOtp = $request->input('otp');
        $sessionOtp = session('otp');

        if (!$sessionOtp) {
            return response()->json(['message' => 'OTP session expired'], 400);
        }

        if ($inputOtp == $sessionOtp) {
            return redirect()->route('register.create', ['eventID' => $eventID])
                ->with('success', 'OTP verified successfully. Please proceed to registration.');
        } else {
            return response()->back()
                ->withInput()
                ->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }
    }

    public function registrationForm($eventID)
    {
        $event = Event::where('id', $eventID)->with('fees', 'categories')->first();
        if (!$event) {
            return redirect('/')->with('error', 'Event not found');
        }
        $verifiedPhone = session('phone');
        if (!$verifiedPhone) {
            return redirect()->route('otp.form', ['eventID' => $eventID])
                ->with('error', 'Please verify your phone number first.');
        }

        return view('registration.form', compact('eventID', 'event', 'verifiedPhone'));
    }

    public function registerParticipant(Request $request, $eventID)
    {
        $request->validate([
            'reg_type' => 'required',
            'category' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'district' => 'required|string|max:100',
            'thana' => 'required|string|max:100',
            'emergency_phone' => 'required|string|max:20',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:100',
            'tshirt_size' => 'required|string|max:10',
            'terms_agreed' => 'required|accepted',
        ]);

        $event = Event::where('id', $eventID)->with('fees')->first();

        if (!$event) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['event' => 'Event not found.']);
        }

        $fee = $event->fees->where('id', $request->input('reg_type'))->first();

        if (!$fee) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['reg_type' => 'Invalid registration type selected.']);
        }

        $participant = new Participant();
        $participant->event_id = $eventID;
        $participant->reg_type = $fee->fee_type;
        $participant->category = $request->input('category');
        $participant->fee = $fee->fee_amount; // Assuming fee_amount is a field in the fees table
        $participant->name = $request->input('name');
        $participant->email = $request->input('email');
        $participant->phone = $request->input('phone');
        $participant->address = $request->input('address');
        $participant->district = $request->input('district');
        $participant->thana = $request->input('thana');
        $participant->emergency_phone = $request->input('emergency_phone');
        $participant->gender = $request->input('gender');
        $participant->dob = $request->input('dob');
        $participant->nationality = $request->input('nationality');
        $participant->tshirt_size = $request->input('tshirt_size');
        $participant->kit_option = $request->input('kit_option');
        $participant->terms_agreed = $request->input('terms_agreed');
        $participant->payment_method = $request->input('payment_method');
        // Add other fields as necessary
        $check = $participant->save();

        if ($check) {
            // Create a transaction record
            $trx = Transaction::create([
                'participant_id' => $participant->id,
                'event_id' => $eventID,
                'amount' => $fee->fee_amount, // Assuming fee_amount is a field in the fees table
                'description' => 'Registration fee for event: ' . $event->name,
            ]);

            if (!$trx) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['transaction' => 'Transaction creation failed.']);
            }

            return redirect()->route('payment.init', ['trxID' => $trx->id])
                ->with('success', 'Registration successful. Your transaction ID is: ' . $trx->id);
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors(['registration' => 'Registration failed. Please try again.']);
        }
    }


    public function paymentInit(Request $request, $trxID)
    {
        $transaction = Transaction::where('id', $trxID)->with('participant', 'event')->first();

        if (!$transaction) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['transaction' => 'Transaction not found.']);
        }

        // Here you would typically initiate the payment process
        // For demonstration, we will just return a success message
        return view('registration.payment', compact('transaction'))->with('success', 'Payment initiated successfully. Please proceed with the payment.');
    }

    function smsSend($phone, $msg) {
        $url = "http://bulksmsbd.net/api/smsapi";
        $api_key = "MRk54VtStyOPwoApfyuP";
        $senderid = "8809617642628";
        $number = $phone;
        $message = $msg;

        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
