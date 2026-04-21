<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id')->paginate(10);
        return view('Admin.categories', compact('categories'));
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

            $path = 'categories/' . $fileName;
            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );

            $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        }


        Category::create([
            'name' => $request->name,
            'image' => $imageUrl,

        ]);

        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);
        $brand = Category::find($id);
        $imageUrl = $brand->image;
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
        $brand->update([
            'name' => $request->name,
            'image' => $imageUrl,

        ]);

        return redirect()->route('categories.index')->with('success', 'category updated successfully.');
    }
    // public function update(Request $request, Category $category)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

    //     ]);

    //     $imageUrl = $category->image; 

    //     if ($request->hasFile('image')) {


    //         if ($category->image) {
    //             $oldPath = str_replace(rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/', '', $category->image);
    //             Storage::disk('r2')->delete($oldPath);
    //         }

    //         $file = $request->file('image');
    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
    //         $path = 'categories/' . $fileName;

    //         // Upload new image to R2
    //         Storage::disk('r2')->put(
    //             $path,
    //             file_get_contents($file),
    //             'public'
    //         );

    //         // Save FULL public URL
    //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    //     }

    //     $category->update([
    //         'name' => $request->name,
    //         'image' => $imageUrl,
    //     ]);

    //     return back()->with('success', 'Category updated successfully!');
    // }



    public function destroy($id)
    {

        $category = Category::findOrFail($id);
        $imageUrl = $category->image;

        if ($imageUrl) {
            $oldPath = str_replace(rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/', '', $imageUrl);
            Storage::disk('r2')->delete($oldPath);
        }

        $category->delete();

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

            // Header
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

        $pdf = Pdf::loadView('admin.categories_pdf', compact('categories'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('categories_' . now()->format('Ymd_His') . '.pdf');
    }
}
