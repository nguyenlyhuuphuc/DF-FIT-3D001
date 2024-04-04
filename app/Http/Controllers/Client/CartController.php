<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add($productId, $qty = 1){
        $cart = session()->get('cart', []);

        $product = Product::find($productId);
        
        $cart[$product->id]  = [
            'name' => $product->name,
            'price' => $product->price,
            'image_url' => asset('images/'.$product->image),
            'qty' => ($cart[$product->id]['qty'] ?? 0) + $qty
        ];

        //Add cart into session
        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Add product success!',
            'totalItem' => count($cart)
        ]);
    }

    public function remove($productId){
        $cart = session()->get('cart', []);

        if(isset($cart[$productId])){
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);

        return response()->json(['message' => 'Remove product success!',]);
    }

    public function index(){
        $cart = session()->get('cart', []);
        
        return view('client.pages.cart', ['cart' => $cart]);
    }
}
