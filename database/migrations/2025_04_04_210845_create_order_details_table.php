<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2);
            
            // Thêm các cột cho thông tin giao hàng
            $table->string('recipient_name'); // Tên người nhận
            $table->string('recipient_phone'); // Số điện thoại người nhận
            $table->string('recipient_address'); // Địa chỉ giao hàng
            $table->unsignedBigInteger('province_code'); // Mã tỉnh
            $table->unsignedBigInteger('district_code'); // Mã quận/huyện
            $table->string('payment_method'); // Phương thức thanh toán
            
            $table->timestamps();
        
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
