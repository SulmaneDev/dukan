<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function brand(): HasMany
    {
        return $this->hasMany(Brand::class);
    }


    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function supplier(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function purchase(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchaseReturn(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }
    public function saleReturn(): HasMany
    {
        return $this->hasMany(SaleReturn::class);
    }
    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
    public function balance(): HasMany
    {
        return $this->hasMany(Balance::class);
    }
}
