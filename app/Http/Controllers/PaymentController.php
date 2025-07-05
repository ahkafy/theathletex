<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $post_data = array();
        $post_data['total_amount'] = $request->amount;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // Unique transaction ID

        // Customer information
        $post_data['cus_name'] = $request->name;
        $post_data['cus_email'] = $request->email;
        $post_data['cus_add1'] = $request->address;
        $post_data['cus_phone'] = $request->phone;

        // URLs
        $post_data['success_url'] = route('payment.success');
        $post_data['fail_url'] = route('payment.fail');
        $post_data['cancel_url'] = route('payment.cancel');

        // SSLCommerz credentials
        $store_id = env('SSLCOMMERZ_STORE_ID');
        $store_passwd = env('SSLCOMMERZ_STORE_PASSWORD');
        $url = env('SSLCOMMERZ_SANDBOX', true)
            ? "https://sandbox.sslcommerz.com/gwprocess/v4/api.php"
            : "https://securepay.sslcommerz.com/gwprocess/v4/api.php";

        $post_data['store_id'] = $store_id;
        $post_data['store_passwd'] = $store_passwd;

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);

        $content = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !(curl_errno($handle))) {
            $sslcommerzResponse = json_decode($content, true);
            if (isset($sslcommerzResponse['GatewayPageURL']) && $sslcommerzResponse['GatewayPageURL'] != "") {
                return redirect($sslcommerzResponse['GatewayPageURL']);
            } else {
                return response()->json(['error' => 'Failed to connect with SSLCOMMERZ'], 500);
            }
        } else {
            return response()->json(['error' => 'Failed to connect with SSLCOMMERZ'], 500);
        }
    }

    public function success(Request $request)
    {
        // Validate required fields from SSLCOMMERZ response
        $validated = $request->validate([
            'tran_id' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'value_a' => 'nullable', // You can use value_a to pass user_id or event_id if set in pay()
            'value_b' => 'nullable', // You can use value_b to pass event_id or other info if set in pay()
        ]);

        // Retrieve user_id and event_id from custom fields or session (adjust as needed)
        $user_id = $request->input('value_a');
        $event_id = $request->input('value_b');

        // Fallback if not passed in value_a/value_b
        if (!$user_id) {
            $user_id = auth()->id();
        }

        // Create transaction record
        \App\Models\Transaction::create([
            'user_id' => $user_id,
            'event_id' => $event_id,
            'transaction_id' => $request->input('tran_id'),
            'amount' => $request->input('amount'),
            'payment_method' => 'sslcommerz',
            'status' => 'completed',
            'description' => 'Payment successful via SSLCOMMERZ',
            'transaction_date' => now(),
            'currency' => $request->input('currency', 'BDT'),
        ]);
        // Handle payment success logic here
        return response()->json(['message' => 'Payment Successful', 'data' => $request->all()]);
    }

    public function fail(Request $request)
    {
        // Handle payment failure logic here
        return response()->json(['message' => 'Payment Failed', 'data' => $request->all()]);
    }

    public function cancel(Request $request)
    {
        // Handle payment cancellation logic here
        return response()->json(['message' => 'Payment Cancelled', 'data' => $request->all()]);
    }
}
