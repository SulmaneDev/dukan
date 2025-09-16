<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'address',
        'shipping_address',
        'city',
        'code',
        'user_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receipt(): HasMany
    {
        return $this->hasMany(SupplierReceipt::class);
    }

    public function purchases()
    {
        return $this->morphMany(Purchase::class, 'party');
    }

    public function purchaseReturn()
    {
        return $this->morphMany(PurchaseReturn::class, 'party');
    }
      public function balance()
    {
        return $this->hasMany(Balance::class, 'supplier_id', 'id');
    }
}
