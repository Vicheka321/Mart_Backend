<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use App\Models\ProductsImageModel;
use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\CategoriesModel;
use App\Models\BrandsModel;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    // public function index()
    // {
    //     $products = ProductsModel::with([
    //         'category:id,name',
    //         'brand:id,name',
    //         'firstImage:id,product_id,image_url'
    //     ])
    //         ->latest()
    //         ->paginate(10);

    //     $categories = Category::select('id', 'name')->get();
    //     $brands = BrandModel::select('id', 'name')->get();

    //     return view('admin.products', compact('products', 'categories', 'brands'));
    // }

    // public function index(Request $request)
    // {
    //     $statusFilter = $request->input('status', 'all');

    //     $products = ProductsModel::with([
    //         'category:id,name',
    //         'brand:id,name',
    //         'image:id,product_id,image_url',
    //     ])
    //         ->when($statusFilter !== 'all', function ($q) use ($statusFilter) {
    //             if ($statusFilter === 'active') {
    //                 $q->where('status', 1);
    //             } elseif ($statusFilter === 'inactive') {
    //                 $q->where('status', 0);
    //             } elseif ($statusFilter === 'low-stock') {
    //                 $q->where('quantity', '<=', 10);
    //             }
    //         })
    //         ->latest()
    //         ->paginate(10);

    //     $categories = Category::select('id', 'name')->get();
    //     $brands     = BrandModel::select('id', 'name')->get();
    //     $totalProducts = ProductsModel::count();
    //     $totalActive = ProductsModel::where('status', 1)->count();
    //     $totalLowStock = ProductsModel::where('quantity', '<=', 10)->count();


    //     return view('admin.products', compact(
    //         'products',
    //         'categories',
    //         'brands',
    //         'statusFilter',
    //         'totalProducts',
    //         'totalActive',
    //         'totalLowStock'

    //     ));
    // }

    public function index(Request $request)
    {
        // Default filter = inactive
        $statusFilter = $request->input('status', 'all');

        // Build optimized query
        $query = ProductsModel::query()
            ->with([
                'category:id,name',
                'brand:id,name',
                'image:id,product_id,image_url',
            ]);

        // Apply status filter
        switch ($statusFilter) {
            case 'active':
                $query->where('status', 1);
                break;

            case 'inactive':
                $query->where('status', 0);
                break;

            case 'low-stock':
                $query->where('quantity', '<=', 10);
                break;

                // case 'all' => no filter
        }

        // Products list
        $products = $query
            ->latest('id')   // faster than latest()
            ->paginate(10)
            ->withQueryString();

        // Required for Add/Edit Product modal
        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();

        $brands = BrandModel::select('id', 'name')
            ->orderBy('name')
            ->get();

        // Summary cards
        $totalProducts = ProductsModel::count();
        $totalActive   = ProductsModel::where('status', 1)->count();
        $totalLowStock = ProductsModel::where('quantity', '<=', 10)->count();

        return view('admin.products', compact(
            'products',
            'categories',
            'brands',
            'statusFilter',
            'totalProducts',
            'totalActive',
            'totalLowStock'
        ));
    }

    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'name' => 'required',
    //         'products_id' => 'required',
    //         'brand_id' => 'required',
    //         'cost_price' => 'required|numeric',
    //         'sale_price' => 'required|numeric',
    //         'quantity' => 'required|integer',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //     ]);

    //     $imageUrl = null;

    //     if ($request->hasFile('image')) {

    //         $file = $request->file('image');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'products/' . $fileName;

    //         Storage::disk('r2')->put(
    //             $path,
    //             file_get_contents($file),
    //             'public'
    //         );

    //         $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
    //     }

    //     $product = ProductsModel::create([
    //         'products_id' => $request->products_id,
    //         'brand_id' => $request->brand_id,
    //         'product_code' => $request->product_code,
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'unit' => $request->unit,
    //         'cost_price' => $request->cost_price,
    //         'sale_price' => $request->sale_price,
    //         'quantity' => $request->quantity,
    //         'status' => $request->status ?? 1,
    //     ]);

    //     if ($imageUrl) {
    //         ProductsImageModel::create([
    //             'product_id' => $product->id,
    //             'image_url' => $imageUrl,
    //         ]);
    //     }

    //     return redirect()
    //         ->route('products.index')
    //         ->with('success', 'Product created successfully.');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'categories_id' => 'required|exists:categories,id',
            'brand_id'      => 'required|exists:brands,id',
            'cost_price'    => 'nullable|numeric',
            'sale_price'    => 'required|numeric',
            'quantity'      => 'required|integer',
            'images'        => 'nullable|array|max:10',        // ✅ multiple
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // ✅ Create product
        $product = ProductsModel::create([
            'categories_id' => $request->categories_id,
            'brand_id'      => $request->brand_id,
            'product_code'  => $request->product_code,
            'name'          => $request->name,
            'description'   => $request->description,
            'unit'          => $request->unit,
            'cost_price'    => $request->cost_price,
            'sale_price'    => $request->sale_price,
            'quantity'      => $request->quantity,
            'status'        => $request->status ?? 1,
        ]);



        // ✅ Upload each image to R2 and save to product_images
        if ($request->file('images')) {
            foreach ($request->file('images') as $file) {

                if (!$file->isValid()) continue;

                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = 'products/' . $fileName;

                Storage::disk('r2')->put(
                    $path,
                    file_get_contents($file),
                    'public'
                );

                $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;

                ProductsImageModel::create([
                    'product_id' => $product->id,
                    'image_url'  => $imageUrl,
                ]);
            }
        }


        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required',
            'categories_id' => 'required|exists:categories,id',
            'brand_id'      => 'required|exists:brands,id',
            'cost_price'    => 'nullable|numeric',
            'sale_price'    => 'required|numeric',
            'quantity'      => 'required|integer',
            'status'        => 'nullable|in:0,1',

            // Multiple image upload
            'images'        => 'nullable|array|max:10',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $id) {

            // Find product
            $product = ProductsModel::findOrFail($id);

            // Update product information
            $product->update([
                'categories_id' => $request->categories_id,
                'brand_id'      => $request->brand_id,
                'product_code'  => $request->product_code,
                'name'          => $request->name,
                'description'   => $request->description,
                'unit'          => $request->unit,
                'cost_price'    => $request->cost_price,
                'sale_price'    => $request->sale_price,
                'quantity'      => $request->quantity,
                'status'        => $request->status ?? 1,
            ]);

            // If new images are uploaded, replace all old images
            if ($request->hasFile('images')) {

                // Get old images
                $oldImages = ProductsImageModel::where('product_id', $product->id)->get();

                // Delete old files from Cloudflare R2
                foreach ($oldImages as $oldImage) {
                    if ($oldImage->image_url) {
                        $baseUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/';

                        // Convert full URL to relative path
                        $path = str_replace($baseUrl, '', $oldImage->image_url);

                        // Delete file if it exists
                        if (Storage::disk('r2')->exists($path)) {
                            Storage::disk('r2')->delete($path);
                        }
                    }
                }

                // Delete old image records from database
                ProductsImageModel::where('product_id', $product->id)->delete();

                // Upload each new image
                foreach ($request->file('images') as $file) {

                    if (!$file->isValid()) {
                        continue;
                    }

                    // Generate unique filename
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = 'products/' . $fileName;

                    // Upload to Cloudflare R2
                    Storage::disk('r2')->put(
                        $path,
                        file_get_contents($file),
                        'public'
                    );

                    // Build public URL
                    $imageUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;

                    // Save to database
                    ProductsImageModel::create([
                        'product_id' => $product->id,
                        'image_url'  => $imageUrl,
                    ]);
                }
            }
        });

        return redirect()
            ->route('products.index', ['page' => request('page')])
            ->with('success', 'Product updated successfully.');
    }
    public function destroy($id)
    {
        $product = ProductsModel::findOrFail($id);
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }


    // public function exportCSV()
    // {
    //     $fileName = "products.csv";

    //     $products = ProductsModel::orderBy('id')->get();

    //     $headers = [
    //         "Content-type" => "text/csv",
    //         "Content-Disposition" => "attachment; filename={$fileName}",
    //     ];

    //     $callback = function () use ($products) {
    //         $file = fopen('php://output', 'w');

    //         // Header
    //         fputcsv($file, ['ID', 'Name', 'Created At']);

    //         foreach ($products as $product) {
    //             fputcsv($file, [
    //                 $product->id,
    //                 $product->name,
    //                 $product->created_at->format('Y-m-d H:i')
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }
    public function exportCSV()
    {
        $fileName = 'products_' . now()->format('Ymd_His') . '.csv';

        // Load relationships to avoid N+1 queries
        $products = ProductsModel::with([
            'category:id,name',
            'brand:id,name'
        ])
            ->orderBy('id')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Header
            fputcsv($file, [
                'ID',
                // 'Product Code',
                'Name',
                'Category',
                'Brand',
                'Description',
                // 'Unit',
                'Cost Price',
                'Sale Price',
                'Quantity',
                'Status',
                'Created At',
            ]);

            // CSV Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    // $product->product_code,
                    $product->name,
                    $product->category->name ?? '',
                    $product->brand->name ?? '',
                    $product->description,
                    // $product->unit,
                    number_format($product->cost_price ?? 0, 2, '.', ''),
                    number_format($product->sale_price ?? 0, 2, '.', ''),
                    $product->quantity,
                    $product->status ? 'Active' : 'Inactive',
                    $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    // public function exportPDF()
    // {
    //     $products = ProductsModel::orderBy('id')->get();

    //     $pdf = Pdf::loadView('admin.products_pdf', compact('products'))
    //         ->setPaper('A4', 'portrait');

    //     return $pdf->download('products_' . now()->format('Ymd_His') . '.pdf');

    // }

    public function exportPDF()
    {
        // Load relationships to avoid N+1 queries in the PDF view
        $products = ProductsModel::with([
            'category:id,name',
            'brand:id,name'
        ])
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView(
            'admin.PDF.products_pdf',
            compact('products')
        )
            ->setPaper('A4', 'portrait');

        return $pdf->download(
            'products_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
