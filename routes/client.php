<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('home', [HomeController::class, 'index'])->name('client.index');
Route::get('cart/add-product/{product?}', [CartController::class, 'add'])->name('cart.add.product');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');