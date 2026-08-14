<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeliveryFeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('delivery_fees')->truncate();

        $now = Carbon::now();

        DB::table('delivery_fees')->insert([

            [
                'min_km' => 0,
                'max_km' => 10,
                'fee' => 0,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'min_km' => 11,
                'max_km' => 50,
                'fee' => 1,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'min_km' => 51,
                'max_km' => 100,
                'fee' => 2,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'min_km' => 101,
                'max_km' => 99999,
                'fee' => 3,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

        ]);
    }
}