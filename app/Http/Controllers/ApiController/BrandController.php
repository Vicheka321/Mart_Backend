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
    //     $brands = BrandModel::with([
    //         'products.firstImage'
    //     ])->get();

    //     return response()->json($brands);
    // }

    public function index()
    {
        $brands = BrandModel::with([
            'products.firstImage',
            'products.promotions',
        ])->get();

        $brands->each(function ($brand) {

            $brand->products->transform(function ($product) {

                $promotion = $product->promotions()
                    ->where('status', true)
                    ->where(function ($q) {
                        $q->whereNull('start_date')
                            ->orWhere('start_date', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhere('end_date', '>=', now()->toDateString());
                    })
                    ->orderByDesc('discount_value')
                    ->first();

                $salePrice = (float) $product->sale_price;
                $finalPrice = $salePrice;
                $discount = null;

                if ($promotion) {

                    if ($promotion->discount_type === 'percent') {

                        $finalPrice = $salePrice -
                            ($salePrice * $promotion->discount_value / 100);

                        if (!is_null($promotion->max_discount)) {

                            $discountAmount = min(
                                $salePrice - $finalPrice,
                                $promotion->max_discount
                            );

                            $finalPrice = $salePrice - $discountAmount;
                        }
                    } else {

                        $finalPrice = $salePrice - $promotion->discount_value;
                    }

                    $finalPrice = max(0, $finalPrice);

                    $discount = [
                        'discount_type' => $promotion->discount_type,
                        'discount_value' => $promotion->discount_value,
                    ];
                }

                $product->sale_price = number_format($salePrice, 2, '.', '');
                $product->final_price = number_format($finalPrice, 2, '.', '');
                $product->discount = $discount;

                unset($product->promotions);

                return $product;
            });
        });

        return response()->json($brands);
    }


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
