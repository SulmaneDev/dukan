<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $fillable = [
        'purchase_id',
        'user_id',
        'party_id',
        'party_type',
        'brand_id',
        'product_id',
        'imeis',
        'qty',
        'price',
        'fixed_discount',
        'reason',
    ];

    protected $casts = [
        'imeis' => 'array',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function party()
    {
        return $this->morphTo();
    }
}
