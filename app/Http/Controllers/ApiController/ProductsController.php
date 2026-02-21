<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\PromotionModel;
use Carbon\Carbon;

class ProductsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $products = ProductsModel::with(['image'])->get();
        $products = $products->map(function ($product) use ($today) {
            $product->final_price = $product->sale_price;
            $product->discount = null;
            $promotion = PromotionModel::whereHas('products', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
                ->where('status', true)
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->first();

            if ($promotion) {

                if ($promotion->discount_type === 'percent') {
                    $product->final_price =
                        $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $product->discount = $promotion->discount_value . '%';
                } else {
                    $product->final_price = $product->sale_price - $promotion->discount_value;

                    $product->discount = '$' . $promotion->discount_value;
                }
            }
            $product->images = $product->image->pluck('image_url');
            unset($product->image);
            return $product;
        });

        return response()->json($products);
    }

    public function getProductById($id)
    {
        $product = ProductsModel::with(['category', 'brand', 'image'])->find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
        $product->images = $product->image->pluck('image_url');
        $product->category_name = optional($product->category)->name;
        $product->brand_name = optional($product->brand)->name;
        unset($product->image);
        unset($product->category);
        unset($product->brand);
        return response()->json($product);
    }
}


// if (product.final_price < product.sale_price) {
//     show sale_price (strikethrough)
//     show final_price (highlight)
//  } else {
//     show sale_price only
//  }
