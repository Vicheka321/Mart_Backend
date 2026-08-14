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

        $permissions = [
            // admin panel
            'access_admin_panel',

            // dashboard
            'view_dashboard',

            // products
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'export_products',

            // categories
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // brands
            'view_brands',
            'create_brands',
            'edit_brands',
            'delete_brands',

            // orders
            'view_orders',
            'update_orders',
            'cancel_orders',
            'export_orders',

            // delivery fees
            'view_delivery_fees',
            'create_delivery_fees',
            'edit_delivery_fees',
            'delete_delivery_fees',

            // branches
            'view_branches',
            'create_branches',
            'edit_branches',
            'delete_branches',

            'view_reviews',

            // coupons
            'view_coupons',
            'create_coupons',
            'edit_coupons',
            'delete_coupons',

            // banners
            'view_banners',
            'create_banners',
            'edit_banners',
            'delete_banners',

            // promotions
            'view_promotions',
            'create_promotions',
            'edit_promotions',
            'delete_promotions',

            // customers
            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',

            // notifications
            'view_notifications',

            // reports
            'view_reports',
            'view_sales_report',
            'view_orders_report',
            'view_customers_report',

            // analysis
            'view_analysis',

            // settings
            'view_settings',
            'edit_settings',

            // audit logs
            'view_audit_logs',
            'delete_audit_logs',

            // roles / users
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_roles',
            'view_users',
            'edit_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
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

        // Admin => almost everything except super-sensitive things if you want
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

            'view_roles',
            'create_roles',
            'edit_roles',
            'assign_roles',
            'view_users',
            'edit_users',

            'view_settings',
            'edit_settings',

        ]);


        // Manager => operations + reports
        $manager->syncPermissions([
            'access_admin_panel',
            'view_dashboard',

            'view_products', 'edit_products',
            'view_categories',
            'view_brands',

            'view_orders', 'update_orders', 'cancel_orders',
            'view_customers',

            'view_promotions',
            'view_coupons',

            'view_reports', 'view_sales_report', 'view_orders_report', 'view_customers_report',
            'view_analysis',
            'view_notifications',
        ]);


        // Staff => day-to-day operational access, no delete/settings/roles
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


        $customer->syncPermissions([]);
    }
}