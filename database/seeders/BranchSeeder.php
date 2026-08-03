<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::insert([

            [
                'name'      => 'Darita Mart Head Office',
                'phone'     => '+85512999999',
                'email'     => 'headoffice@daritamart.com',
                'address'   => 'Norton University Old Campus, HWQJ+624, St. Keo Chenda, Sangkat Chroy Chang Va, Khan Russey Keo, Phnom Penh, Cambodia',
                'lat'       => 11.588111,
                'lng'       => 104.930121,
                'is_main'   => true,
                'status'    => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],



        ]);
    }
}