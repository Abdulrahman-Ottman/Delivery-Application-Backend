<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = ['product_id', 'user_id', 'quantity', 'order_number'];

    public function status() : HasOne
    {
        return $this->hasOne(OrderStatus::class, 'order_number', 'order_number');
    }
}
