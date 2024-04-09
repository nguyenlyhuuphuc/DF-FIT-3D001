<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderSuccessEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Mail\EmailConfirmOrderAdmin;
use App\Mail\EmailConfirmOrderCustomer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(){
        $cart = session()->get('cart', []);

        return view('client.pages.checkout', ['cart' => $cart]);
    }

    public function placeOrder(PlaceOrderRequest $request){

        DB::beginTransaction();
        try{
            $totalPrice = 0;
            foreach (session()->get('cart', []) as $key => $value) {
                $totalPrice += (float)$value['qty'] * (float)$value['price'];
            }
                
            //Save Order
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->address = $request->address;
            $order->note = $request->note;
            $order->total = $totalPrice;
            $order->save();
    
            //Save Order Items
            $cart = session()->get('cart', []);
            if(!empty($cart)){
                foreach ($cart as $key => $value) {
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
            
            //Save Order Payment Method
            $orderPaymentMethod = new OrderPaymentMethod();
            $orderPaymentMethod->payment_method = $request->payment_method;
            $orderPaymentMethod->status = 'pending';
            $orderPaymentMethod->order_id = $order->id;
            $orderPaymentMethod->total = $totalPrice;
            $orderPaymentMethod->save();
    
            //Update phone of User
            $user = Auth::user();
            $user->phone = $request->phone;
            $user->save();
    
            //Reset Cart
            session()->put('cart', []);

            event(new OrderSuccessEvent($order)); //public event

            DB::commit();

            return redirect()->route('client.index')->with('msg', 'Order thanh cong');
        }catch(Exception $e){
            DB::rollBack();
            // throw new Exception($e->getMessage());
            return redirect()->route('client.index')->with('msg', 'Order that bai');
        }
    }
}
