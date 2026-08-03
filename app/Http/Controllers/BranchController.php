<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->get(); // plain collection, not paginated

        return view('Admin.branches', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'required|string',
            'lat'     => 'required|numeric',
            'lng'     => 'required|numeric',
            'is_main' => 'nullable|boolean',
            'status'  => 'nullable|in:active,inactive',
        ]);

        if ($request->boolean('is_main')) {
            Branch::query()->update(['is_main' => false]);
        }

        $branch = Branch::create([
            ...$validated,
            'is_main' => $request->boolean('is_main'),
            'status'  => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'success' => true,
            'branch'  => $branch,
            'message' => 'Branch created successfully.',
        ]);
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'required|string',
            'lat'     => 'required|numeric',
            'lng'     => 'required|numeric',
            'is_main' => 'nullable|boolean',
            'status'  => 'nullable|in:active,inactive',
        ]);

        if ($request->boolean('is_main')) {
            Branch::where('id', '!=', $branch->id)->update(['is_main' => false]);
        }

        $branch->update([
            ...$validated,
            'is_main' => $request->boolean('is_main'),
            'status'  => $validated['status'] ?? $branch->status,
        ]);

        return response()->json([
            'success' => true,
            'branch'  => $branch->fresh(),
            'message' => 'Branch updated successfully.',
        ]);
    }

    public function destroy(Branch $branch)
    {
        if ($branch->is_main) {
            return response()->json([
                'success' => false,
                'message' => 'Main branch cannot be deleted.',
            ], 422);
        }

        $branch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Branch deleted successfully.',
        ]);
    }
}
