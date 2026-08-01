<?php

namespace Database\Seeders;

use App\Models\Banners;
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
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/banner1.png'
            ],
            [
                'title' => 'Banner',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/banner2.png'
            ],
            [
                'title' => 'Banner',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/banner3.png'
            ],
            [
                'title' => 'Banner',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/banner4.png'
            ],
            [
                'title' => 'Banner',
                'image_url' => 'https://pub-42158637988f4d79ab3305553db0651f.r2.dev/banners/banner5.png'
            ],

        ];
        foreach ($data as $item) {
            banners::create($item);
        }
    }
}
