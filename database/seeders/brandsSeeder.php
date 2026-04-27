<?php

namespace Database\Seeders;

use App\Models\BrandModel;
use Illuminate\Database\Seeder;

class brandsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Coca-Cola',
                'country' => 'USA',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/1.jpg'
            ],
            [
                'name' => 'Pepsi',
                'country' => 'USA',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/2.jpg'
            ],
            [
                'name' => 'Nestlé',
                'country' => 'Switzerland',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/3.jpg'
            ],
            [
                'name' => 'Unilever',
                'country' => 'United Kingdom',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/4.jpg'
            ],
            [
                'name' => 'Kellogg\'s',
                'country' => 'USA',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/5.avif'
            ]
        ];

        foreach ($data as $item) {
            BrandModel::create($item);
        }
    }
}