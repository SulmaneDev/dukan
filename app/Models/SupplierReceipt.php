<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SupplierReceipt extends Model
{
    protected $table = "supplier_receipt";
    protected $fillable = [
        'date',
        'amount',
        'payment_method',
        'reference',
        'description',
        'supplier_id',
        'user_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($receipt) {
            if (empty($receipt->reference)) {
                $receipt->reference = 'SUP-' . strtoupper(Str::random(8));
            }
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
