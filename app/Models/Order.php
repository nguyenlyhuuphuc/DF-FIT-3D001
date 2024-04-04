<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order';

    protected $guarded = [];

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'order_id')->withTrashed();
    }

    public function orderPaymentMethods(){
        return $this->hasMany(OrderPaymentMethod::class, 'order_id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
