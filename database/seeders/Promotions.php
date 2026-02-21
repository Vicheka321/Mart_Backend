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
            'discount_value' => 20, // 20% off
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(3),
            'status' => true
        ]);
        DB::table('promotion_products')->insert([
            [
                'promotion_id' => $promotion->id,
                'product_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'promotion_id' => $promotion->id,
                'product_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
