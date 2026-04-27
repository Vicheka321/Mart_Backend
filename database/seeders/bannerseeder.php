<?php

namespace Database\Seeders;

use App\Models\banners;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class bannerseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Coca-Cola',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/6975285.jpg'
            ],
            [
                'title' => 'Pepsi',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/7173417.jpg'

            ],
            [
                'title' => 'Nestlé',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/7529420.jpg'
            ],

        ];
        foreach ($data as $item) {
            banners::create($item);
        }
    }
}
