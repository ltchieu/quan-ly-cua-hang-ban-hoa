<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ProductController as AdminProduct;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sản phẩm: liệt kê + chi tiết
Route::resource('products', ProductController::class)->only(['index','show']);

// Giỏ hàng (session)
Route::get('cart', [CartController::class,'index'])->name('cart.index');
Route::post('cart/{product}', [CartController::class,'add'])->name('cart.add');
Route::patch('cart/{product}', [CartController::class,'update'])->name('cart.update');
Route::delete('cart/{product}', [CartController::class,'remove'])->name('cart.remove');

// Checkout + Review yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('checkout', [CheckoutController::class,'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class,'store'])->name('checkout.store');
    Route::post('products/{product}/reviews', [ReviewController::class,'store'])->name('reviews.store');
});

// Khu vực Admin (đã tạo middleware alias 'admin')
Route::prefix('admin')->middleware(['auth','admin'])->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
});

// Route đăng nhập/đăng ký của Breeze
require __DIR__.'/auth.php';
