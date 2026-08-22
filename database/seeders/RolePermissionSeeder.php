<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions grouped by resource
        |--------------------------------------------------------------------------
        | Each key is the "resource" column value, each item in the array is the
        | permission "name". guard_name is always 'web'.
        */
        $permissions = [
            'Admin Panel' => [
                'access_admin_panel',
            ],

            'Dashboard' => [
                'view_dashboard',
            ],

            'Products' => [
                'view_products',
                'create_products',
                'edit_products',
                'delete_products',
                'export_products',
            ],

            'Categories' => [
                'view_categories',
                'create_categories',
                'edit_categories',
                'delete_categories',
            ],

            'Brands' => [
                'view_brands',
                'create_brands',
                'edit_brands',
                'delete_brands',
            ],

            'Orders' => [
                'view_orders',
                'update_orders',
                'cancel_orders',
                'export_orders',
            ],

            'Delivery Fees' => [
                'view_delivery_fees',
                'create_delivery_fees',
                'edit_delivery_fees',
                'delete_delivery_fees',
            ],

            'Branches' => [
                'view_branches',
                'create_branches',
                'edit_branches',
                'delete_branches',
            ],

            'Reviews' => [
                'view_reviews',
            ],

            'Coupons' => [
                'view_coupons',
                'create_coupons',
                'edit_coupons',
                'delete_coupons',
            ],

            'Banners' => [
                'view_banners',
                'create_banners',
                'edit_banners',
                'delete_banners',
            ],

            'Promotions' => [
                'view_promotions',
                'create_promotions',
                'edit_promotions',
                'delete_promotions',
            ],

            'Customers' => [
                'view_customers',
                'create_customers',
                'edit_customers',
                'delete_customers',
            ],

            'Notifications' => [
                'view_notifications',
            ],

            'Reports' => [
                'view_reports',
                'view_sales_report',
                'view_orders_report',
                'view_customers_report',
            ],

            'Analysis' => [
                'view_analysis',
            ],

            'Settings' => [
                'view_settings',
                'edit_settings',
            ],

            'Roles' => [
                'view_roles',
                'create_roles',
                'edit_roles',
                'delete_roles',
                'assign_roles',
            ],

            'Users' => [
                'view_users',
            ],

            'Permissions' => [
                'view_permissions',
                'create_permissions',
                'edit_permissions',
                'delete_permissions',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Seed permissions (resource + name + guard_name explicit, no auto-generation)
        |--------------------------------------------------------------------------
        */
        foreach ($permissions as $resource => $permissionNames) {
            foreach ($permissionNames as $permissionName) {
                Permission::firstOrCreate(
                    [
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ],
                    [
                        'resource' => $resource,
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Create Roles
        |--------------------------------------------------------------------------
        */
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $manager    = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $staff      = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $customer   = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        /*
        |--------------------------------------------------------------------------
        | Permission Sets
        |--------------------------------------------------------------------------
        */

        // Super Admin => everything
        $superAdmin->syncPermissions(Permission::pluck('name')->toArray());

        // Admin => almost everything, including Permission Management
        $admin->syncPermissions([
            'access_admin_panel',

            'view_dashboard',

            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'export_products',

            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            'view_brands',
            'create_brands',
            'edit_brands',
            'delete_brands',

            'view_orders',
            'update_orders',
            'cancel_orders',
            'export_orders',

            'view_delivery_fees',
            'create_delivery_fees',
            'edit_delivery_fees',
            'delete_delivery_fees',

            'view_branches',
            'create_branches',
            'edit_branches',
            'delete_branches',

            'view_reviews',

            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',

            'view_coupons',
            'create_coupons',
            'edit_coupons',
            'delete_coupons',

            'view_banners',
            'create_banners',
            'edit_banners',
            'delete_banners',

            'view_promotions',
            'create_promotions',
            'edit_promotions',
            'delete_promotions',

            'view_notifications',

            'view_reports',
            'view_sales_report',
            'view_orders_report',
            'view_customers_report',

            'view_analysis',

            'view_settings',
            'edit_settings',

            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_roles',
            'view_users',

            // Permission Management
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
        ]);

        // Manager => operations + reports (NO permission management)
        $manager->syncPermissions([
            'access_admin_panel',
            'view_dashboard',

            'view_products',
            'edit_products',
            'view_categories',
            'view_brands',

            'view_orders',
            'update_orders',
            'cancel_orders',
            'view_customers',

            'view_promotions',
            'view_coupons',

            'view_reports',
            'view_sales_report',
            'view_orders_report',
            'view_customers_report',
            'view_analysis',
            'view_notifications',
        ]);

        // Staff => day-to-day operational access, no delete/settings/roles/permissions
        $staff->syncPermissions([
            'access_admin_panel',
            'view_dashboard',

            'view_products',
            'edit_products',

            'view_categories',
            'view_brands',

            'view_orders',
            'update_orders',

            'view_customers',

            'view_coupons',
            'view_banners',
            'view_promotions',

            'view_notifications',
        ]);

        // Customer => no admin permissions
        $customer->syncPermissions([]);
    }
}
