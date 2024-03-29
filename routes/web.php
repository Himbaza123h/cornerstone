<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Homecontroller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::any('/', [PaymentController::class, 'index'])->name('landing.index');
Route::get('/donate', [PaymentController::class, 'view'])->name('donate.view');
Route::get('/who-we-are/about-us', [Homecontroller::class, 'index'])->name('who-we-are.about-us');
Route::get('/why-we-exist/history', [Homecontroller::class, 'history'])->name('why-we-exist.history');

Route::post('payment-store', [PaymentController::class, 'store'])->name('payment.store');


Route::get('payment-store/paypal', [PaymentController::class, 'store'])->name('paypal.payment.store');
Route::get('payment-store/paypal/success', [PaymentController::class, 'successTransaction'])->name('paypal.payment.successTransaction');
Route::get('payment-store/paypal/cancel', [PaymentController::class, 'cancelTransaction'])->name('paypal.payment.cancelTransaction');

