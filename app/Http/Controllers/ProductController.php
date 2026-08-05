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
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        $statusFilter = $request->input('status', 'all');
        $productKeywordSearch = trim($request->input('search'));


        $query = ProductsModel::query()
            ->with([
                'category:id,name',
                'brand:id,name',
                'image:id,product_id,image_url',
            ]);


        switch ($statusFilter) {
            case 'active':
                $query->where('status', 1);
                break;

            case 'inactive':
                $query->where('status', 0);
                break;

            case 'low-stock':
                $query->where('quantity', '<=', 20);
                break;


        }

        if (!empty($productKeywordSearch)) {

            $query->where(function ($productSearchQuery) use ($productKeywordSearch) {

                $productSearchQuery
                    ->where('name', 'LIKE', "%{$productKeywordSearch}%")

                    ->orWhere('product_code', 'LIKE', "%{$productKeywordSearch}%")

                    ->orWhere('description', 'LIKE', "%{$productKeywordSearch}%")

                    ->orWhereHas('category', function ($categorySearchQuery) use ($productKeywordSearch) {

                        $categorySearchQuery->where(
                            'name',
                            'LIKE',
                            "%{$productKeywordSearch}%"
                        );
                    })

                    ->orWhereHas('brand', function ($brandSearchQuery) use ($productKeywordSearch) {

                        $brandSearchQuery->where(
                            'name',
                            'LIKE',
                            "%{$productKeywordSearch}%"
                        );
                    });
            });
        }

   
        $products = $query
            ->latest('id') 
            ->paginate(10)
            ->withQueryString();

  
        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();

        $brands = BrandModel::select('id', 'name')
            ->orderBy('name')
            ->get();

  
        $totalProducts = ProductsModel::count();
        $totalActive   = ProductsModel::where('status', 1)->count();
        $totalInactive = ProductsModel::where('status', 0)->count();
        $totalLowStock = ProductsModel::where('quantity', '<=', 20)->count();

        return view('Admin.products', compact(
            'products',
            'categories',
            'brands',
            'statusFilter',
            'totalProducts',
            'totalActive',
            'totalLowStock',
            'totalInactive'
        ));
    }


 


    public function store(Request $request)
    {
        $request->merge([
            'name' => preg_replace(
                '/\s+/',
                ' ',
                trim($request->input('name'))
            ),
        ]);

        $request->validate([
            'name' => [
                'required',
                'max:255',

                function ($attribute, $value, $fail) {

                    $normalized = strtolower(
                        preg_replace('/\s+/', '', strtolower(trim($value)))
                    );

                    $exists = ProductsModel::get()->contains(function ($product) use ($normalized) {

                        return strtolower(
                            preg_replace('/\s+/', '', trim($product->name))
                        ) === $normalized;
                    });

                    if ($exists) {
                        $fail('Product name already exists.');
                    }
                },
            ],

            'categories_id' => 'required|exists:categories,id',
            'brand_id'      => 'required|exists:brands,id',
            'cost_price'    => 'nullable|numeric',
            'sale_price'    => 'required|numeric|gte:cost_price',
            'quantity'      => 'required|integer',
            'images'        => 'required|array|min:1|max:10',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.unique'      => 'Product name already exists.',
            'sale_price.gte'   => 'Sale price cannot be less than cost price.',
            'images.required'  => 'Please upload at least one product image.',
            'images.min'       => 'Please upload at least one product image.',
        ]);

     
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


      
        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'name' => preg_replace(
                '/\s+/',
                ' ',
                trim($request->input('name'))
            ),
        ]);
        $request->validate([
            'name' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($id) {

                    $normalized = strtolower(
                        preg_replace('/\s+/', '', strtolower(trim($value)))
                    );

                    $exists = ProductsModel::where('id', '!=', $id)
                        ->get()
                        ->contains(function ($product) use ($normalized) {

                            return strtolower(
                                preg_replace('/\s+/', '', trim($product->name))
                            ) === $normalized;
                        });

                    if ($exists) {
                        $fail('Product name already exists.');
                    }
                },
            ],

            'categories_id' => 'required|exists:categories,id',
            'brand_id'      => 'required|exists:brands,id',
            'cost_price'    => 'nullable|numeric',
            'sale_price'    => 'required|numeric|gte:cost_price',
            'quantity'      => 'required|integer',
            'status'        => 'nullable|in:0,1',

            'images'        => 'nullable|array|max:10',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.unique'    => 'Product name already exists.',
            'sale_price.gte' => 'Sale price cannot be less than cost price.',
        ]);
        $product = ProductsModel::findOrFail($id);
        $hasNewImages      = $request->hasFile('images') && count($request->file('images')) > 0;
        $hasExistingImages = $product->image()->exists();

        if (!$hasNewImages && !$hasExistingImages) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['images' => ['Please upload at least one product image.']],
                ], 422);
            }

            return back()
                ->withErrors(['images' => 'Please upload at least one product image.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $id) {

            $product = ProductsModel::findOrFail($id);

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

      
            if ($request->hasFile('images')) {

            
                $oldImages = ProductsImageModel::where('product_id', $product->id)->get();

             
                foreach ($oldImages as $oldImage) {
                    if ($oldImage->image_url) {
                        $baseUrl = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/';

                     
                        $path = str_replace($baseUrl, '', $oldImage->image_url);

                   
                        if (Storage::disk('r2')->exists($path)) {
                            Storage::disk('r2')->delete($path);
                        }
                    }
                }

             
                ProductsImageModel::where('product_id', $product->id)->delete();

         
                foreach ($request->file('images') as $file) {

                    if (!$file->isValid()) {
                        continue;
                    }

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
        });

 
        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
            ]);
        }
        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }
    public function destroy($id)
    {
        $product = ProductsModel::findOrFail($id);
        $product->delete();
 
        if (request()->ajax()) {

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
    public function exportCSV()
    {
        $fileName = 'products_' . now()->format('Ymd_His') . '.csv';

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

       
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID',
             
                'Name',
                'Category',
                'Brand',
                'Description',
             
                'Cost Price',
                'Sale Price',
                'Quantity',
                'Status',
                'Created At',
            ]);

           
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                  
                    $product->name,
                    $product->category->name ?? '',
                    $product->brand->name ?? '',
                    $product->description,
                
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

    public function exportPDF()
    {


        $products = ProductsModel::with([
            'category:id,name',
            'brand:id,name',
            'firstImage:id,product_id,image_url', // or image if that's your relationship
        ])
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView(
            'Admin.PDF.products_pdf',
            compact('products')
        )
            ->setPaper('A4', 'portrait');


        return $pdf->download(
            'products_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
