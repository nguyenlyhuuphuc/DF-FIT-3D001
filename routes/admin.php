<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

/** Product Category */
Route::prefix('admin/product_category')
->name('admin.product_category.')
->controller(ProductCategoryController::class)
->middleware('check.user.is.admin')
->group(function (){
    Route::get('index', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('store', 'store')->name('store');
    Route::post('delete/{id}', 'destroy')->name('destroy');
    Route::get('detail/{id}', 'detail')->name('detail');
    Route::post('update/{id}', 'update')->name('update');
    Route::post('slug', 'slug')->name('slug');
});

Route::resource('admin/product', ProductController::class,  ['as' => 'admin'])->middleware('check.user.is.admin');