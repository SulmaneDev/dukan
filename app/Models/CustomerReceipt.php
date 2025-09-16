<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CustomerReceipt extends Model
{
    protected $table = "customer_receipt";
    protected $fillable = [
        'date',
        'amount',
        'payment_method',
        'reference',
        'description',
        'customer_id',
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
                $receipt->reference = 'RCP-' . strtoupper(Str::random(8));
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
