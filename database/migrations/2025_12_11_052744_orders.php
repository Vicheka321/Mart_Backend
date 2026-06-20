<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreignId('address_id')->constrained('address')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('delivery_address');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);

            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_method', ['cash', 'card', 'aba', 'wing', 'paypal', 'khqr']);

            $table->decimal('promotion_discount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->enum('coupon_type',['percent', 'fixed'])->nullable();

            $table->decimal('coupon_value', 10, 2)->default(0);
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->string('telegram_message_id')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
