<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();

            // Prevent duplicate product in same promotion
            $table->unique(['promotion_id', 'product_id']);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('promotion_products');
    }
};
