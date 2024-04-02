<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', function (){
    return view('client.pages.home');
});