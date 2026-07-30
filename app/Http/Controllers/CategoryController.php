<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{
    // public function index()
    // {
    //     $categories = Category::orderBy('id')->paginate(10);

    //     return view('Admin.categories', compact('categories'));
    // }
    // public function index(Request $request)
    // {
    //     $query = Category::query();

    //     if ($request->filled('search')) {

    //         $query->where('name', 'LIKE', '%' . trim($request->search) . '%');
    //     }

    //     $categories = $query
    //         ->latest('id')
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view('Admin.categories', compact('categories'));
    // }

    // public function store(Request $request)
    // {
    //     $request->merge([
    //         'name' => preg_replace('/\s+/', ' ', trim($request->name))
    //     ]);
    //     $request->validate([
    //         'name' => [

    //             'required',

    //             'string',

    //             'max:255',

    //             function ($attribute, $value, $fail) {

    //                 $normalized = strtolower(
    //                     preg_replace('/\s+/', ' ', trim($value))
    //                 );

    //                 $exists = Category::get()->contains(function ($category) use ($normalized) {

    //                     return strtolower(
    //                         preg_replace('/\s+/', ' ', trim($category->name))
    //                     ) === $normalized;
    //                 });

    //                 if ($exists) {

    //                     $fail('Category name already exists.');
    //                 }
    //             }

    //         ],
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

    //     ]);
    //     $imageUrl = null;
    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'categories/' . $fileName;
    //         Storage::disk('r2')->put(
    //             $path,
    //             file_get_contents($file),
    //             'public'
    //         );

    //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    //     }


    //     Category::create([
    //         'name' => $request->name,
    //         'image' => $imageUrl,

    //     ]);

    //     return back()->with('success', 'Category created successfully!');
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->merge([
    //         'name' => preg_replace('/\s+/', ' ', trim($request->name))
    //     ]);
    //     $request->validate([
    //         'name' => [

    //             'required',

    //             'string',

    //             'max:255',

    //             function ($attribute, $value, $fail) use ($id) {

    //                 $normalized = strtolower(
    //                     preg_replace('/\s+/', ' ', trim($value))
    //                 );

    //                 $exists = Category::where('id', '!=', $id)
    //                     ->get()
    //                     ->contains(function ($category) use ($normalized) {

    //                         return strtolower(
    //                             preg_replace('/\s+/', ' ', trim($category->name))
    //                         ) === $normalized;
    //                     });

    //                 if ($exists) {

    //                     $fail('Category name already exists.');
    //                 }
    //             }

    //         ],
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

    //     ]);
    //     $brand = Category::find($id);
    //     $imageUrl = $brand->image;
    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'categories/' . $fileName;
    //         Storage::disk('r2')->put(
    //             $path,
    //             file_get_contents($file),
    //             'public'
    //         );
    //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    //     }
    //     $brand->update([
    //         'name' => $request->name,
    //         'image' => $imageUrl,

    //     ]);

    //     return redirect()->route('categories.index')->with('success', 'category updated successfully.');
    // }
    // // public function update(Request $request, Category $category)
    // // {
    // //     $request->validate([
    // //         'name' => 'required|string|max:255',
    // //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

    // //     ]);

    // //     $imageUrl = $category->image; 

    // //     if ($request->hasFile('image')) {


    // //         if ($category->image) {
    // //             $oldPath = str_replace(rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/', '', $category->image);
    // //             Storage::disk('r2')->delete($oldPath);
    // //         }

    // //         $file = $request->file('image');
    // //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
    // //         $path = 'categories/' . $fileName;

    // //         // Upload new image to R2
    // //         Storage::disk('r2')->put(
    // //             $path,
    // //             file_get_contents($file),
    // //             'public'
    // //         );

    // //         // Save FULL public URL
    // //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    // //     }

    // //     $category->update([
    // //         'name' => $request->name,
    // //         'image' => $imageUrl,
    // //     ]);

    // //     return back()->with('success', 'Category updated successfully!');
    // // }



    // public function destroy($id)
    // {

    //     $category = Category::findOrFail($id);
    //     $imageUrl = $category->image;

    //     if ($imageUrl) {
    //         $oldPath = str_replace(rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/', '', $imageUrl);
    //         Storage::disk('r2')->delete($oldPath);
    //     }

    //     $category->delete();

    //     return redirect()->back()->with('success', 'Category deleted');
    // }


    // public function exportCSV()
    // {
    //     $fileName = "categories.csv";

    //     $categories = Category::orderBy('id')->get();

    //     $headers = [
    //         "Content-type" => "text/csv",
    //         "Content-Disposition" => "attachment; filename={$fileName}",
    //     ];

    //     $callback = function () use ($categories) {
    //         $file = fopen('php://output', 'w');

    //         // Header
    //         fputcsv($file, ['ID', 'Name', 'Image URL', 'Created At']);

    //         foreach ($categories as $category) {
    //             fputcsv($file, [
    //                 $category->id,
    //                 $category->name,
    //                 $category->image ?? 'N/A',
    //                 $category->created_at->format('Y-m-d H:i')
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }

    // public function exportPDF()
    // {
    //     $categories = Category::orderBy('id')->get();

    //     $pdf = Pdf::loadView('Admin.PDF.categories_pdf', compact('categories'))
    //         ->setPaper('A4', 'portrait');

    //     return $pdf->download('categories_' . now()->format('Ymd_His') . '.pdf');
    // }

    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . trim($request->search) . '%');
        }

        $categories = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('Admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim($request->name))
        ]);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $normalized = strtolower(
                        preg_replace('/\s+/', '', strtolower(trim($value)))
                    );

                    $exists = Category::get()->contains(function ($category) use ($normalized) {
                        return strtolower(
                            preg_replace('/\s+/', '', trim($category->name))
                        ) === $normalized;
                    });

                    if ($exists) {
                        $fail('Category name already exists.');
                    }
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = 'categories/' . $fileName;

            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );

            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }

        Category::create([
            'name'  => $request->name,
            'image' => $imageUrl,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
            ]);
        }

        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim($request->name))
        ]);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($id) {
                    $normalized = strtolower(
                        preg_replace('/\s+/', '', strtolower(trim($value)))
                    );

                    $exists = Category::where('id', '!=', $id)
                        ->get()
                        ->contains(function ($category) use ($normalized) {
                            return strtolower(
                                preg_replace('/\s+/', '', trim($category->name))
                            ) === $normalized;
                        });

                    if ($exists) {
                        $fail('Category name already exists.');
                    }
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $imageUrl = $category->image;

        if ($request->hasFile('image')) {

            // Delete old image from R2 before uploading the new one
            if ($category->image) {
                $baseUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/';
                $oldPath = str_replace($baseUrl, '', $category->image);

                if (Storage::disk('r2')->exists($oldPath)) {
                    Storage::disk('r2')->delete($oldPath);
                }
            }

            $file = $request->file('image');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = 'categories/' . $fileName;

            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );

            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }

        $category->update([
            'name'  => $request->name,
            'image' => $imageUrl,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
            ]);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $imageUrl = $category->image;

        if ($imageUrl) {
            $baseUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/';
            $oldPath = str_replace($baseUrl, '', $imageUrl);

            if (Storage::disk('r2')->exists($oldPath)) {
                Storage::disk('r2')->delete($oldPath);
            }
        }

        $category->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Category deleted');
    }

    public function exportCSV()
    {
        $fileName = "categories.csv";

        $categories = Category::orderBy('id')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Name', 'Image URL', 'Created At']);

            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->image ?? 'N/A',
                    $category->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF()
    {
        $categories = Category::orderBy('id')->get();

        $pdf = Pdf::loadView('Admin.PDF.categories_pdf', compact('categories'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('categories_' . now()->format('Ymd_His') . '.pdf');
    }
}
