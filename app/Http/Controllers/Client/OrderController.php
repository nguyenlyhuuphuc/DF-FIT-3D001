<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(){
        $cart = session()->get('cart', []);

        return view('client.pages.checkout', ['cart' => $cart]);
    }

    public function placeOrder(PlaceOrderRequest $request){
        //Save Order
        $order = new Order();
        $order->name = $request->name;
        $order->address = $request->address;
        $order->note = $request->note;
        $order->save(); //insert

        //Save Order Items
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        if(!empty($cart)){
            foreach ($cart as $key => $value) {
                $totalPrice += (float)$value['qty'] * (float)$value['price'];
                $orderItem = new OrderItem();        
                $orderItem->price = $value['price'];
                $orderItem->qty = $value['qty'];
                $orderItem->name = $value['name'];
                $orderItem->image = $value['image_url'];
                $orderItem->product_id = $key;
                $orderItem->order_id = $order->id;
                $orderItem->save();
            }
        }

        $order->total = $totalPrice;
        $order->save(); //update
        

        //Save ORder Payment Method
        $orderPaymentMethod = new OrderPaymentMethod();
        $orderPaymentMethod->payment_method = $request->payment_method;
        $orderPaymentMethod->status = 'pending';
        $orderPaymentMethod->order_id = $order->id;
        $orderPaymentMethod->total = $totalPrice;
        $orderPaymentMethod->save();

        //Reset Cart
        session()->put('cart', []);

        //Update phone of User
        $user = Auth::check() ? Auth::user() : null;
        if(!is_null($user)){
            $user->phone = $request->phone;
            $user->save();
        }
    }
}
