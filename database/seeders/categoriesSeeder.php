<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Drinks',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c1.png',
            ],
            [
                'name' => 'HouseHold',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c2.png',
            ],
            [
                'name' => 'Coffee & Tea',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c3.png',
            ],
            [
                'name' => 'Canned Food',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c4.png',
            ],
            [
                'name' => 'Instant Food',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c5.png',
            ],
            [
                'name' => 'Baby Care',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c6.png',
            ],
            [
                'name' => 'beauty',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c7.png',
            ],
            [
                'name' => 'Food',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c8.png',
            ],
            [
                'name' => 'Seasioning',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c9.png',
            ],
            [
                'name' => 'Personal care',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/c10.png',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'image' => $category['image'],
            ]);
        }
    }
}
