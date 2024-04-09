<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MinusQtyOfProductListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderSuccessEvent $event): void
    {
        $order = $event->order;
        foreach($order->orderItems as $item){
            $product = Product::find($item->product_id);
            $product->qty = (int)$product->qty - (int)$item->qty;
            $product->save();
        }
    }
}
