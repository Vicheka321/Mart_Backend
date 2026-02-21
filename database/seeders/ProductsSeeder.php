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
            [
                'categories_id' => 1, // Drinks
                'brand_id' => 1, // Coca-Cola
                'product_code' => 'DRINK001',
                'name' => 'Coca Cola 330ml',
                'description' => 'Carbonated soft drink',
                'unit' => 'bottle',
                'cost_price' => 0.40,
                'sale_price' => 0.60,
                'quantity' => 100,
                'status' => true
            ],
            [
                'categories_id' => 1,
                'brand_id' => 2, // Pepsi
                'product_code' => 'DRINK002',
                'name' => 'Pepsi 330ml',
                'description' => 'Refreshing cola drink',
                'unit' => 'bottle',
                'cost_price' => 0.40,
                'sale_price' => 0.60,
                'quantity' => 120,
                'status' => true
            ],
            [
                'categories_id' => 2, // Snacks
                'brand_id' => 4, // Oishi
                'product_code' => 'SNACK001',
                'name' => 'Oishi Prawn Crackers',
                'description' => 'Crispy snack',
                'unit' => 'pack',
                'cost_price' => 0.30,
                'sale_price' => 0.50,
                'quantity' => 200,
                'status' => true
            ],
            [
                'categories_id' => 3, // Groceries
                'brand_id' => 3, // Nestle
                'product_code' => 'GROC001',
                'name' => 'Nestle Milk 1L',
                'description' => 'Fresh milk',
                'unit' => 'box',
                'cost_price' => 0.90,
                'sale_price' => 1.20,
                'quantity' => 80,
                'status' => true
            ],
            [
                'categories_id' => 2, // Household
                'brand_id' => 4, // P&G
                'product_code' => 'HOUSE001',
                'name' => 'Tide Detergent 1kg',
                'description' => 'Laundry detergent powder',
                'unit' => 'bag',
                'cost_price' => 3.00,
                'sale_price' => 3.80,
                'quantity' => 60,
                'status' => true
            ],
        ];
        foreach ($data as $item) {
            ProductsModel::create($item);
        }

    }
}
