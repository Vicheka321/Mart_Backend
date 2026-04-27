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

    public function bestSellers()
    {
        $today = Carbon::today();

        $products = ProductsModel::leftJoin(
            'order_items',
            'products.id',
            '=',
            'order_items.product_id'
        )
            ->select('products.*')
            ->selectRaw('COALESCE(SUM(order_items.qty),0) as sold')
            ->groupBy('products.id')
            ->orderByDesc('sold')
            ->take(10)
            ->get();

        $products->load('image');

        $products = $products->map(function ($product) use ($today) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = PromotionModel::whereHas('products', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
                ->where('status', true)
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->first();

            if ($promotion) {

                if ($promotion->discount_type === 'percent') {
                    $final_price =
                        $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $discount = $promotion->discount_value . '%';
                } else {
                    $final_price =
                        $product->sale_price - $promotion->discount_value;

                    $discount = '$' . $promotion->discount_value;
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sale_price' => $product->sale_price,
                'final_price' => round($final_price, 2),
                'discount' => $discount,
                'sold' => (int)$product->sold,
                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }

    public function newArrivals()
    {
        $today = Carbon::today();

        $products = ProductsModel::with('image')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $products = $products->map(function ($product) use ($today) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = PromotionModel::whereHas(
                'products',
                function ($q) use ($product) {
                    $q->where(
                        'product_id',
                        $product->id
                    );
                }
            )
                ->where('status', true)
                ->whereDate(
                    'start_date',
                    '<=',
                    $today
                )
                ->whereDate(
                    'end_date',
                    '>=',
                    $today
                )
                ->first();

            if ($promotion) {

                if (
                    $promotion->discount_type
                    === 'percent'
                ) {

                    $final_price =
                        $product->sale_price -
                        (
                            $product->sale_price *
                            $promotion->discount_value
                            / 100
                        );

                    $discount =
                        $promotion->discount_value . '%';
                } else {

                    $final_price =
                        $product->sale_price -
                        $promotion->discount_value;

                    $discount =
                        '$' .
                        $promotion->discount_value;
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,

                'sale_price' =>
                $product->sale_price,

                'final_price' =>
                round($final_price, 2),

                'discount' =>
                $discount,

                'images' =>
                $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }

    public function recommended()
    {
        $today = Carbon::today();

        $products = ProductsModel::with('image')
            ->withSum(
                'orderItems as sold',
                'qty'
            )
            ->where('status', 1)
            ->orderBy(
                'sale_price',
                'asc'
            )
            ->take(10)
            ->get();

        $products = $products->map(
            function ($product) use ($today) {

                $final_price =
                    $product->sale_price;

                $discount = null;

                $promotion =
                    PromotionModel::whereHas(
                        'products',
                        function ($q)
                        use ($product) {

                            $q->where(
                                'product_id',
                                $product->id
                            );
                        }
                    )
                    ->where(
                        'status',
                        true
                    )
                    ->whereDate(
                        'start_date',
                        '<=',
                        $today
                    )
                    ->whereDate(
                        'end_date',
                        '>=',
                        $today
                    )
                    ->first();

                if ($promotion) {

                    if (
                        $promotion->discount_type
                        === 'percent'
                    ) {

                        $final_price =
                            $product->sale_price -
                            (
                                $product->sale_price *
                                $promotion->discount_value
                                / 100
                            );

                        $discount =
                            $promotion
                            ->discount_value . '%';
                    } else {

                        $final_price =
                            $product->sale_price -
                            $promotion
                            ->discount_value;

                        $discount =
                            '$' .
                            $promotion
                            ->discount_value;
                    }
                }

                return [
                    'id' => $product->id,

                    'name' =>
                    $product->name,

                    'sale_price' =>
                    $product->sale_price,

                    'final_price' =>
                    round(
                        $final_price,
                        2
                    ),

                    'discount' =>
                    $discount,

                    'sold' =>
                    $product->sold ?? 0,

                    'images' =>
                    $product->image
                        ->pluck(
                            'image_url'
                        )
                        ->values(),
                ];
            }
        );

        return response()->json(
            $products
        );
    }
}

