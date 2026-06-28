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
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();

            // User (Guest = NULL)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Firebase Token
            $table->text('fcm_token')->unique();

            // Device Information
            $table->string('device_id')->nullable();
            $table->string('platform')->nullable(); // android / ios

            // Notification Topic
            $table->string('topic')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Last App Open
            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
