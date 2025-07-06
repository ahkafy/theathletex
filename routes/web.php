<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\EventController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;




Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/register/{eventID}/one', [RegistrationController::class, 'otpForm'])->name('otp.form');
Route::post('/register/{eventID}/send', [RegistrationController::class, 'sendOTP'])->name('otp.send');
Route::post('/register/{eventID}/verify', [RegistrationController::class, 'verifyOTP'])->name('otp.verify');
Route::get('/register/{eventID}/two', [RegistrationController::class, 'registrationForm'])->name('register.create');
Route::post('/register/{eventID}/store', [RegistrationController::class, 'registerParticipant'])->name('register.store');

Route::get('/payment/{trxID}', [RegistrationController::class, 'paymentInit'])->name('payment.init');
Route::post('/payment/pay', [PaymentController::class, 'pay'])->name('payment.pay');

Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


