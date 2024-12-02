<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
