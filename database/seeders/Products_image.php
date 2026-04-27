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
            ['product_id'=>1,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/2.webp'],
            ['product_id'=>2,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/2.avif'],
            ['product_id'=>3,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/24_019351f9-d2ac-40cd-91ef-117d7a0cebf6.webp'],
            ['product_id'=>4,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/3.webp'],
            ['product_id'=>5,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/5.webp'],
            ['product_id'=>6,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/6.jpg'],
            ['product_id'=>7,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/7.jpg'],
            ['product_id'=>8,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/8.jpg'],
            ['product_id'=>9,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/9.png'],
            ['product_id'=>10,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/10.avif'],
            ['product_id'=>10,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/11.webp'],
            ['product_id'=>10,'image_url'=>'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/products/12.webp'],


        
            
        


        ];
        
        foreach($data as $row){
            ProductsImageModel::create($row);
        }
        
    }
}
