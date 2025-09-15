<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'code',
        'brand_id',
        'user_id',
    ];

    protected $casts = [
        "brand_id" => "integer",
        "user_id" => "integer",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function saleReturn() {
        return $this->hasMany(SaleReturn::class);
    }

    public function purchaseReturn() {
        return $this->hasMany(PurchaseReturn::class);
    }

    public function balance()
    {
        return $this->hasMany(Balance::class, 'product_id', 'id');
    }
}
