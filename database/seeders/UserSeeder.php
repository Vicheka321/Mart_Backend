<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '012345678',
            'password' => Hash::make('11112222'),
            'role' => 'admin'
        ]);

        // Staff
        User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'phone' => '098765432',
            'password' => Hash::make('11112222'),
            'role' => 'staff'
        ]);
    }
}
