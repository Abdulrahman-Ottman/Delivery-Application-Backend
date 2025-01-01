<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable=['path','product_id','is_main'];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
