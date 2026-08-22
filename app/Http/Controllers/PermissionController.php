<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('resource')
            ->orderBy('name')
            ->get();

        // Group permissions by resource
        $resources = $permissions
            ->groupBy('resource')
            ->map(function ($permissions) {
                return $permissions->map(function ($permission) {
                    return [
                        'id'   => $permission->id,
                        'name' => $permission->name,
                    ];
                })->values();
            });

        return view('Admin.roles.permission', compact(
            'permissions',
            'resources'
        ));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource' => [
                'required',
                'string',
                'max:100',
            ],

            'actions' => [
                'required',
                'array',
                'min:1',
            ],

            'actions.*' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'resource.required' => 'Resource is required.',
            'actions.required' => 'Please add at least one permission action.',
            'actions.min' => 'Please add at least one permission action.',
        ]);

        $resource = trim($validated['resource']);

        $created = 0;
        $duplicates = [];

        foreach ($validated['actions'] as $action) {

            $action = trim($action);

            if ($action === '') {
                continue;
            }

            // Check duplicate
            $exists = Permission::where('name', $action)
                ->where('guard_name', 'web')
                ->exists();

            if ($exists) {
                $duplicates[] = $action;
                continue;
            }

            Permission::create([
                'name'       => $action,
                'resource'   => $resource,
                'guard_name' => 'web',
            ]);

            $created++;
        }

        if ($created === 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'actions' => 'All selected permissions already exist.',
                ]);
        }

        $message = "{$created} permission(s) created successfully.";

        if (count($duplicates) > 0) {
            $message .= ' Already existing: ' . implode(', ', $duplicates);
        }

        return back()->with('success', $message);
    }


    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'resource' => [
                'required',
                'string',
                'max:100',
            ],

            'action' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'resource.required' => 'Resource is required.',
            'action.required' => 'Permission name is required.',
        ]);

        // Check duplicate permission name
        $exists = Permission::where('name', $validated['action'])
            ->where('guard_name', 'web')
            ->where('id', '!=', $permission->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'action' => 'This permission already exists.',
                ]);
        }

        $permission->update([
            'name'     => $validated['action'],
            'resource' => $validated['resource'],
        ]);

        return back()->with(
            'success',
            'Permission updated successfully.'
        );
    }


    public function destroy(Permission $permission)
    {
        // Check whether this permission is assigned to any role
        if ($permission->roles()->exists()) {
            return back()->with(
                'error',
                'This permission is currently assigned to one or more roles and cannot be deleted.'
            );
        }

        // Delete permission
        $permission->delete();

        return back()->with(
            'success',
            'Permission deleted successfully.'
        );
    }

    public function updateResource(Request $request, string $resource)
    {
        $validated = $request->validate([
            'resource' => [
                'required',
                'string',
                'max:100',
            ],

            'permissions' => [
                'required',
                'array',
                'min:1',
            ],

            'permissions.*.id' => [
                'nullable',
                'integer',
                'exists:permissions,id',
            ],

            'permissions.*.name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($validated, $resource) {

            /*
        |--------------------------------------------------------------------------
        | Existing permissions of this resource
        |--------------------------------------------------------------------------
        */

            $existingPermissions = Permission::where('resource', $resource)
                ->where('guard_name', 'web')
                ->get();

            $submittedIds = collect($validated['permissions'])
                ->pluck('id')
                ->filter()
                ->map(fn($id) => (int) $id)
                ->values()
                ->toArray();


            /*
        |--------------------------------------------------------------------------
        | Update / Create permissions
        |--------------------------------------------------------------------------
        */

            foreach ($validated['permissions'] as $item) {

                // Existing permission
                if (!empty($item['id'])) {

                    $permission = Permission::where('id', $item['id'])
                        ->where('guard_name', 'web')
                        ->firstOrFail();

                    // Check duplicate name
                    $duplicate = Permission::where('name', $item['name'])
                        ->where('guard_name', 'web')
                        ->where('id', '!=', $permission->id)
                        ->exists();

                    if ($duplicate) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'permissions' => "Permission '{$item['name']}' already exists.",
                        ]);
                    }

                    $permission->update([
                        'name' => $item['name'],
                        'resource' => $validated['resource'],
                    ]);
                } else {

                    // New permission
                    $duplicate = Permission::where('name', $item['name'])
                        ->where('guard_name', 'web')
                        ->exists();

                    if ($duplicate) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'permissions' => "Permission '{$item['name']}' already exists.",
                        ]);
                    }

                    Permission::create([
                        'name' => $item['name'],
                        'resource' => $validated['resource'],
                        'guard_name' => 'web',
                    ]);
                }
            }


            /*
        |--------------------------------------------------------------------------
        | Delete removed permissions
        |--------------------------------------------------------------------------
        */

            $existingPermissions
                ->whereNotIn('id', $submittedIds)
                ->each(function ($permission) {
                    $permission->delete();
                });
        });

        return back()->with(
            'success',
            'Resource permissions updated successfully.'
        );
    }

    public function destroyResource(string $resource)
    {
        $permissions = Permission::where('guard_name', 'web')
            ->where('resource', $resource)
            ->get();

        if ($permissions->isEmpty()) {
            return back()->with(
                'error',
                'Resource not found.'
            );
        }

        // Check whether any permission is assigned to a role
        $assignedPermission = $permissions->first(
            fn($permission) => $permission->roles()->exists()
        );

        if ($assignedPermission) {
            return back()->with(
                'error',
                "Resource '{$resource}' cannot be deleted because one or more permissions are assigned to a role."
            );
        }

        // Delete all permissions under this resource
        Permission::where('guard_name', 'web')
            ->where('resource', $resource)
            ->delete();

        return back()->with(
            'success',
            "Resource '{$resource}' and all its permissions have been deleted successfully."
        );
    }
}
