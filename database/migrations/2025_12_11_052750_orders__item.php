<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('qty')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
            // Prevent duplicate product in same order
            $table->unique(['order_id', 'product_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
