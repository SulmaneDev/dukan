<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        "imeis",
        "user_id",
        "customer_id",
        "product_id",
        "brand_id",
        "price",
        "fixed_discount",
        "description",
        "qty",
        'reference_id'
    ];

    protected $casts = [
        "imeis" => "array",
        "user_id" => "integer",
        "customer_id" => "integer",
        "product_id" => "integer",
        "brand_id" => "integer",
        "price" => "float",
        "fixed_discount" => "float",
        "qty" => "integer",
    ];

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
}
