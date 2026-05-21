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
        for ($i = 1; $i <= 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(1, 730));

            BrandModel::create([
                'name' => fake()->unique()->words(rand(1, 3), true),

                // Fake image URL
                'image' => 'https://picsum.photos/seed/brand' . $i . '/600/600',

                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
