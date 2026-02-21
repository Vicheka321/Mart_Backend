<?php

namespace Database\Seeders;

use App\Models\BrandModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class brandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Coca-Cola',
                'country' => 'USA',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/c/ce/Coca-Cola_logo.svg',
            ],
            [
                'name' => 'Pepsi',
                'country' => 'USA',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/6/68/Pepsi_2023.svg',
            ],
            [
                'name' => 'Nestle',
                'country' => 'Switzerland',
                'image' => 'https://upload.wikimedia.org/wikipedia/en/d/d8/Nestl%C3%A9.svg',
            ],
            [
                'name' => 'P&G',
                'country' => 'USA',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/8/85/Procter_%26_Gamble_logo.svg',
            ],
        ];
        
        foreach ($data as $item) {
            BrandModel::create($item);
        }
    }
}
