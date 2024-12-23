<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatus extends Model
{
    protected $fillable = ['order_number', 'status'];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_number', 'order_number');
    }
}
