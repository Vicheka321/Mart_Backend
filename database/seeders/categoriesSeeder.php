<?php

namespace Database\Seeders;

use App\Models\categoriesModel;
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
            ['name' => 'Drinks', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Snacks', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Groceries', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Frozen Foods', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],

            ['name' => 'Fruits', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Vegetables', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Dairy', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Bakery', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],

            ['name' => 'Meat', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Seafood', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Instant Food', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Fooda', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodb', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodc', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodd', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foode', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodf', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodg', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodh', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodi', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodj', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodk', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodl', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],
            ['name' => 'Canned Foodm', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3ab8d9d4-2369-43a0-be37-b5b39441cd90.jpg'],

        ];

        foreach ($data as $item) {
            categoriesModel::create($item);
        }
    }
}
