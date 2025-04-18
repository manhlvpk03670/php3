<?php

// app/Models/Coupon.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_percent',
        'discount_amount',
        'min_order_value',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isValid()
    {
        // Check if coupon has expired
        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }

        return true;
    }

    public function getDiscountAmount($orderTotal)
    {
        // Check if minimum order value is met
        if ($orderTotal < $this->min_order_value) {
            return 0;
        }

        // Calculate discount based on percentage or fixed amount
        if ($this->discount_percent > 0) {
            return ($orderTotal * $this->discount_percent) / 100;
        }
        
        return $this->discount_amount;
    }
}