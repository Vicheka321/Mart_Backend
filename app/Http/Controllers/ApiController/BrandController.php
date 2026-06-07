<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandModel;
use App\Models\ProductsModel;
use Carbon\Carbon;

class BrandController extends Controller
{
    // public function index()
    // {
    //     return BrandModel::all();
    // }

    public function index()
    {
        $brands = BrandModel::with([
            'products.firstImage'
        ])->get();

        return response()->json($brands);
    }
    // public function getProductById($id)
    // {
    //     $brand = BrandModel::find($id);
    //     $products = ProductsModel::where('brand_id', $brand->id)->get();
    //     $brand -> products = $products;
    //     if (!$brand) {
    //         return response()->json(['message' => 'Brand not found'], 404);
    //     }
    //     return response()->json($brand);
    // }

    public function getProductsByBrand($id)
    {
        $today = Carbon::today();

        $brand = BrandModel::with([
            'products.image',
            'products.category',
            'products.promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])->find($id);

        if (!$brand) {
            return response()->json([
                'message' => 'Brand not found'
            ], 404);
        }

        $products = $brand->products->map(function ($product) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = $product->promotions->first();

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {

                    $final_price = $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $discount = $promotion->discount_value . '%';
                } else {

                    $final_price = $product->sale_price - $promotion->discount_value;

                    $discount = '$' . $promotion->discount_value;
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'unit' => $product->unit,
                'quantity' => $product->quantity,

                'sale_price' => $product->sale_price,
                'final_price' => number_format($final_price, 2, '.', ''),
                'discount' => $discount,

                'category_name' => optional($product->category)->name,
                'brand_name' => optional($product->brand)->name,

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json([
            'id' => $brand->id,
            'name' => $brand->name,
            'country' => $brand->country,
            'products' => $products
        ]);
    }
}
