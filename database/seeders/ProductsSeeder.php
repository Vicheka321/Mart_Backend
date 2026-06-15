<?php

namespace Database\Seeders;

use App\Models\ProductsModel;
use App\Models\ProductsImageModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fake image URLs
        // $productImages = [
        //     'https://m.media-amazon.com/images/I/715rFhZpV0L._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/612HeyYXOnL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/711DwRCax+L._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/61Tbn-eDhVL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/51ZNB3skixL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/71w25DflEoL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg',
        //     'https://ae-pic-a1.aliexpress-media.com/kf/Sf6991501f16f4ee0bd28da8ac8710cb4F.jpg_480x480q75.jpg_.avif'

        // ];

        // /*
        // |--------------------------------------------------------------------------
        // | Create 500 Fake Products
        // |--------------------------------------------------------------------------
        // */
        // for ($i = 1; $i <= 50; $i++) {
        //     $createdAt = Carbon::now()->subDays(rand(1, 730));

        //     $product = ProductsModel::create([
        //         'categories_id' => rand(1, 50),
        //         'brand_id'      => rand(1, 50),
        //         'product_code'  => 'PRD' . str_pad($i, 4, '0', STR_PAD_LEFT),
        //         'name'          => fake()->words(2, true),
        //         'description'   => fake()->sentence(),
        //         'unit'          => fake()->randomElement([
        //             'kg',
        //             'piece',
        //             'pack',
        //             'bottle',
        //             'bag',
        //             'dozen',
        //             'liter'
        //         ]),
        //         // 'cost_price'    => fake()->randomFloat(2, 0.50, 50),
        //         // 'sale_price'    => fake()->randomFloat(2, 1, 100),
        //         'cost_price'    => 0.01,
        //         'sale_price'    => 0.02,
        //         'quantity'      => rand(0, 500),
        //         'status'        => rand(0, 1),
        //         'created_at'    => $createdAt,
        //         'updated_at'    => $createdAt,
        //     ]);

        //     // Create product image
        //     ProductsImageModel::create([
        //         'product_id' => $product->id,
        //         'image_url'  => $productImages[array_rand($productImages)],
        //         'created_at' => $createdAt,
        //         'updated_at' => $createdAt,
        //     ]);
        // }

        $productData = [
            [
                'name' => 'Coca Cola 330ml',
                'category' => 1,
                'brand' => 1,
                'image' => 'https://m.media-amazon.com/images/I/715rFhZpV0L.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Pepsi 330ml',
                'category' => 1,
                'brand' => 2,
                'image' => 'https://m.media-amazon.com/images/I/612HeyYXOnL.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Milo Chocolate Drink',
                'category' => 3,
                'brand' => 4,
                'image' => 'https://m.media-amazon.com/images/I/711DwRCax+L.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Nescafe Classic',
                'category' => 1,
                'brand' => 5,
                'image' => 'https://m.media-amazon.com/images/I/51UYq7UwqrL.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Oishi Prawn Crackers',
                'category' => 2,
                'brand' => 6,
                'image' => 'https://m.media-amazon.com/images/I/61crDE1AJjL.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Lays Classic Chips',
                'category' => 2,
                'brand' => 7,
                'image' => 'https://m.media-amazon.com/images/I/61Tbn-eDhVL.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Anchor Full Cream Milk',
                'category' => 3,
                'brand' => 10,
                'image' => 'https://m.media-amazon.com/images/I/61p+1+md+8L.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Dutch Mill Yogurt',
                'category' => 3,
                'brand' => 9,
                'image' => 'https://m.media-amazon.com/images/I/71EybBZ-jpL.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Colgate Toothpaste',
                'category' => 9,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/61gq3kWYz3L.*AC_UL480_FMwebp_QL65*.jpg'
            ],
            [
                'name' => 'Dettol Hand Wash',
                'category' => 10,
                'brand' => 3,
                'image' => 'https://m.media-amazon.com/images/I/51ZNB3skixL.*AC_UL480_FMwebp_QL65*.jpg'
            ],
        ];

        foreach ($productData as $index => $item) {

            $createdAt = now()->subDays(rand(1, 730));

            $product = ProductsModel::create([
                'categories_id' => $item['category'],
                'brand_id'      => $item['brand'],
                'product_code'  => 'PRD' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'name'          => $item['name'],
                'description'   => $item['name'],
                'unit'          => 'piece',
                // 'cost_price'    => rand(1, 20),
                // 'sale_price'    => rand(2, 30),
                'cost_price'    => 0.01,
                'sale_price'    => 0.02,
                'quantity'      => rand(10, 500),
                'status'        => true,
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            ProductsImageModel::create([
                'product_id' => $product->id,
                'image_url'  => $item['image'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
