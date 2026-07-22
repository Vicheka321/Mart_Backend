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
                'image' => 'https://www.pngitem.com/pimgs/m/3-37003_cool-drinks-images-png-transparent-png.png',
            ],
            [
                'name' => 'HouseHold',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9qkAXcgnkEg5YAxLfm8lGPer_bv6xPmd_hlxJXm8tVWhqKpyFRcuJUn4&s=10',
            ],
            [
                'name' => 'Coffee & Tea',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyilOuWKompb2Bb1M5SZOdH3RUErK-xqn8pK1X5yLgEIP8GYQRKXvwwZI&s=10',
            ],
            [
                'name' => 'Canned Food',
                'image' => 'https://farelabs.com/wp-content/uploads/canned-food-768x432-1.webp',
            ],
            [
                'name' => 'Instant Food',
                'image' => 'https://i5.walmartimages.com/seo/Samyang-Spicy-Chicken-Noodle-Carbonara-Flavor-Ramen-4-58-oz-5-Pack_062ca712-fd56-43fc-af93-84b9f38fc873.0f0d66a110f8f69aed8255038bb82628.jpeg',
            ],
            [
                'name' => 'Baby Care',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYwzqOfaBPAj95r4rbtVmPRckCTCkRY7mWJfcPeX5YH-8iOcX_CMBRgGyg&s=10',
            ],
            [
                'name' => 'beauty',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTr1-y8NFnz3MG2_Grlh22_3QrwhPv-USLyxJX1nb0FLWsqEnT2tfCPvBv4&s=10',
            ],
            [
                'name' => 'Food',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSixnJJA-He5vQ9ihbGI8LIay8h5mfEj-7xoJqrtSZ8kIE3Xlq6KiF-jEX4&s=10',
            ],
            [
                'name' => 'Seasioning',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTemtVFUn6-geEUIACVHkTF2K0b7CrC8uzH1lghKGu937tmA421dNjKSCs&s=10',
            ],
            [
                'name' => 'Personal care',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgtwPSsUa6UnBH78E2jhBdV8I3GdCOFUah44CedBu552kJstd-I0-8nl-M&s=10',
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
