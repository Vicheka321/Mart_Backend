<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductsModel;
use Carbon\Carbon;
use App\Models\PromotionModel;

class CategoriesController extends Controller
{
    // public function index()
    // {
    //     return Category::all();
    // }

    public function index()
    {
        $categories = Category::with([
            'products.firstImage'
        ])->get();

        return response()->json($categories);
    }

    public function getProductsByCategory($id)
    {
        $today = Carbon::today();

        $category = Category::with([
            'products.image',
            'products.brand',
            'products.promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $products = $category->products->map(function ($product) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = $product->promotions->first(); // already filtered

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
            'id' => $category->id,
            'name' => $category->name,
            'products' => $products
        ]);
    }
}
