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
                'title' => 'Banner',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/photo_2026-06-14_01-08-24%20(2).jpg'
            ],
            [
                'title' => 'Banner',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/photo_2026-06-14_01-08-24.jpg'

            ],
            [
                'title' => 'Banner',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/photo_2026-06-14_01-08-25.jpg'
            ],

        ];
        foreach ($data as $item) {
            banners::create($item);
        }
    }
}
