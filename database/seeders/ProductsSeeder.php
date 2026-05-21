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
            'https://images.unsplash.com/photo-1542838132-92c53300491e',
            'https://images.unsplash.com/photo-1586201375761-83865001e31c',
            'https://images.unsplash.com/photo-1604719312566-8912e9227c6a',
            'https://images.unsplash.com/photo-1615485290382-441e4d049cb5',
            'https://images.unsplash.com/photo-1573246123716-6b1782bfc499',
            'https://images.unsplash.com/photo-1567306226416-28f0efdc88ce',
            'https://images.unsplash.com/photo-1621939514649-280e2ee25f60',
            'https://images.unsplash.com/photo-1592928302636-c83cf1e1a1f5',
            'https://images.unsplash.com/photo-1603569283847-aa295f0d016a',
            'https://images.unsplash.com/photo-1580910051074-3eb694886505',
        ];

        /*
        |--------------------------------------------------------------------------
        | Create 500 Fake Products
        |--------------------------------------------------------------------------
        */
        for ($i = 1; $i <= 500; $i++) {
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
                'cost_price'    => fake()->randomFloat(2, 0.50, 50),
                'sale_price'    => fake()->randomFloat(2, 1, 100),
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
        $data = [
            ['categories_id' => 1, 'brand_id' => 1, 'product_code' => 'FP001', 'name' => 'Apple', 'description' => 'Fresh red apples', 'unit' => 'kg', 'cost_price' => 1.20, 'sale_price' => 1.80, 'quantity' => 150, 'status' => true],
            ['categories_id' => 1, 'brand_id' => 1, 'product_code' => 'FP002', 'name' => 'Banana', 'description' => 'Fresh ripe bananas', 'unit' => 'dozen', 'cost_price' => 1.00, 'sale_price' => 1.50, 'quantity' => 120, 'status' => true],

            ['categories_id' => 2, 'brand_id' => 2, 'product_code' => 'DA001', 'name' => 'Milk', 'description' => 'Fresh whole milk', 'unit' => 'liter', 'cost_price' => 1.10, 'sale_price' => 1.60, 'quantity' => 90, 'status' => true],
            ['categories_id' => 2, 'brand_id' => 2, 'product_code' => 'DA002', 'name' => 'Cheese', 'description' => 'Cheddar cheese block', 'unit' => 'pack', 'cost_price' => 2.50, 'sale_price' => 3.50, 'quantity' => 60, 'status' => true],

            ['categories_id' => 3, 'brand_id' => 3, 'product_code' => 'BK001', 'name' => 'Chocolate Cake', 'description' => 'Rich chocolate layered cake', 'unit' => 'piece', 'cost_price' => 8.00, 'sale_price' => 12.00, 'quantity' => 25, 'status' => true],
            ['categories_id' => 3, 'brand_id' => 3, 'product_code' => 'BK002', 'name' => 'Vanilla Sponge Cake', 'description' => 'Soft vanilla sponge cake', 'unit' => 'piece', 'cost_price' => 7.50, 'sale_price' => 11.00, 'quantity' => 20, 'status' => true],

            ['categories_id' => 4, 'brand_id' => 4, 'product_code' => 'SN001', 'name' => 'Potato Chips', 'description' => 'Crispy salted potato chips', 'unit' => 'bag', 'cost_price' => 0.50, 'sale_price' => 1.00, 'quantity' => 200, 'status' => true],
            ['categories_id' => 4, 'brand_id' => 4, 'product_code' => 'SN002', 'name' => 'Popcorn', 'description' => 'Butter flavored popcorn', 'unit' => 'bag', 'cost_price' => 0.45, 'sale_price' => 0.90, 'quantity' => 180, 'status' => true],

            ['categories_id' => 5, 'brand_id' => 5, 'product_code' => 'BV001', 'name' => 'Orange Juice', 'description' => 'Fresh orange juice drink', 'unit' => 'bottle', 'cost_price' => 0.70, 'sale_price' => 1.20, 'quantity' => 160, 'status' => true],
            ['categories_id' => 5, 'brand_id' => 5, 'product_code' => 'BV002', 'name' => 'Green Tea', 'description' => 'Refreshing bottled green tea', 'unit' => 'bottle', 'cost_price' => 0.60, 'sale_price' => 1.00, 'quantity' => 190, 'status' => true],
        ];

        foreach ($data as $item) {
            $createdAt = Carbon::now()->subDays(rand(1, 730));

            $product = ProductsModel::create(array_merge($item, [
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]));

            ProductsImageModel::create([
                'product_id' => $product->id,
                'image_url'  => $productImages[array_rand($productImages)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}