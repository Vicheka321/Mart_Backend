<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name' => 'Super Admin',
                'phone' => '012345678',
                'password' => Hash::make('11112222'),
            ]
        );
        $superAdmin->syncRoles(['Super Admin']);

        $admin = User::updateOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'full_name' => 'Admin User',
                'phone' => '012345679',
                'password' => Hash::make('11112222'),
            ]
        );
        $admin->syncRoles(['Admin']);

        $manager = User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'full_name' => 'Manager User',
                'phone' => '012345680',
                'password' => Hash::make('11112222'),
            ]
        );
        $manager->syncRoles(['Manager']);

        $staff = User::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'full_name' => 'Staff User',
                'phone' => '012345681',
                'password' => Hash::make('11112222'),
            ]
        );
        $staff->syncRoles(['Staff']);

        $customer = User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'full_name' => 'Customer User',
                'phone' => '012345682',
                'password' => Hash::make('11112222'),
            ]
        );
        $customer->syncRoles(['Customer']);
    }
}