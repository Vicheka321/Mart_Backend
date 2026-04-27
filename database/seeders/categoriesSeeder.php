<?php

namespace Database\Seeders;

use App\Models\categoriesModel;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Fresh Produce', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/1.webp'],
            ['name' => 'Dairy Products', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/2.jpg'],
            ['name' => 'Bakery Cakes', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3.jpg'],
            ['name' => 'Snacks', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/4.jpeg'],
            ['name' => 'Beverages', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/5.avif'],
        ];


        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
