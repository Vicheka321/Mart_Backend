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
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/Screenshot%202026-05-28%20172829.png'
            ],
            [
                'title' => 'Pepsi',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/Screenshot%202026-05-28%20172942.png'

            ],
            [
                'title' => 'Nestlé',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/Screenshot%202026-05-28%20173028.png'
            ],
            [
                'title' => 'Nestlée',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/Screenshot%202026-05-28%20173119.png'
            ],
            [
                'title' => 'Nestléss',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/Screenshot%202026-05-28%20173211.png'
            ],

        ];
        foreach ($data as $item) {
            banners::create($item);
        }
    }
}
