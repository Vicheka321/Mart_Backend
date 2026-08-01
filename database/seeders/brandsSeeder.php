<?php

namespace Database\Seeders;

use App\Models\BrandModel;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class brandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Coca Cola',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b1.png',
            ],
            [
                'name' => 'Pepsi',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b2.png',
            ],
            [
                'name' => 'Eau Kulen',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b3.png',
            ],
            [
                'name' => 'Julies Cheese Sandwich',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b4.png',
            ],
            [
                'name' => 'Buldak Noodle',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b5.png',
            ],
            [
                'name' => 'Koreno Noddle',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b6.png',
            ],
            [
                'name' => 'Mistine ACNE CLEAR Facial Foam',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b7.png',
            ],
            [
                'name' => 'Ajinomoto',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b8.png',
            ],
            [
                'name' => 'Oishi',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b9.png',
            ],
            [
                'name' => 'Nestlé',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b10.png',
            ],
            [
                'name' => 'No Brand',
                'image' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/brands/b11.png',
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
