<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Library\SslCommerz\SslCommerzNotification;

$postData = [
    'total_amount' => 10,
    'currency' => 'BDT',
    'tran_id' => 'TEST_' . time(),
    'cus_name' => 'Test User',
    'cus_email' => 'test@example.com',
    'cus_phone' => '01700000000',
    'cus_add1' => 'Dhaka',
    'cus_city' => 'Dhaka',
    'cus_country' => 'Bangladesh',
    'product_name' => 'Test',
    'product_category' => 'Test',
    'product_profile' => 'general',
    'shipping_method' => 'NO',
];

echo "Testing SSLCommerz connection...\n";
echo "Store ID: " . config('sslcommerz.apiCredentials.store_id') . "\n";
echo "Test Mode: " . (env('SSLCZ_TESTMODE') ? 'TRUE' : 'FALSE') . "\n";

$sslc = new SslCommerzNotification();
// We don't want it to exit() on us, so we'll use a mock or check how it works
// Actually makePayment for 'hosted' calls redirect() which calls exit()
// Let's use 'checkout' type instead to get the response array
$response = $sslc->makePayment($postData, 'checkout');

echo "Response:\n";
print_r($response);
