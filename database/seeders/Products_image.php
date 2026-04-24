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
            ['product_id'=>1,'image_url'=>'https://www.shutterstock.com/image-photo/jakarta-indonesia-june-9-2024-260nw-2473912149.jpg'],
            ['product_id'=>2,'image_url'=>'https://cdn.pixabay.com/photo/2016/03/02/20/13/grocery-1232944_1280.jpg'],
            ['product_id'=>3,'image_url'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRwq5e4JwvR821FtdOR63C7G6IK4SnRsi4IOQ&s'],
            ['product_id'=>4,'image_url'=>'https://cdn.pixabay.com/photo/2016/03/02/20/13/grocery-1232944_1280.jpg'],
        


        ];
        
        foreach($data as $row){
            ProductsImageModel::create($row);
        }
        
    }
}
