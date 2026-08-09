<?php

namespace Database\Seeders;

use App\Models\ProductsModel;
use App\Models\PromotionModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Promotions extends Seeder
{
    public function run(): void
    {
        $promotionNames = [
            'Weekend Sale',
            'Flash Sale',
            'Mega Discount',
            'Happy Hour',
            'Special Offer',
            'Super Deal',
        ];

        // foreach ($promotionNames as $name) {

        //     $promotion = PromotionModel::create([
        //         'name' => $name,
        //         'discount_type' => rand(0, 1)
        //             ? 'percent'
        //             : 'fixed',

        //         'discount_value' => rand(5, 50),

        //         'start_date' => now(),

        //         'end_date' => now()->addDays(rand(3, 15)),

        //         'status' => true,
        //     ]);

        //     $productIds = ProductsModel::inRandomOrder()
        //         ->limit(rand(10, 30))
        //         ->pluck('id');

        //     foreach ($productIds as $productId) {

        //         DB::table('promotion_products')->insert([
        //             'promotion_id' => $promotion->id,
        //             'product_id' => $productId,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }
        $discountValues = [10, 20, 30, 40, 50];

        foreach ($promotionNames as $name) {

            $promotion = PromotionModel::create([
                'name' => $name,

                'discount_type' => rand(0, 1)
                    ? 'percent'
                    : 'fixed',

                'discount_value' => $discountValues[array_rand($discountValues)],

                'start_date' => now(),

                'end_date' => now()->addDays(rand(3, 15)),

                'status' => true,
            ]);

            $productIds = ProductsModel::inRandomOrder()
                ->limit(rand(10, 30))
                ->pluck('id');

            foreach ($productIds as $productId) {

                DB::table('promotion_products')->insert([
                    'promotion_id' => $promotion->id,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
