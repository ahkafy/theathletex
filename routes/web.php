<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\EventResultController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SslCommerzPaymentController;




Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/register/{eventID}/one', [RegistrationController::class, 'otpForm'])->name('otp.form');
Route::get('/register/{eventID}/send', [RegistrationController::class, 'sendOTP'])->name('otp.send');
Route::post('/register/{eventID}/verify', [RegistrationController::class, 'verifyOTP'])->name('otp.verify');
Route::get('/register/{eventID}/two', [RegistrationController::class, 'registrationForm'])->name('register.create');
Route::post('/register/{eventID}/store', [RegistrationController::class, 'registerParticipant'])->name('register.store');

Route::get('/payment/{trxID}', [RegistrationController::class, 'paymentInit'])->name('payment.init');
Route::post('/payment/pay', [PaymentController::class, 'pay'])->name('payment.pay');

Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
//Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

// Event Results Routes
Route::get('/events/{eventSlug}/results', [EventResultController::class, 'index'])->name('events.results');
Route::get('/events/{eventSlug}/results/category/{category}', [EventResultController::class, 'byCategory'])->name('events.results.category');
Route::get('/events/{eventSlug}/certificate/{participantId}', [EventResultController::class, 'certificate'])->name('events.certificate');

// Policy Pages
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/delivery', [HomeController::class, 'delivery'])->name('delivery');
Route::get('/return', [HomeController::class, 'return'])->name('return');

Route::get('/testmail', [TestMailController::class, 'showForm']);
Route::post('/testmail', [TestMailController::class, 'send']);
