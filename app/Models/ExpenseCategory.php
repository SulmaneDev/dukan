<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExpenseCategory extends Model
{
    use  HasFactory;
    protected $table = 'expense_categories';

    protected $fillable = [
        'name',
        'code',
        'description',
        'user_id'
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                do {
                    $code = strtoupper(Str::random(6));
                } while (self::where('code', $code)->exists());

                $model->code = $code;
            }
        });
    }
}
