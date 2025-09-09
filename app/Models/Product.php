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
        "brand_id"=>"integer",
        "user_id"=>"integer",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function sales() {
        return $this->hasMany(Product::class);
    }

    public function brand(): BelongsTo {
        return $this->belongsTo(Brand::class);
    }
}
