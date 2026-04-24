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
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/c/ce/Coca-Cola_logo.svg'
            ],
        ];

        foreach ($data as $item) {
            BrandModel::create($item);
        }
    }
}
