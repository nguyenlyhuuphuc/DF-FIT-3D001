<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/nguyen-van-a', function (){
    echo 'Nguyen Van B';
});

Route::get('product/detail/{productId?}', function($productId = 1){
    echo $productId;
});

Route::get('test', function (){
    return view('Test.SubTest.test');
});

Route::get('sinh-vien', function(){
    return view('sinhvien.sinhvien');
});

Route::get('wikipedia', function(){
    return view('wikipedia');
});

Route::get('product', function(){
    return view('pages.product');
});
Route::get('about-us', function(){
    return view('pages.about-us');
});
Route::get('article', function(){
    return view('pages.article');
});