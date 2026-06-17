<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'full_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '012345678',
            'password' => Hash::make('11112222'),
            'role' => 'admin'
        ]);

        // Staff
        // User::create([
        //     'full_name' => 'Staff',
        //     'email' => 'staff@gmail.com',
        //     'phone' => '098765432',
        //     'password' => Hash::make('11112222'),
        //     'role' => 'staff'
        // ]);

        for ($i = 1; $i <= 100; $i++) {
            User::create([
                'full_name' => fake()->firstName() . ' ' . fake()->lastName(),
                'email'      => fake()->unique()->safeEmail(),
                'phone'      => '09' . rand(10000000, 99999999),

                // Fake avatar image URL
                'avatar'     => 'https://i.pravatar.cc/150?img=' . rand(1, 70),

                'role'       => 'customer',

                // Optional fields
                'password'   => Hash::make('12345678'),
                // Random dates within the last 2 years
                'created_at' => Carbon::now()->subDays(rand(1, 730)),
                'updated_at' => Carbon::now()->subDays(rand(1, 730)),
            ]);
        }
    }
}
