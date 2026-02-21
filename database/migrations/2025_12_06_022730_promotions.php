<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();    
            $table->string('image_url')->nullable(); 
            $table->enum('discount_type', ['percent', 'fixed']);
            $table->decimal('discount_value', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
