<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ad extends Model
{
    protected $fillable = ['image' , 'store_id'];
    public  function store() : BelongsTo{
        return $this->belongsTo(Store::class);
    }
}
