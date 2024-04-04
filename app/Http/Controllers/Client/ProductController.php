<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function detail(string $slug){
        $product = Product::where('slug', $slug)->firstOrFail();
        
        return view('client.pages.product-detail', ['product' => $product]);
    }
}
