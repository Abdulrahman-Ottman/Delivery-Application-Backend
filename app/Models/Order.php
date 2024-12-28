<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'accepted_at', 'distance'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('ordered_quantity');
    }
}
