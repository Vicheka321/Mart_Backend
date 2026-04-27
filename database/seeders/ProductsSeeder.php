<?php

namespace Database\Seeders;

use App\Models\ProductsModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            ProductsModel::create($item);
        }
    }
}
