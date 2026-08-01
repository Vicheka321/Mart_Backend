<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PromotionModel;

class Promotions extends Seeder
{
    public function run(): void
    {
        $promotion = PromotionModel::create([
            'name' => 'Weekend Sale',
            'discount_type' => 'percent',
            'discount_value' => 20,
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'status' => true,
        ]);

        // Random 20 products from IDs 1-50
        $productIds = collect(range(1, 50))
            ->shuffle()
            ->take(20);

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