<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class BrandsController extends Controller
{
    // public function index()
    // {
    //     $brands = BrandModel::orderBy('id')->paginate(10);
    //     return view('Admin.brands', compact('brands'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

    //     ]);
    //     $imageUrl = null;
    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'brands/' . $fileName;
    //         Storage::disk('r2')->put(
    //             $path,
    //             file_get_contents($file),
    //             'public'
    //         );
    //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    //     }
    //     BrandModel::create([
    //         'name' => $request->name,
    //         'image' => $imageUrl,

    //     ]);

    //     return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //         'country' => 'nullable|string|max:255',

    //     ]);
    //     $brand = BrandModel::find($id);
    //     $imageUrl = $brand->image;
    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'brands/' . $fileName;
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
    //         'country' => $request->country,

    //     ]);

    //     return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    // }

    // public function destroy($id)
    // {
    //     $brand = BrandModel::find($id);
    //     $brand->delete();
    //     return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    // }


    // public function exportCSV()
    // {
    //     $fileName = "brands.csv";

    //     $brands = BrandModel::orderBy('id')->get();

    //     $headers = [
    //         "Content-type" => "text/csv",
    //         "Content-Disposition" => "attachment; filename={$fileName}",
    //     ];

    //     $callback = function () use ($brands) {
    //         $file = fopen('php://output', 'w');

    //         // header
    //         fputcsv($file, ['ID', 'Name', 'Image', 'Country', 'Created At']);

    //         foreach ($brands as $brand) {
    //             fputcsv($file, [
    //                 $brand->id,
    //                 $brand->name,
    //                 $brand->image,
    //                 $brand->country,
    //                 $brand->created_at
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);

    // }

    // public function exportPDF()
    // {
    //     $brands = BrandModel::orderBy('id')->get();

    //     $pdf = Pdf::loadView('Admin.PDF.brands_pdf', compact('brands'));

    //     return $pdf->download('brands.pdf');
    // }

    public function index()
    {
        $brands = BrandModel::orderBy('id')->paginate(10);
        return view('Admin.brands', compact('brands'));
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

                    $exists = BrandModel::get()->contains(function ($brand) use ($normalized) {
                        return strtolower(
                            preg_replace('/\s+/', '', trim($brand->name))
                        ) === $normalized;
                    });

                    if ($exists) {
                        $fail('Brand name already exists.');
                    }
                }
            ],
            'country' => 'nullable|string|max:255',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'name'    => $request->name,
            'image'   => $imageUrl,
            'country' => $request->country,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully.',
            ]);
        }

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
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

                    $exists = BrandModel::where('id', '!=', $id)
                        ->get()
                        ->contains(function ($brand) use ($normalized) {
                            return strtolower(
                                preg_replace('/\s+/', '', trim($brand->name))
                            ) === $normalized;
                        });

                    if ($exists) {
                        $fail('Brand name already exists.');
                    }
                }
            ],
            'country' => 'nullable|string|max:255',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $brand = BrandModel::findOrFail($id);
        $imageUrl = $brand->image;

        if ($request->hasFile('image')) {

            // Delete old logo from R2 before uploading the new one
            if ($brand->image) {
                $baseUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/';
                $oldPath = str_replace($baseUrl, '', $brand->image);

                if (Storage::disk('r2')->exists($oldPath)) {
                    Storage::disk('r2')->delete($oldPath);
                }
            }

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
            'name'    => $request->name,
            'image'   => $imageUrl,
            'country' => $request->country,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully.',
            ]);
        }

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $brand = BrandModel::findOrFail($id);
        $imageUrl = $brand->image;

        if ($imageUrl) {
            $baseUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/';
            $oldPath = str_replace($baseUrl, '', $imageUrl);

            if (Storage::disk('r2')->exists($oldPath)) {
                Storage::disk('r2')->delete($oldPath);
            }
        }

        $brand->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully.',
            ]);
        }

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }

    public function exportCSV()
    {
        $fileName = "brands.csv";

        $brands = BrandModel::orderBy('id')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($brands) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Name', 'Image', 'Country', 'Created At']);

            foreach ($brands as $brand) {
                fputcsv($file, [
                    $brand->id,
                    $brand->name,
                    $brand->image,
                    $brand->country,
                    $brand->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF()
    {
        $brands = BrandModel::orderBy('id')->get();

        $pdf = Pdf::loadView('Admin.PDF.brands_pdf', compact('brands'));

        return $pdf->download('brands.pdf');
    }
}
