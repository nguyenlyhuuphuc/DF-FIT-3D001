<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\GoogleController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProductController;
use App\Mail\EmailConfirmOrderCustomer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('home', [HomeController::class, 'index'])->name('client.index');
Route::get('cart/add-product/{product}/{qty?}', [CartController::class, 'add'])->name('cart.add.product')->middleware('auth');
Route::get('cart/remove-product/{product}', [CartController::class, 'remove'])->name('cart.remove.product');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::get('product/{slug}', [ProductController::class, 'detail'])->name('product.detail');

Route::get('checkout', [OrderController::class, 'index'])->name('order.checkout')->middleware('auth');
Route::post('place-order', [OrderController::class, 'placeOrder'])->name('order.place-order')->middleware('auth');

Route::get('google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('vnpay/callback', [OrderController::class, 'vnpayCallback'])->name('vnpay.callback');

Route::get('test-send-email', function (){
    $order = \App\Models\Order::find(8);
    // dd($order->user->email);
    Mail::to('nguyenlyhuuphucwork@gmail.com')->send(new EmailConfirmOrderCustomer($order));
});