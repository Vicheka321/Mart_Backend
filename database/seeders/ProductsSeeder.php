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

            // Drinks
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR001','name'=>'Coca Cola 330ml','description'=>'Soft drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>100,'status'=>true],
            ['categories_id'=>1,'brand_id'=>2,'product_code'=>'DR002','name'=>'Pepsi 330ml','description'=>'Cola drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>120,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR003','name'=>'Sprite 330ml','description'=>'Lemon soda','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>90,'status'=>true],
            ['categories_id'=>1,'brand_id'=>2,'product_code'=>'DR004','name'=>'Fanta Orange','description'=>'Orange soda','unit'=>'bottle','cost_price'=>0.45,'sale_price'=>0.70,'quantity'=>80,'status'=>true],

            // Snacks
            ['categories_id'=>2,'brand_id'=>4,'product_code'=>'SN001','name'=>'Oishi Prawn Crackers','description'=>'Snack','unit'=>'pack','cost_price'=>0.30,'sale_price'=>0.50,'quantity'=>200,'status'=>true],
            ['categories_id'=>2,'brand_id'=>4,'product_code'=>'SN002','name'=>'Potato Chips','description'=>'Crispy chips','unit'=>'pack','cost_price'=>0.50,'sale_price'=>0.80,'quantity'=>150,'status'=>true],
            ['categories_id'=>2,'brand_id'=>4,'product_code'=>'SN003','name'=>'Corn Snacks','description'=>'Corn snack','unit'=>'pack','cost_price'=>0.35,'sale_price'=>0.55,'quantity'=>130,'status'=>true],

            // Groceries
            ['categories_id'=>3,'brand_id'=>3,'product_code'=>'GR001','name'=>'Nestle Milk 1L','description'=>'Milk','unit'=>'box','cost_price'=>0.90,'sale_price'=>1.20,'quantity'=>80,'status'=>true],
            ['categories_id'=>3,'brand_id'=>3,'product_code'=>'GR002','name'=>'Sugar 1kg','description'=>'White sugar','unit'=>'bag','cost_price'=>0.70,'sale_price'=>1.00,'quantity'=>100,'status'=>true],
            ['categories_id'=>3,'brand_id'=>3,'product_code'=>'GR003','name'=>'Salt 500g','description'=>'Salt','unit'=>'pack','cost_price'=>0.20,'sale_price'=>0.40,'quantity'=>200,'status'=>true],

            // Frozen
            ['categories_id'=>4,'brand_id'=>5,'product_code'=>'FR001','name'=>'Ice Cream 500ml','description'=>'Ice cream','unit'=>'tub','cost_price'=>1.50,'sale_price'=>2.00,'quantity'=>50,'status'=>true],
            ['categories_id'=>4,'brand_id'=>5,'product_code'=>'FR002','name'=>'Frozen Chicken','description'=>'Chicken','unit'=>'kg','cost_price'=>3.00,'sale_price'=>3.80,'quantity'=>60,'status'=>true],
            ['categories_id'=>4,'brand_id'=>5,'product_code'=>'FR003','name'=>'Frozen Fries','description'=>'Fries','unit'=>'pack','cost_price'=>1.00,'sale_price'=>1.50,'quantity'=>90,'status'=>true],

            // Household
            ['categories_id'=>22,'brand_id'=>4,'product_code'=>'HS001','name'=>'Tide Detergent','description'=>'Laundry','unit'=>'bag','cost_price'=>3.00,'sale_price'=>3.80,'quantity'=>60,'status'=>true],
            ['categories_id'=>22,'brand_id'=>4,'product_code'=>'HS002','name'=>'Dishwashing Liquid','description'=>'Clean dishes','unit'=>'bottle','cost_price'=>1.00,'sale_price'=>1.50,'quantity'=>70,'status'=>true],

            // Personal Care
            ['categories_id'=>24,'brand_id'=>4,'product_code'=>'PC001','name'=>'Shampoo','description'=>'Hair care','unit'=>'bottle','cost_price'=>2.00,'sale_price'=>2.80,'quantity'=>50,'status'=>true],
            ['categories_id'=>24,'brand_id'=>4,'product_code'=>'PC002','name'=>'Soap','description'=>'Body soap','unit'=>'bar','cost_price'=>0.50,'sale_price'=>0.80,'quantity'=>150,'status'=>true],

            // Extra products
            ['categories_id'=>5,'brand_id'=>3,'product_code'=>'FRU001','name'=>'Apple','description'=>'Fresh apple','unit'=>'kg','cost_price'=>1.20,'sale_price'=>1.80,'quantity'=>100,'status'=>true],
            ['categories_id'=>6,'brand_id'=>3,'product_code'=>'VEG001','name'=>'Carrot','description'=>'Fresh carrot','unit'=>'kg','cost_price'=>0.80,'sale_price'=>1.20,'quantity'=>90,'status'=>true],
            ['categories_id'=>7,'brand_id'=>3,'product_code'=>'DA001','name'=>'Cheese','description'=>'Dairy cheese','unit'=>'pack','cost_price'=>2.00,'sale_price'=>2.80,'quantity'=>40,'status'=>true],
            ['categories_id'=>8,'brand_id'=>3,'product_code'=>'BA001','name'=>'Bread','description'=>'Bakery bread','unit'=>'loaf','cost_price'=>0.70,'sale_price'=>1.00,'quantity'=>60,'status'=>true],
            ['categories_id'=>15,'brand_id'=>3,'product_code'=>'NP001','name'=>'Instant Noodles','description'=>'Quick meal','unit'=>'pack','cost_price'=>0.30,'sale_price'=>0.60,'quantity'=>200,'status'=>true],
            ['categories_id'=>16,'brand_id'=>3,'product_code'=>'SP001','name'=>'Soy Sauce','description'=>'Cooking sauce','unit'=>'bottle','cost_price'=>0.80,'sale_price'=>1.20,'quantity'=>100,'status'=>true],
            ['categories_id'=>17,'brand_id'=>3,'product_code'=>'SW001','name'=>'Chocolate','description'=>'Sweet snack','unit'=>'bar','cost_price'=>0.50,'sale_price'=>0.90,'quantity'=>120,'status'=>true],
        ];
        foreach ($data as $item) {
            ProductsModel::create($item);
        }
    }
}
