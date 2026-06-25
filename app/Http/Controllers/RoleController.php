<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('id', 'desc')
            ->get();

        $permissions = Permission::orderBy('name')->get();

        $permissionGroups = [
            'Admin Panel' => [
                'access_admin_panel',
            ],

            'Dashboard' => [
                'view_dashboard',
            ],

            'Banners' => [
                'view_banners',
                'create_banners',
                'edit_banners',
                'delete_banners',
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

            'Customers' => [
                'view_customers',
                'delete_customers',
            ],

            'Promotions' => [
                'view_promotions',
                'create_promotions',
                'edit_promotions',
                'delete_promotions',
            ],

            'Coupons' => [
                'view_coupons',
                'create_coupons',
                'edit_coupons',
                'delete_coupons',
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

            'Notifications' => [
                'view_notifications',
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
        ];

        return view('admin.roles.index', compact(
            'roles',
            'permissions',
            'permissionGroups'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        // 🔒 Protect Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // 🔒 Protect Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }

    public function users()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $roles = Role::orderBy('name')->get();

        return view('Admin.roles.assign-users', compact('users', 'roles'));
    }

    public function assignUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // 🔒 Optional protection:
        // if this user is already Super Admin, don't allow changing that role here
        if ($user->hasRole('Super Admin')) {
            return back()->with('error', 'Super Admin user role cannot be changed.');
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'User role updated successfully.');
    }
}
