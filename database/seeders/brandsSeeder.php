<?php

namespace Database\Seeders;

use App\Models\BrandModel;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class brandsSeeder extends Seeder
{
    // public function run(): void
    // {
    //     $data = [
    //         [
    //             'name' => 'Coca-Cola',
    //             'country' => 'USA',
    //             'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/1.jpg'
    //         ],
    //         [
    //             'name' => 'Pepsi',
    //             'country' => 'USA',
    //             'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/2.jpg'
    //         ],
    //         [
    //             'name' => 'Nestlé',
    //             'country' => 'Switzerland',
    //             'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/3.jpg'
    //         ],
    //         [
    //             'name' => 'Unilever',
    //             'country' => 'United Kingdom',
    //             'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/4.jpg'
    //         ],
    //         [
    //             'name' => 'Kellogg\'s',
    //             'country' => 'USA',
    //             'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/5.avif'
    //         ]
    //     ];

    //     foreach ($data as $item) {
    //         BrandModel::create($item);
    //     }
    // }
    public function run(): void
    {
        // Create 50 fake categories with random images
        // for ($i = 1; $i <= 50; $i++) {
        //     $createdAt = Carbon::now()->subDays(rand(1, 730));

        //     BrandModel::create([
        //         'name' => fake()->unique()->words(rand(1, 3), true),

        //         // Fake image URL
        //         'image' => 'https://picsum.photos/seed/brand' . $i . '/600/600',

        //         'created_at' => $createdAt,
        //         'updated_at' => $createdAt,
        //     ]);
        // }

        $brands = [
            [
                'name' => 'Coca Cola',
                'image' => 'https://www.coca-cola.com/content/dam/onexp/us/en/brands/coca-cola-original/en_coca-cola-original-taste-20-oz_750x750_v1.jpg',
            ],
            [
                'name' => 'Pepsi',
                'image' => 'https://digitalcontent.api.tesco.com/v2/media/ghs/ecc7a153-e6ab-4701-a6b2-5fbda041c452/593ca84c-7908-4948-8fb8-f00ee3c4c389_1163419905.jpeg?h=960&w=960',
            ],
            [
                'name' => 'Nestle',
                'image' => 'https://www.monpetitbresil.com/cdn/shop/collections/Nestle-logo_1.png?v=1661395847',
            ],
            [
                'name' => 'Oishi',
                'image' => 'https://corinthiandistributors.com/wp-content/uploads/2023/01/GEM230117_23-scaled.jpg',
            ],
            [
                'name' => 'Milo',
                'image' => 'https://bigpharmacy.com.my/cdn/shop/files/10017410EA_20260126112411.jpg?v=1770877809',
            ],
            [
                'name' => 'Nescafe',
                'image' => 'https://turcamart.com/cdn/shop/files/Nescafe_3_in_1_Original_17.5_G.jpg?v=1756378021',
            ],
            [
                'name' => 'Red Bull',
                'image' => 'https://m.media-amazon.com/images/I/51Bp30CR3IL.jpg',
            ],
            [
                'name' => 'Tiger',
                'image' => 'https://upload.wikimedia.org/wikipedia/en/6/60/Tiger_Beer_logo.png',
            ],
            [
                'name' => 'Ajinomoto',
                'image' => 'https://www.thewasabicompany.co.uk/cdn/shop/files/ajinomoto-msg-seasoning-powder-1kg_1200x1200.webp?v=1743506642',
            ],
            [
                'name' => 'Anchor',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIHonOtqP3g0mNHg9DqvWSuqnqnj3RxxMikQ&s',
            ],
        ];

        foreach ($brands as $brand) {

            $createdAt = Carbon::now()->subDays(rand(1, 730));

            BrandModel::create([
                'name' => $brand['name'],
                'image' => $brand['image'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
