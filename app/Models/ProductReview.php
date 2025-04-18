<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}

