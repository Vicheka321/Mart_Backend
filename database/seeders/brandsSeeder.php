<?php

namespace Database\Seeders;

use App\Models\BrandModel;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class brandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Coca Cola',
                'image' => 'https://www.coca-cola.com/content/dam/onexp/kh/en/brands/coca-cola/coca-cola-original-taste.png/width1338.png',
            ],
            [
                'name' => 'Pepsi',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQG23HKqdTKzlJ_jbPvI9SVuloknfL4e76vJZfLBoFBuaFweIh1h0QtGElH&s=10',
            ],
            [
                'name' => 'Eau Kulen',
                'image' => 'https://www.fstwmart.com/upload/product/202210051327170.jpg',
            ],
            [
                'name' => 'Buldak Noodle (Black)',
                'image' => 'https://i5.walmartimages.com/seo/Buldak-Artifical-Spicy-Chicken-Flavour-Ramen-Noodles-700g_c4afcca3-9f77-4b18-837e-d2314e4bdf4f.c3ca58e24148ede077a2381258042229.jpeg',
            ],
            [
                'name' => 'Buldak Noodle (Pink)',
                'image' => 'https://i5.walmartimages.com/seo/Samyang-Spicy-Chicken-Noodle-Carbonara-Flavor-Ramen-4-58-oz-5-Pack_062ca712-fd56-43fc-af93-84b9f38fc873.0f0d66a110f8f69aed8255038bb82628.jpeg',
            ],
            [
                'name' => 'Buldak Noodle (Red spicy x2)',
                'image' => 'https://i5.walmartimages.com/seo/3-7-oz-PACK-OF-1-Samyang-Buldak-2X-Spicy-Hot-Chicken-Flavor-Instant-Ramen_31104ec4-00ed-4f71-afe0-3d19d1c97e9c.7c536707010b20e1c45d2946e1416429.jpeg',
            ],
            [
                'name' => 'Julies Cheese Sandwich',
                'image' => 'https://daganghalal.blob.core.windows.net/19165/Product/julies-cheese-sandwich-168g-1723105111431.jpg',
            ],
            [
                'name' => 'Koreno Noddle',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSixnJJA-He5vQ9ihbGI8LIay8h5mfEj-7xoJqrtSZ8kIE3Xlq6KiF-jEX4&s=10',
            ],
            [
                'name' => 'Ajinomoto',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTemtVFUn6-geEUIACVHkTF2K0b7CrC8uzH1lghKGu937tmA421dNjKSCs&s=10',
            ],
            [
                'name' => 'Mistine ACNE CLEAR Facial Foam',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgtwPSsUa6UnBH78E2jhBdV8I3GdCOFUah44CedBu552kJstd-I0-8nl-M&s=10',
            ],
        ];
        
        foreach ($brands as $brand) {

            $createdAt = Carbon::now()->subDays(rand(1, 730));

            BrandModel::create([
                'name' => $brand['name'],
                'image' => $brand['image'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
