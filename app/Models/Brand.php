<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        "name",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product():HasMany {
        return $this->hasMany(Product::class);
    }

    public function sale():HasMany {
        return $this->hasMany(Sale::class);
    }
}
