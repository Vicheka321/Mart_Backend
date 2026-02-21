<?php

namespace Database\Seeders;

use App\Models\ProductsImageModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Products_image extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['product_id'=>1,'image_url'=>'https://picsum.photos/200?1'],
            ['product_id'=>1,'image_url'=>'https://picsum.photos/200?2'],
            ['product_id'=>2,'image_url'=>'https://picsum.photos/200?3'],
            ['product_id'=>3,'image_url'=>'https://picsum.photos/200?4'],
        ];
        
        foreach($data as $row){
            ProductsImageModel::create($row);
        }
        
    }
}
