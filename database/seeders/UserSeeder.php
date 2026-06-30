<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================
        // Super Admin
        // ==========================
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name' => 'Super Admin',
                'phone' => '012345678',
                'password' => Hash::make('11112222'),
            ]
        );
        $superAdmin->syncRoles(['Super Admin']);

        // ==========================
        // Admin
        // ==========================
        $admin = User::updateOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'full_name' => 'Admin User',
                'phone' => '012345679',
                'password' => Hash::make('11112222'),
            ]
        );
        $admin->syncRoles(['Admin']);

        // ==========================
        // Manager
        // ==========================
        $manager = User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'full_name' => 'Manager User',
                'phone' => '012345680',
                'password' => Hash::make('11112222'),
            ]
        );
        $manager->syncRoles(['Manager']);

        // ==========================
        // Staff
        // ==========================
        $staff = User::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'full_name' => 'Staff User',
                'phone' => '012345681',
                'password' => Hash::make('11112222'),
            ]
        );
        $staff->syncRoles(['Staff']);

        // ==========================
        // Demo Customer
        // ==========================
        $customer = User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'full_name' => 'Customer User',
                'phone' => '012345682',
                'password' => Hash::make('11112222'),
            ]
        );
        $customer->syncRoles(['Customer']);

        // ==========================
        // Generate 300 Cambodian Customers
        // ==========================

        $firstNames = [
            'Sok',
            'Dara',
            'Vanna',
            'Sophea',
            'Piseth',
            'Rith',
            'Chan',
            'Bora',
            'Makara',
            'Ratanak',
            'Kosal',
            'Narin',
            'Sovan',
            'Virak',
            'Sokha',
            'Srey',
            'Linda',
            'Sopheap',
            'Sreypov',
            'Ratha',
            'Mony',
            'Kanha',
            'Chenda',
            'Nika',
            'Dalin',
            'Bopha',
            'Pich',
            'Nary',
            'Kunthea',
            'Vicheka',
            'Ravy',
            'Phalla',
            'Thida',
            'Rina',
            'Panha'
        ];

        $lastNames = [
            'Chea',
            'Chan',
            'Kim',
            'Lim',
            'Ouk',
            'Seng',
            'Ly',
            'Phan',
            'Yim',
            'Sin',
            'Touch',
            'Noun',
            'Kong',
            'Long',
            'Heng',
            'Chhun',
            'Keo',
            'Mao',
            'Nuon',
            'Meas',
            'Pen',
            'Ros',
            'Sam',
            'Suon',
            'Tan',
            'Thorn',
            'Vong',
            'Yoeun',
            'Sok',
            'Kea',
            'Chhim',
            'Hun',
            'Prom',
            'Prak'
        ];

        $prefixes = ['010', '011', '012', '015', '016', '017', '018', '031', '060', '061', '066', '067', '068', '069', '070', '071', '076', '077', '078', '081', '085', '086', '087', '088', '089', '090', '092', '093', '095', '096', '097', '098', '099'];

        for ($i = 1; $i <= 300; $i++) {

            $fullName = $firstNames[array_rand($firstNames)]
                . ' ' .
                $lastNames[array_rand($lastNames)];

            $phone = $prefixes[array_rand($prefixes)]
                . str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

            $user = User::create([
                'full_name' => $fullName,
                'email' => 'customer' . $i . '@gmail.com',
                'phone' => $phone,
                'password' => Hash::make('11112222'),
            ]);

            $user->syncRoles(['Customer']);
        }
    }
}
