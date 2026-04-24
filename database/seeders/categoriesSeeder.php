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
            ['name' => 'Drinks', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/03f6c63a-1058-402e-93ce-727e2a60fa56.jpg'],
        ];


        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
