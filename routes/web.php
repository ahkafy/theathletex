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
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\FormPaymentController;




Auth::routes(['verify' => true]);

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Events pages
Route::get('/events', [HomeController::class, 'allEvents'])->name('events.all');
Route::get('/events/upcoming', [HomeController::class, 'upcomingEvents'])->name('events.upcoming');
Route::get('/events/past', [HomeController::class, 'pastEvents'])->name('events.past');

// Profile routes
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/verify-phone/send', [App\Http\Controllers\ProfileController::class, 'sendPhoneVerification'])->name('profile.send-phone-verification');
Route::post('/profile/verify-phone', [App\Http\Controllers\ProfileController::class, 'verifyPhone'])->name('profile.verify-phone');
Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');

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

// -------------------------------------------------------
// Public Form Builder Routes
// -------------------------------------------------------
Route::get('/forms/{slug}', [FormSubmissionController::class, 'show'])->name('form.show');
Route::post('/forms/{slug}', [FormSubmissionController::class, 'submit'])->name('form.submit');
Route::get('/forms/{slug}/thanks', [FormSubmissionController::class, 'thankYou'])->name('form.thankyou');
Route::get('/forms/{slug}/pay/{responseId}', [FormPaymentController::class, 'pay'])->name('form.payment.pay');
Route::post('/form-payment/initiate', [FormPaymentController::class, 'initiate'])->name('form.payment.initiate');
Route::post('/form-payment/success', [FormPaymentController::class, 'success'])->name('form.payment.success');
Route::post('/form-payment/fail', [FormPaymentController::class, 'fail'])->name('form.payment.fail');
Route::post('/form-payment/cancel', [FormPaymentController::class, 'cancel'])->name('form.payment.cancel');
Route::post('/form-payment/ipn', [FormPaymentController::class, 'ipn'])->name('form.payment.ipn');
