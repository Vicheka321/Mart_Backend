<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Coupon code (e.g. SAVE10)
            $table->string('code')->unique();

            // Optional display name
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            // Discount type
            $table->enum('discount_type', ['percent', 'fixed']);

            // Discount value
            $table->decimal('discount_value', 10, 2);

            // Minimum order amount required to use this coupon
            $table->decimal('min_order_amount', 10, 2)->nullable();

            // Maximum discount allowed (optional)
            $table->decimal('max_discount', 10, 2)->nullable();

            // Usage limits
            $table->unsignedInteger('usage_limit')->nullable(); // null = unlimited
            $table->unsignedInteger('used_count')->default(0);

            // User limits
            $table->unsignedInteger('usage_limit_per_user')->nullable(); // null = unlimited

            // Active period
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Active / Inactive
            $table->boolean('status')->default(true);

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
