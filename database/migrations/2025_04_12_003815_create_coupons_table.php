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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_percent', 5, 2)->nullable(); // Giảm theo %
            $table->decimal('discount_amount', 10, 2)->nullable(); // Giảm theo số tiền
            $table->decimal('min_order_value', 10, 2)->default(0); // Giá trị tối thiểu đơn hàng
            $table->dateTime('expires_at');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
