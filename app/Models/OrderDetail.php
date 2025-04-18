<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'price',
        'quantity',
        'subtotal',
        'recipient_name', // Tên người nhận
        'recipient_phone', // Số điện thoại người nhận
        'recipient_address', // Địa chỉ giao hàng
        'province_code', // Mã tỉnh
        'district_code', // Mã quận/huyện
        'payment_method', // Phương thức thanh toán
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
