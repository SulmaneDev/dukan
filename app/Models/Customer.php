<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'code',
        'cnic_front_image',
        'cnic_back_image',
        'user_id',
    ];

    protected $casts = [
        'user_id' => "integer",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function saleReturn() {
        return $this->hasMany(SaleReturn::class);
    }

    public function purchases() {
        return $this->morphMany(Purchase::class,'party');
    }

}
