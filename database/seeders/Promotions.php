<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PromotionModel;
use Carbon\Carbon;

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

        $productIds = collect(range(1, 10))
            ->shuffle()
            ->take(8);

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
