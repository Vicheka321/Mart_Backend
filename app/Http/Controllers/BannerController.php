<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\banners;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        // $banners = banners::where('status', true)
        //     ->where(function ($query) {
        //         $query->whereNull('start_date')
        //             ->orWhere('start_date', '<=', now());
        //     })
        //     ->where(function ($query) {
        //         $query->whereNull('end_date')
        //             ->orWhere('end_date', '>=', now());
        //     })
        //     ->orderBy('sort_order', 'asc')
        //     ->get();

        $banners = banners::orderBy('sort_order')->get();
        return view('Admin.banners', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = 'banners/' . $fileName;
            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );
            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }
        banners::create([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, banners $banner)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $imageUrl = $banner->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = 'banners/' . $fileName;

            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );

            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }

        $banner->update([
            'title' => $request->title,
            'image' => $imageUrl,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner updated successfully.');
    }
    public function destroy(banners $banner)
    {
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner deleted successfully.');
    }
}
