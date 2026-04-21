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
            $table->foreignId('address_id')->constrained('address')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'cancelled', 'completed'])->default('pending');
            $table->string('telegram_message_id')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
