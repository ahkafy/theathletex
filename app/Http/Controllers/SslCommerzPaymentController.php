<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Transaction;
use App\Http\Controllers\RegistrationController;

class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public function index(Request $request)
    {
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $transaction = Transaction::where('id', $request->transaction_id)->with('participant')->first();

        $post_data = array();
        $post_data['total_amount'] = $transaction->amount; # You cant not pay less than 10
        $post_data['currency'] = $transaction->currency; # You cant not pay less than 10
        $post_data['tran_id'] = $transaction->id; # tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $transaction->participant->name;
        $post_data['cus_email'] = $transaction->participant->email;
        $post_data['cus_add1'] = $transaction->participant->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = $transaction->participant->thana;
        $post_data['cus_state'] = $transaction->participant->district;
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $transaction->participant->phone;
        $post_data['cus_fax'] = "";

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }


    public function success(Request $request)
    {
        echo "Transaction is Successful";
        $tran_id = $request->input('tran_id');

        $trxInfo = Transaction::where('id', $tran_id)->with('participant')->first();

        $participantPhone = $trxInfo->participant->phone;

        $check = Transaction::where('id', $tran_id)
            ->update([
                'status' => 'complete',
                'currency' => $request->input('currency'),
                'transaction_id' => $request->input('bank_tran_id'),
                'payment_method' => $request->input('card_type'),
            ]);

        if ($check) {

            // Send SMS to participant
            $msg = "Dear " . $trxInfo->participant->name . ", your payment of " . $trxInfo->amount . " " . $trxInfo->currency . " for the event has been successfully completed. Transaction ID: " . $tran_id . ". Thank you for your participation!";
            $this->smsSend($participantPhone, $msg);

            echo "Transaction is successfully Completed";
            return redirect()->route('payment.init', ['trxID' => $tran_id])
                ->with('success', 'Transaction is successfully Completed');
        } else {
            echo "Invalid Transaction";
        }

    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        return redirect()->route('index')
            ->with('error', 'Transaction is Failed');

    }

    public function cancel(Request $request)
    {

        $tran_id = $request->input('tran_id');

        return redirect()->route('index')
            ->with('error', 'Transaction is Cancelled');

    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
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
