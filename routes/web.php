<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SupportController;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Support pages - FAQ is public, contact/tickets require auth
Route::get('support/faq', [SupportController::class, 'faq'])->name('support.faq');
Route::get('support/contact', [SupportController::class, 'contact'])->name('support.contact');

Route::middleware('auth')->group(function () {
    Route::post('support/contact', [SupportController::class, 'store'])->name('support.store');
    Route::get('support/tickets', [SupportController::class, 'tickets'])->name('support.tickets');
    Route::get('support/tickets/{ticket}', [SupportController::class, 'show'])->name('support.show');
});

// Sản phẩm: liệt kê + chi tiết
Route::resource('products', ProductController::class)->only(['index','show']);

// Giỏ hàng (session)
Route::get('cart', [CartController::class,'index'])->name('cart.index');
Route::post('cart/{product}', [CartController::class,'add'])->name('cart.add');
Route::patch('cart/{product}', [CartController::class,'update'])->name('cart.update');
Route::delete('cart/{product}', [CartController::class,'remove'])->name('cart.remove');

// Checkout, Payment, and Review routes (require login)
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('checkout', [CheckoutController::class,'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class,'store'])->name('checkout.store');
    
    // Reviews
    Route::post('products/{product}/reviews', [ReviewController::class,'store'])->name('reviews.store');
    
    // Payment Initiation & Status
    Route::get('payment/momo/{tempOrderId}', [PaymentController::class, 'momo'])->name('payment.momo');
    Route::get('payment/vnpay/{tempOrderId}', [PaymentController::class, 'vnpay'])->name('payment.vnpay');
    Route::get('payment/status/{tempOrderId}', [PaymentController::class, 'paymentStatus'])->name('payment.status');

    // Payment Display (QR Code pages)
    Route::get('payment/momo/qr/{tempOrderId}', [PaymentController::class, 'momoQr'])->name('payment.momo.qr');
    Route::get('payment/vnpay/qr/{tempOrderId}', [PaymentController::class, 'vnpayQr'])->name('payment.vnpay.qr');

    // Payment Result
    Route::get('payment/success/{order}', [PaymentController::class, 'success'])->name('checkout.success');
    Route::get('payment/error/{tempOrderId}', [PaymentController::class, 'error'])->name('payment.error');
    Route::get('payment/cancel/{tempOrderId}', [PaymentController::class, 'cancelPayment'])->name('payment.cancel');
});

// Payment Callbacks (from gateways - do not require auth middleware but are part of payment flow)
Route::get('payment/momo/callback', [PaymentController::class, 'momoCallback'])->name('payment.momo.callback');
Route::post('payment/momo/ipn', [PaymentController::class, 'momoIpn'])->name('payment.momo.ipn');
Route::get('payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');


// Client account routes (require auth)
Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('orders', [OrderHistoryController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderHistoryController::class, 'show'])->name('orders.show');
    Route::get('vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
});

// Khu vực Admin (đã tạo middleware alias 'admin')
Route::prefix('admin')->middleware(['auth','admin'])->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
});

// Route đăng nhập/đăng ký của Breeze
require __DIR__.'/auth.php';
