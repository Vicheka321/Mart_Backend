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
        Schema::create('audit_logs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Action
            |--------------------------------------------------------------------------
            */

            $table->enum('action', [
                'login',
                'logout',
                'create',
                'update',
                'delete',
                'restore',
                'approve',
                'reject',
                'assign_role',
                'remove_role',
                'export',
                'import',
                'payment_success',
                'payment_failed',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Module
            |--------------------------------------------------------------------------
            */

            $table->string('module',100);

            /*
            |--------------------------------------------------------------------------
            | Record
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('record_id')->nullable();

            $table->string('record_name')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Before / After
            |--------------------------------------------------------------------------
            */

            $table->json('old_values')->nullable();

            $table->json('new_values')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request
            |--------------------------------------------------------------------------
            */

            $table->string('method',10)->nullable();

            $table->text('url')->nullable();

            $table->ipAddress('ip_address')->nullable();

            $table->text('user_agent')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status',[
                'success',
                'failed',
                'warning'
            ])->default('success');

            /*
            |--------------------------------------------------------------------------
            | Extra
            |--------------------------------------------------------------------------
            */

            $table->string('device')->nullable();

            $table->string('browser')->nullable();

            $table->string('platform')->nullable();

            $table->string('country')->nullable();

            $table->string('city')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Created
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');

            $table->index('action');

            $table->index('module');

            $table->index('record_id');

            $table->index('status');

            $table->index('created_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};