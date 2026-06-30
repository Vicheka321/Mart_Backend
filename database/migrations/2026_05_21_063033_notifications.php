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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('message');

            // Target Audience
            $table->string('target')->default('all');

            // Optional image
            $table->string('image_url')->nullable();

            // Status
            $table->enum('status', [
                'pending',
                'scheduled',
                'sent',
                'failed'
            ])->default('pending');

            // Schedule
            $table->timestamp('scheduled_at')->nullable();

            // Sent time
            $table->timestamp('sent_at')->nullable();

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
