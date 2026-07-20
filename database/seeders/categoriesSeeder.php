<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $data = [
    //         ['name' => 'Fresh Produce', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/1.webp'],
    //         ['name' => 'Dairy Products', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/2.jpg'],
    //         ['name' => 'Bakery Cakes', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/3.jpg'],
    //         ['name' => 'Snacks', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/4.jpeg'],
    //         ['name' => 'Beverages', 'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/categories/5.avif'],
    //     ];


    //     foreach ($data as $item) {
    //         Category::create($item);
    //     }
    // }

    public function run(): void
    {
        // Create 50 fake categories with fake names and random images
        // for ($i = 1; $i <= 50; $i++) {
        //     Category::create([
        //         // Fake category name (1 to 3 random words)
        //         'name' => ucwords(fake()->unique()->words(rand(1, 3), true)),

        //         // Fake image URL
        //         'image' => 'https://picsum.photos/seed/category' . $i . '/600/600',
        //     ]);
        // }
        $categories = [
            [
                'name' => 'Beverages',
                'image' => 'https://thumbs.dreamstime.com/b/fresh-red-apple-fruit-isolated-white-background-130051566.jpg',
            ],
            [
                'name' => 'Snacks',
                'image' => 'https://img.magnific.com/premium-vector/circle-label-hygienic-supplies-cosmetics-department-grocery-store-personal-care-goods_172149-457.jpg?semt=ais_hybrid&w=740&q=80',
            ],
            [
                'name' => 'Dairy Products',
                'image' => 'https://previews.123rf.com/images/budolga/budolga1901/budolga190100004/126608412-dairy-products-vector-illustration-isolated-on-white-background-milk-cheese-butter.jpg',
            ],
            [
                'name' => 'Fresh Fruits',
                'image' => 'https://thumbs.dreamstime.com/b/fresh-red-apple-fruit-isolated-white-background-130051566.jpg',
            ],
            [
                'name' => 'Vegetables',
                'image' => 'https://t4.ftcdn.net/jpg/05/37/04/61/360_F_537046123_s8JVn2NrClPQDOryhSm8jonYZPfIzPRX.jpg',
            ],
            [
                'name' => 'Instant Noodles',
                'image' => 'https://img.magnific.com/free-psd/delicious-instant-noodles-with-chopsticks-white-bowl_84443-64651.jpg?semt=ais_hybrid&w=740&q=80',
            ],
            [
                'name' => 'Frozen Foods',
                'image' => 'https://www.coca-cola.com/content/dam/onexp/us/en/brands/coca-cola-original/en_coca-cola-original-taste-20-oz_750x750_v1.jpg',
            ],
            [
                'name' => 'Bakery',
                'image' => 'https://img.magnific.com/free-photo/concept-tasty-bakery-bagels-isolated-white-background_185193-109388.jpg?semt=ais_hybrid&w=740&q=80',
            ],
            [
                'name' => 'Personal Care',
                'image' => 'https://img.magnific.com/premium-vector/circle-label-hygienic-supplies-cosmetics-department-grocery-store-personal-care-goods_172149-457.jpg?semt=ais_hybrid&w=740&q=80',
            ],
            [
                'name' => 'Household Essentials',
                'image' => 'https://png.pngtree.com/png-clipart/20250111/original/pngtree-household-cleaning-essentials-set-png-image_19048522.png',
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
