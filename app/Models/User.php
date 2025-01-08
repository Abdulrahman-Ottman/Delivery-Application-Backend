<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'password',
        'first_name',
        'last_name',
        'location',
        'role_id',
        'phone_verified_at',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function cartItems() : HasMany
    {
        return $this->hasMany(Cart::class);
    }
    public function orders() :HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function favorites() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }
    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    public function store() : HasOne{
        return $this->hasOne(Store::class);
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',

        ];
    }
}
