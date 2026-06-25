<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('id', 'desc')->paginate(20);
        $roles = Role::orderBy('name')->get();

        return view('admin.roles.user-roles', compact('users', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
        ]);

        $user->syncRoles($request->roles ?? []);

        return back()->with('success', 'User roles updated successfully');
    }
}