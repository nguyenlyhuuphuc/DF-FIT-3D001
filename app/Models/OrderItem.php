<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_item';

    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id')->withTrashed();
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
}

