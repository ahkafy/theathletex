<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use App\Models\FormTransaction;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FormPaymentController extends Controller
{
    /**
     * Show the payment initiation page and auto-redirect to SSLCommerz.
     *
     * SSLCommerz's makePayment('hosted') calls header() + exit() directly
     * inside the library when it gets a valid gateway URL.
     * On failure it returns a string error message.
     *
     * The success/fail/cancel/ipn URLs are set in config/sslcommerz.php and
     * CANNOT be overridden per-request through postData — the library ignores
     * those keys and uses the config values. We therefore route all form-payment
     * callbacks through /form-payment/* which maps to this controller.
     */
    public function pay($slug, $responseId)
    {
        $form     = Form::where('slug', $slug)->firstOrFail();
        $response = FormResponse::where('id', $responseId)
                        ->where('form_id', $form->id)
                        ->with('transaction')
                        ->firstOrFail();

        // Already paid — go straight to thank-you
        if ($response->payment_status === 'complete') {
            return redirect()->route('form.thankyou', ['slug' => $form->slug])
                ->with('success', 'Payment already completed.');
        }

        $transaction = $response->transaction;

        if (!$transaction) {
            return redirect()->route('form.show', $form->slug)
                ->with('error', 'Transaction record not found. Please resubmit the form.');
        }

        // Show a page that displays payment info + errors, and auto-initiates payment
        return view('forms.payment', compact('form', 'response', 'transaction'));
    }

    /**
     * Actually initiate the SSLCommerz payment (called by the payment page's form POST).
     */
    public function initiate(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $transaction   = FormTransaction::findOrFail($transactionId);
        $response      = FormResponse::findOrFail($transaction->form_response_id);
        $form          = Form::findOrFail($transaction->form_id);

        // If already paid
        if ($response->payment_status === 'complete') {
            return redirect()->route('form.thankyou', ['slug' => $form->slug])
                ->with('success', 'Payment already completed.');
        }

        $postData = [
            'total_amount'     => $transaction->amount,
            'currency'         => $transaction->currency,
            'tran_id'          => $transaction->ssl_tran_id,

            'cus_name'         => $response->respondent_name,
            'cus_email'        => $response->respondent_email,
            'cus_phone'        => $response->respondent_phone ?? '01700000000',
            'cus_add1'         => 'Bangladesh',
            'cus_add2'         => '',
            'cus_city'         => 'Dhaka',
            'cus_state'        => 'Dhaka',
            'cus_postcode'     => '1200',
            'cus_country'      => 'Bangladesh',
            'cus_fax'          => '',

            'product_name'     => $form->title,
            'product_category' => 'Form Submission',
            'product_profile'  => 'general',

            'shipping_method'  => 'NO',
            'ship_name'        => $response->respondent_name,
            'ship_add1'        => 'Dhaka',
            'ship_city'        => 'Dhaka',
            'ship_postcode'    => '1200',
            'ship_country'     => 'Bangladesh',

            // Pass context as extra values so we can identify this on callback
            'value_a'          => (string) $form->id,
            'value_b'          => (string) $response->id,
            'value_c'          => $form->slug,
        ];

        // Set form-specific callback URLs at runtime so the library picks them up
        config([
            'sslcommerz.success_url' => '/form-payment/success',
            'sslcommerz.failed_url'  => '/form-payment/fail',
            'sslcommerz.cancel_url'  => '/form-payment/cancel',
            'sslcommerz.ipn_url'     => '/form-payment/ipn',
        ]);

        $sslc   = new SslCommerzNotification();
        // 'hosted' → library calls header('Location:...') and exit() on success
        // On failure it returns a string error message
        $result = $sslc->makePayment($postData, 'hosted');

        // If we reach here, makePayment failed (on success it exits)
        $error = is_string($result) ? $result : 'Payment gateway error. Please try again.';
        Log::error('SSLCommerz form payment initiation failed', [
            'form_id'     => $form->id,
            'response_id' => $response->id,
            'error'       => $error,
        ]);

        return redirect()->route('form.payment.pay', [
            'slug'       => $form->slug,
            'responseId' => $response->id,
        ])->with('error', 'Payment gateway error: ' . $error);
    }

    /**
     * SSLCommerz SUCCESS callback (POST from gateway).
     */
    public function success(Request $request)
    {
        $sslTranId = $request->input('tran_id');
        $amount    = $request->input('amount');
        $currency  = $request->input('currency');

        $transaction = FormTransaction::where('ssl_tran_id', $sslTranId)->first();

        if (!$transaction) {
            Log::error('FormPayment success: transaction not found', ['ssl_tran_id' => $sslTranId]);
            return redirect('/')->with('error', 'Transaction not found.');
        }

        // Prevent double-processing
        if ($transaction->status === 'complete') {
            $form = Form::find($transaction->form_id);
            return redirect()->route('form.thankyou', ['slug' => $form->slug])
                ->with('success', 'Payment already completed.');
        }

        // Ensure the library uses the correct validation URLs
        config([
            'sslcommerz.success_url' => '/form-payment/success',
            'sslcommerz.failed_url'  => '/form-payment/fail',
            'sslcommerz.cancel_url'  => '/form-payment/cancel',
            'sslcommerz.ipn_url'     => '/form-payment/ipn',
        ]);

        // Validate with SSLCommerz API
        $sslc       = new SslCommerzNotification();
        $validation = $sslc->orderValidate($request->all(), $sslTranId, $amount, $currency);

        if ($validation) {
            // Mark transaction complete
            $transaction->update([
                'status'         => 'complete',
                'bank_tran_id'   => $request->input('bank_tran_id'),
                'payment_method' => $request->input('card_type'),
                'raw_payload'    => $request->all(),
            ]);

            // Mark form response paid
            FormResponse::where('id', $transaction->form_response_id)
                ->update(['payment_status' => 'complete']);

            $form = Form::find($transaction->form_id);

            return redirect()->route('form.thankyou', ['slug' => $form->slug])
                ->with('success', 'Payment completed successfully! Your response has been recorded.');
        } else {
            // Validation failed — mark as failed and let user retry
            $transaction->update([
                'status'      => 'failed',
                'raw_payload' => $request->all(),
            ]);

            FormResponse::where('id', $transaction->form_response_id)
                ->update(['payment_status' => 'failed']);

            $form     = Form::find($transaction->form_id);
            $response = FormResponse::find($transaction->form_response_id);

            Log::warning('FormPayment success callback: validation failed', [
                'ssl_tran_id' => $sslTranId,
            ]);

            return redirect()->route('form.payment.pay', [
                'slug'       => $form->slug,
                'responseId' => $response->id,
            ])->with('error', 'Payment validation failed. Please try again.');
        }
    }

    /**
     * SSLCommerz FAIL callback (POST from gateway).
     */
    public function fail(Request $request)
    {
        $sslTranId = $request->input('tran_id');

        $transaction = FormTransaction::where('ssl_tran_id', $sslTranId)->first();

        if ($transaction && $transaction->status !== 'complete') {
            $transaction->update([
                'status'      => 'failed',
                'raw_payload' => $request->all(),
            ]);

            FormResponse::where('id', $transaction->form_response_id)
                ->update(['payment_status' => 'failed']);
        }

        // Redirect to the payment page so user can retry
        if ($transaction) {
            $form     = Form::find($transaction->form_id);
            $response = FormResponse::find($transaction->form_response_id);

            return redirect()->route('form.payment.pay', [
                'slug'       => $form->slug,
                'responseId' => $response->id,
            ])->with('error', 'Payment failed. Please try again.');
        }

        return redirect('/')->with('error', 'Payment failed.');
    }

    /**
     * SSLCommerz CANCEL callback (POST from gateway).
     */
    public function cancel(Request $request)
    {
        $sslTranId = $request->input('tran_id');

        $transaction = FormTransaction::where('ssl_tran_id', $sslTranId)->first();

        if ($transaction && $transaction->status !== 'complete') {
            $transaction->update([
                'status'      => 'cancelled',
                'raw_payload' => $request->all(),
            ]);
            // Keep form_response payment_status as 'pending' so user can retry
        }

        if ($transaction) {
            $form     = Form::find($transaction->form_id);
            $response = FormResponse::find($transaction->form_response_id);

            return redirect()->route('form.payment.pay', [
                'slug'       => $form->slug,
                'responseId' => $response->id,
            ])->with('error', 'Payment was cancelled. You can try again below.');
        }

        return redirect('/')->with('error', 'Payment cancelled.');
    }

    /**
     * SSLCommerz IPN callback (server-to-server POST).
     */
    public function ipn(Request $request)
    {
        $sslTranId = $request->input('tran_id');

        if (!$sslTranId) {
            Log::warning('FormPayment IPN: no tran_id received');
            echo 'Invalid IPN data.';
            return;
        }

        $transaction = FormTransaction::where('ssl_tran_id', $sslTranId)->first();

        if (!$transaction) {
            echo 'Transaction not found.';
            return;
        }

        if ($transaction->status !== 'pending') {
            echo 'Already processed.';
            return;
        }

        // Ensure the library uses the correct validation URLs
        config([
            'sslcommerz.success_url' => '/form-payment/success',
            'sslcommerz.failed_url'  => '/form-payment/fail',
            'sslcommerz.cancel_url'  => '/form-payment/cancel',
            'sslcommerz.ipn_url'     => '/form-payment/ipn',
        ]);

        $sslc       = new SslCommerzNotification();
        $validation = $sslc->orderValidate(
            $request->all(),
            $sslTranId,
            $transaction->amount,
            $transaction->currency
        );

        if ($validation) {
            $transaction->update([
                'status'      => 'complete',
                'raw_payload' => $request->all(),
            ]);

            FormResponse::where('id', $transaction->form_response_id)
                ->update(['payment_status' => 'complete']);

            echo 'IPN: Transaction completed.';
        } else {
            echo 'IPN: Validation failed.';
        }
    }
}
