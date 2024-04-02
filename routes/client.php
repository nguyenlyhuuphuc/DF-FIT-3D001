<?php

use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index'])->name('client.index');