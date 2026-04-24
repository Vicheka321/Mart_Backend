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
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR013','name'=>'Coca Cola 330ml','description'=>'Soft drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>100,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR014','name'=>'Pepsi 330ml','description'=>'Cola drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>120,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR015','name'=>'Sprite 330ml','description'=>'Lemon soda','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>90,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR016','name'=>'Fanta Orange','description'=>'Orange soda','unit'=>'bottle','cost_price'=>0.45,'sale_price'=>0.70,'quantity'=>80,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR005','name'=>'Coca Cola','description'=>'Soft drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>110,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR006','name'=>'Pepsi','description'=>'Cola drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>130,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR007','name'=>'Sprite','description'=>'Lemon soda','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>40,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR008','name'=>'Fanta Orange 380ml','description'=>'Orange soda','unit'=>'bottle','cost_price'=>0.45,'sale_price'=>0.70,'quantity'=>80,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR009','name'=>'Coca Cola 380ml','description'=>'Soft drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>160,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR010','name'=>'Pepsi 380ml','description'=>'Cola drink','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>190,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR011','name'=>'Sprite 380ml','description'=>'Lemon soda','unit'=>'bottle','cost_price'=>0.40,'sale_price'=>0.60,'quantity'=>10,'status'=>true],
            ['categories_id'=>1,'brand_id'=>1,'product_code'=>'DR012','name'=>'Fanta Orange 340ml','description'=>'Orange soda','unit'=>'bottle','cost_price'=>0.45,'sale_price'=>0.70,'quantity'=>40,'status'=>true],
       ];
        foreach ($data as $item) {
            ProductsModel::create($item);
        }
    }
}
