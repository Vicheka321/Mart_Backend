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
        Schema::create('khqr_payments', function (Blueprint $table) {
            $table->id();
            $table->string('md5', 32)->unique(); // MD5 is always 32 characters
            $table->text('qr_code'); // Use text instead of varchar(1000)
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('bill_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('store_label')->nullable();
            $table->string('terminal_label')->nullable();
            $table->string('merchant_name')->nullable();
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED', 'EXPIRED'])->default('PENDING');
            $table->json('bakong_response')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('paid_at')->nullable();
            $table->integer('check_attempts')->default(0);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index(['status', 'last_checked_at']);
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
