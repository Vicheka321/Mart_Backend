<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CouponModel;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CouponModel::truncate();

        CouponModel::insert([
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => '10% off for new customers',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'min_order_amount' => 15,
                'max_discount' => 20,
                'usage_limit' => 1000,
                'usage_limit_per_user' => 1,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addMonths(3),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'SAVE20',
                'name' => 'Save $20',
                'description' => '$20 off orders over $100',
                'discount_type' => 'fixed',
                'discount_value' => 20,
                'min_order_amount' => 100,
                'max_discount' => null,
                'usage_limit' => 500,
                'usage_limit_per_user' => 2,
                'used_count' => 25,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(30),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'FLASH50',
                'name' => 'Flash Sale',
                'description' => '50% off limited time',
                'discount_type' => 'percent',
                'discount_value' => 50,
                'min_order_amount' => 50,
                'max_discount' => 100,
                'usage_limit' => 200,
                'usage_limit_per_user' => 1,
                'used_count' => 150,
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(2),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'EXPIRED10',
                'name' => 'Expired Coupon',
                'description' => 'Expired coupon example',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'min_order_amount' => 20,
                'max_discount' => 40,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'used_count' => 80,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->subDays(5),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'DISABLED15',
                'name' => 'Disabled Coupon',
                'description' => 'Inactive coupon',
                'discount_type' => 'fixed',
                'discount_value' => 15,
                'min_order_amount' => 50,
                'max_discount' => null,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'used_count' => 10,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(30),
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping Promo',
                'description' => 'No expiry coupon',
                'discount_type' => 'fixed',
                'discount_value' => 5,
                'min_order_amount' => 25,
                'max_discount' => null,
                'usage_limit' => null,
                'usage_limit_per_user' => null,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}