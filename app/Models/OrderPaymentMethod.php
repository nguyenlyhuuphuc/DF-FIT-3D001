<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_payment_method';

    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id')->withTrashed();
    }
}
