<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'date',
        'amount',
        'payment_method',
        'reference',
        'description',
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($expense) {
            if (empty($expense->reference)) {
                $expense->reference = 'EXP-' . strtoupper(Str::random(8));
            }
        });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
