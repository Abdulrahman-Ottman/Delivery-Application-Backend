<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function store() : BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
