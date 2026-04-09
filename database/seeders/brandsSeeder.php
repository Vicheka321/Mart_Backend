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
            ['name'=>'Coca-Cola','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/c/ce/Coca-Cola_logo.svg'],
            ['name'=>'Pepsi','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/6/68/Pepsi_2023.svg'],
            ['name'=>'Nestle','country'=>'Switzerland','image'=>'https://upload.wikimedia.org/wikipedia/en/d/d8/Nestl%C3%A9.svg'],
            ['name'=>'Unilever','country'=>'UK','image'=>'https://upload.wikimedia.org/wikipedia/commons/9/96/Unilever_logo.svg'],
            ['name'=>'P&G','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/8/85/Procter_%26_Gamble_logo.svg'],

            ['name'=>'Danone','country'=>'France','image'=>'https://upload.wikimedia.org/wikipedia/commons/7/74/Danone_logo.svg'],
            ['name'=>'Kellogg’s','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/6/65/Kellogg%27s_logo.svg'],
            ['name'=>'Mars','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/0/08/Mars_logo.svg'],
            ['name'=>'Mondelez','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/3/36/Mondelez_International_logo.svg'],
            ['name'=>'Heinz','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/a/a5/Heinz_logo.svg'],

            ['name'=>'Johnson & Johnson','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/2/20/Johnson_%26_Johnson_logo.svg'],
            ['name'=>'Colgate','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/2/2f/Colgate_logo.svg'],
            ['name'=>'L’Oreal','country'=>'France','image'=>'https://upload.wikimedia.org/wikipedia/commons/5/5f/L%27Or%C3%A9al_logo.svg'],
            ['name'=>'Nivea','country'=>'Germany','image'=>'https://upload.wikimedia.org/wikipedia/commons/6/6b/Nivea_logo.svg'],
            ['name'=>'Adidas','country'=>'Germany','image'=>'https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg'],

            ['name'=>'Nike','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg'],
            ['name'=>'Samsung','country'=>'South Korea','image'=>'https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg'],
            ['name'=>'LG','country'=>'South Korea','image'=>'https://upload.wikimedia.org/wikipedia/commons/2/20/LG_symbol.svg'],
            ['name'=>'Sony','country'=>'Japan','image'=>'https://upload.wikimedia.org/wikipedia/commons/c/ca/Sony_logo.svg'],
            ['name'=>'Panasonic','country'=>'Japan','image'=>'https://upload.wikimedia.org/wikipedia/commons/4/4c/Panasonic_logo.svg'],

            ['name'=>'3M','country'=>'USA','image'=>'https://upload.wikimedia.org/wikipedia/commons/4/4e/3M_wordmark.svg'],
            ['name'=>'Philips','country'=>'Netherlands','image'=>'https://upload.wikimedia.org/wikipedia/commons/a/ab/Philips_logo.svg'],
            ['name'=>'Sharp','country'=>'Japan','image'=>'https://upload.wikimedia.org/wikipedia/commons/0/0e/Sharp_logo.svg'],
            ['name'=>'Canon','country'=>'Japan','image'=>'https://upload.wikimedia.org/wikipedia/commons/7/7b/Canon_logo.svg'],
            ['name'=>'Toshiba','country'=>'Japan','image'=>'https://upload.wikimedia.org/wikipedia/commons/5/59/Toshiba_logo.svg'],
        ];

        foreach ($data as $item) {
            BrandModel::create($item);
        }
    }
}
