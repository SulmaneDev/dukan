<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $fillable = [
        'sale_id',
        'user_id',
        'customer_id',
        'product_id',
        'brand_id',
        'imeis',
        'qty',
        'price',
        'fixed_discount',
        'reason',
    ];

    protected $casts = [
        'imeis' => 'array',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'product_id' => 'integer',
        'brand_id' => 'integer',
        'price' => 'float',
        'fixed_discount' => 'float',
        'qty' => 'integer',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
