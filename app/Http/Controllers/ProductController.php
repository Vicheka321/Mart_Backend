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
    //     $products = ProductsModel::with(['product','brand','image'])
    //     ->orderBy('id','desc')
    //     ->paginate(10);

    //     return view('admin.products', compact('products'));
    //     // return response()->json($products);
    // }



    public function index()
    {
        $products = ProductsModel::with([
            'category:id,name',
            'brand:id,name',
            'image' => function ($q) {
                $q->select('id', 'product_id', 'image_url')->limit(1);
            }
        ])
            ->orderBy('created_at', 'desc') // ✅ sort by latest created
            ->paginate(10);

        $categories = Category::all();
        $brands = BrandModel::all();
        return view('admin.products', compact('products', 'categories', 'brands'));
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
            'name' => 'required',
            'products_id' => 'required',
            'brand_id' => 'required',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image_url' => 'nullable|url'
        ]);

        $product = ProductsModel::findOrFail($id);
        $product->update([
            'categories_id' => $request->categories_id,
            'brand_id' => $request->brand_id,
            'product_code' => $request->product_code,
            'name' => $request->name,
            'description' => $request->description,
            'unit' => $request->unit,
            'cost_price' => $request->cost_price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'status' => $request->status ?? 1,
        ]);

        // update product image
        if ($request->image_url) {

            ProductsImageModel::updateOrCreate(
                ['product_id' => $product->id],
                ['image_url' => $request->image_url]
            );
        }

        return redirect()
            ->route('products.index', ['page' => request('page')]);
    }
    public function destroy($id)
    {
        $product = ProductsModel::findOrFail($id);
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }


    public function exportCSV()
    {
        $fileName = "products.csv";

        $products = ProductsModel::orderBy('id')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['ID', 'Name', 'Created At']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function exportPDF()
    {
        $products = ProductsModel::orderBy('id')->get();

        // $pdf = Pdf::loadView('admin.products_pdf', compact('products'))
        //     ->setPaper('A4', 'portrait');

        // return $pdf->download('products_' . now()->format('Ymd_His') . '.pdf');
        return response($products, 200);
    }
}
