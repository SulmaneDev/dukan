<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'imeis',
        'user_id',
        'price',
        'percent_discount',
        'fixed_discount',
        'coupon_discount',
        'description',
        'party_id',
        'party_type',
        'order_tax',
        'brand_id',
        'product_id',
    ];

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

    public function supplier()
    {
        return $this->morphTo();
    }
    public function party()
    {
        return $this->morphTo();
    }
}
