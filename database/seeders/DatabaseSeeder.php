<?php

namespace Database\Seeders;

use App\Models\user;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
   
        
        $this->call([
            UserSeeder::class,
            categoriesSeeder::class,
            brandsSeeder::class,
            ProductsSeeder::class,
            Products_image::class,
            Promotions::class,
            bannerseeder::class,
        ]);
    }
}
