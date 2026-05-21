<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\banners;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $now = now()->startOfDay();

        $query = Banners::orderBy('sort_order');

        switch ($statusFilter) {
            case 'active':
                $query->where('status', 1)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('start_date')
                            ->orWhereDate('start_date', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', $now);
                    });
                break;

            case 'scheduled':
                $query->where('status', 1)
                    ->whereNotNull('start_date')
                    ->whereDate('start_date', '>', $now);
                break;

            case 'expired':
                $query->where('status', 1)
                    ->whereNotNull('end_date')
                    ->whereDate('end_date', '<', $now);
                break;

            case 'inactive':
                $query->where('status', 0);
                break;

                // 'all' — no filter, fall through
        }

        $banners = $query->get();

        $banners->each(function ($banner) use ($now) {
            $start = $banner->start_date
                ? \Carbon\Carbon::parse($banner->start_date)->startOfDay()
                : null;

            $end = $banner->end_date
                ? \Carbon\Carbon::parse($banner->end_date)->endOfDay()
                : null;

            // Manually disabled — check first, nothing else matters
            if (! $banner->status) {
                $banner->display_status = 'inactive';
                $banner->is_lifetime    = false;  // disabled banners are never "lifetime"
                return;
            }

            // Scheduled for the future
            if ($start && $now->lt($start)) {
                $banner->display_status = 'scheduled';
                $banner->is_lifetime    = false;
                return;
            }

            // Expired
            if ($end && $now->gt($end)) {
                $banner->display_status = 'expired';
                $banner->is_lifetime    = false;
                return;
            }

            // Active — lifetime only when there is no schedule at all
            $banner->display_status = 'active';
            $banner->is_lifetime    = is_null($start) && is_null($end);
        });

        return view('Admin.banners', compact('banners', 'statusFilter'));
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //         'sort_order' => 'nullable|integer',
    //         'status' => 'required|boolean',
    //         'start_date' => 'nullable|date',
    //         'end_date' => 'nullable|date|after_or_equal:start_date',
    //     ]);

    //     $imageUrl = null;
    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'banners/' . $fileName;
    //         Storage::disk('r2')->put(
    //             $path,
    //             file_get_contents($file),
    //             'public'
    //         );
    //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    //     }
    //     banners::create([
    //         'title' => $request->title,
    //         'image_url' => $imageUrl,
    //         'sort_order' => $request->sort_order,
    //         'status' => $request->status,
    //         'start_date' => $request->start_date,
    //         'end_date' => $request->end_date,
    //     ]);

    //     return redirect()->route('banners.index')->with('success', 'Banner created successfully.');
    // }


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

            Storage::disk('r2')->put($path, file_get_contents($file), 'public');

            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }

        Banners::create([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner created successfully.');
    }
    // public function update(Request $request, banners $banner)
    // {
    //     $request->validate([
    //         'title' => 'required|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //         'sort_order' => 'nullable|integer',
    //         'status' => 'required|boolean',
    //         'start_date' => 'nullable|date',
    //         'end_date' => 'nullable|date|after_or_equal:start_date'
    //     ]);

    //     $imageUrl = $banner->image;

    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'banners/' . $fileName;

    //         Storage::disk('r2')->put(
    //             $path,
    //             file_get_contents($file),
    //             'public'
    //         );

    //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    //     }

    //     $banner->update([
    //         'title' => $request->title,
    //         'image' => $imageUrl,
    //         'sort_order' => $request->sort_order,
    //         'status' => $request->status,
    //         'start_date' => $request->start_date,
    //         'end_date' => $request->end_date,
    //     ]);

    //     return redirect()
    //         ->route('banners.index')
    //         ->with('success', 'Banner updated successfully.');
    // }



    public function update(Request $request, Banners $banner)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $imageUrl = $banner->image_url; // ✅ FIXED

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
            'image_url' => $imageUrl, // ✅ FIXED
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    // public function destroy(banners $banner)
    // {
    //     $banner->delete();

    //     return redirect()->route('banners.index')->with('success', 'Banner deleted successfully.');
    // }

    public function destroy(Banners $banner)
    {
        // Delete image from R2 if exists
        if ($banner->image_url) {
            $path = parse_url($banner->image_url, PHP_URL_PATH);

            // remove leading slash
            $path = ltrim($path, '/');

            Storage::disk('r2')->delete($path);
        }

        // Delete record
        $banner->delete();

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner deleted successfully.');
    }
}
