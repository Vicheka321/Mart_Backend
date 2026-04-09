<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = BrandModel::orderBy('id')->paginate(10);
        return view('Admin.brands', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);
        $imageUrl = null;
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = 'brands/' . $fileName;
            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );
            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }
        BrandModel::create([
            'name' => $request->name,
            'image' => $imageUrl,

        ]);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);
        $brand = BrandModel::find($id);
        $imageUrl = $brand->image;
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = 'brands/' . $fileName;
            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );
            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }
        $brand->update([
            'name' => $request->name,
            'image' => $imageUrl,

        ]);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $brand = BrandModel::find($id);
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
