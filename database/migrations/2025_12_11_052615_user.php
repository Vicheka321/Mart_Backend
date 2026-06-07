<?php

use Google\Service\SaaSServiceManagement\Unit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('facebook_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('password')->nullable();
            $table->text('fcm_token')->nullable();
            $table->enum('role', ['admin', 'customer', 'staff'])->default('customer');
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
