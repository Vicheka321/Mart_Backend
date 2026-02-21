<?php

namespace Database\Seeders;

use App\Models\categoriesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Drinks',
                'image' => 'https://lirp.cdn-website.com/acc4873e/dms3rep/multi/opt/soft+drinks-640w.jpg'
            ],
            [
                'name' => 'Snacks',
                'image' => 'https://m.media-amazon.com/images/I/9111kAzE8GL._SL1000_.jpg'
            ],
            [
                'name' => 'Groceries',
                'image' => 'https://cdn.apartmenttherapy.info/image/upload/f_auto,q_auto:eco,c_fit,w_730,h_521/k%2FPhoto%2FSeries%2F2019-10--power-hour-instant-pot%2FPower-Hour-Instant-Pot_001-rotated'
            ],
            [
                'name' => 'Frozen Foods',
                'image' => 'https://shopsuki.ph/cdn/shop/collections/yummy-french-fries-as-background_127101-63_1024x.jpg?v=1656300018'
            ],
        ];
        
    
        foreach ($data as $item) {
            categoriesModel::create($item);
        }
    }
}
