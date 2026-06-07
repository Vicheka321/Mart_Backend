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
        $productImages = [
            'https://m.media-amazon.com/images/I/715rFhZpV0L._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/612HeyYXOnL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/711DwRCax+L._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/51UYq7UwqrL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/61crDE1AJjL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/61Tbn-eDhVL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/61p+1+md+8L._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/71EybBZ-jpL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/61gq3kWYz3L._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/51ZNB3skixL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/81zI7nySasL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/61K6cQhw4EL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/515Ivb5YCCL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/31dYojQ7nRL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/71w25DflEoL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/91lRn852WJL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/51bHKet2shL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/712n0g5ATPL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/41pP0bG4eQL._AC_UL480_FMwebp_QL65_.jpg',
            'https://m.media-amazon.com/images/I/71bzIktCpVL._AC_UL480_FMwebp_QL65_.jpg',
            'https://ae-pic-a1.aliexpress-media.com/kf/Sf6991501f16f4ee0bd28da8ac8710cb4F.jpg_480x480q75.jpg_.avif'

        ];

        /*
        |--------------------------------------------------------------------------
        | Create 500 Fake Products
        |--------------------------------------------------------------------------
        */
        for ($i = 1; $i <= 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(1, 730));

            $product = ProductsModel::create([
                'categories_id' => rand(1, 50),
                'brand_id'      => rand(1, 50),
                'product_code'  => 'PRD' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name'          => fake()->words(2, true),
                'description'   => fake()->sentence(),
                'unit'          => fake()->randomElement([
                    'kg',
                    'piece',
                    'pack',
                    'bottle',
                    'bag',
                    'dozen',
                    'liter'
                ]),
                // 'cost_price'    => fake()->randomFloat(2, 0.50, 50),
                // 'sale_price'    => fake()->randomFloat(2, 1, 100),
                'cost_price'    => 0.01,
                'sale_price'    => 0.02,
                'quantity'      => rand(0, 500),
                'status'        => rand(0, 1),
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            // Create product image
            ProductsImageModel::create([
                'product_id' => $product->id,
                'image_url'  => $productImages[array_rand($productImages)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Fixed Sample Products
        |--------------------------------------------------------------------------
        */
        // $data = [
        //     ['categories_id' => 1, 'brand_id' => 1, 'product_code' => 'FP001', 'name' => 'Apple', 'description' => 'Fresh red apples', 'unit' => 'kg', 'cost_price' => 1.20, 'sale_price' => 1.80, 'quantity' => 150, 'status' => true],
        //     ['categories_id' => 1, 'brand_id' => 1, 'product_code' => 'FP002', 'name' => 'Banana', 'description' => 'Fresh ripe bananas', 'unit' => 'dozen', 'cost_price' => 1.00, 'sale_price' => 1.50, 'quantity' => 120, 'status' => true],

        //     ['categories_id' => 2, 'brand_id' => 2, 'product_code' => 'DA001', 'name' => 'Milk', 'description' => 'Fresh whole milk', 'unit' => 'liter', 'cost_price' => 1.10, 'sale_price' => 1.60, 'quantity' => 90, 'status' => true],
        //     ['categories_id' => 2, 'brand_id' => 2, 'product_code' => 'DA002', 'name' => 'Cheese', 'description' => 'Cheddar cheese block', 'unit' => 'pack', 'cost_price' => 2.50, 'sale_price' => 3.50, 'quantity' => 60, 'status' => true],

        //     ['categories_id' => 3, 'brand_id' => 3, 'product_code' => 'BK001', 'name' => 'Chocolate Cake', 'description' => 'Rich chocolate layered cake', 'unit' => 'piece', 'cost_price' => 8.00, 'sale_price' => 12.00, 'quantity' => 25, 'status' => true],
        //     ['categories_id' => 3, 'brand_id' => 3, 'product_code' => 'BK002', 'name' => 'Vanilla Sponge Cake', 'description' => 'Soft vanilla sponge cake', 'unit' => 'piece', 'cost_price' => 7.50, 'sale_price' => 11.00, 'quantity' => 20, 'status' => true],

        //     ['categories_id' => 4, 'brand_id' => 4, 'product_code' => 'SN001', 'name' => 'Potato Chips', 'description' => 'Crispy salted potato chips', 'unit' => 'bag', 'cost_price' => 0.50, 'sale_price' => 1.00, 'quantity' => 200, 'status' => true],
        //     ['categories_id' => 4, 'brand_id' => 4, 'product_code' => 'SN002', 'name' => 'Popcorn', 'description' => 'Butter flavored popcorn', 'unit' => 'bag', 'cost_price' => 0.45, 'sale_price' => 0.90, 'quantity' => 180, 'status' => true],

        //     ['categories_id' => 5, 'brand_id' => 5, 'product_code' => 'BV001', 'name' => 'Orange Juice', 'description' => 'Fresh orange juice drink', 'unit' => 'bottle', 'cost_price' => 0.70, 'sale_price' => 1.20, 'quantity' => 160, 'status' => true],
        //     ['categories_id' => 5, 'brand_id' => 5, 'product_code' => 'BV002', 'name' => 'Green Tea', 'description' => 'Refreshing bottled green tea', 'unit' => 'bottle', 'cost_price' => 0.60, 'sale_price' => 1.00, 'quantity' => 190, 'status' => true],
        // ];

        // foreach ($data as $item) {
        //     $createdAt = Carbon::now()->subDays(rand(1, 730));

        //     $product = ProductsModel::create(array_merge($item, [
        //         'created_at' => $createdAt,
        //         'updated_at' => $createdAt,
        //     ]));

            // ProductsImageModel::create([
            //     'product_id' => $product->id,
            //     'image_url'  => $productImages[array_rand($productImages)],
            //     'created_at' => $createdAt,
            //     'updated_at' => $createdAt,
            // ]);
        // }
    }
}