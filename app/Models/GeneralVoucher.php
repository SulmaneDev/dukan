<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GeneralVoucher extends Model
{
    use HasFactory;

    protected $table = 'general_voucher';

    protected $fillable = [
        'date',
        'voucher_number',
        'description',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected static function booted()
    {
        static::creating(function ($receipt) {
            if (empty($receipt->voucher_number)) {
                $receipt->voucher_number = 'GV-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
