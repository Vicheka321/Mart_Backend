<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\PromotionModel;
use Carbon\Carbon;

class ProductsController extends Controller
{
    // public function index()
    // {
    //     $today = Carbon::today();
    //     $products = ProductsModel::with(['image'])->get();
    //     $products = $products->map(function ($product) use ($today) {
    //         $product->final_price = $product->sale_price;
    //         $product->discount = null;
    //         $promotion = PromotionModel::whereHas('products', function ($q) use ($product) {
    //             $q->where('product_id', $product->id);
    //         })
    //             ->where('status', true)
    //             ->whereDate('start_date', '<=', $today)
    //             ->whereDate('end_date', '>=', $today)
    //             ->first();

    //         if ($promotion) {

    //             if ($promotion->discount_type === 'percent') {
    //                 $product->final_price =
    //                     $product->sale_price -
    //                     ($product->sale_price * $promotion->discount_value / 100);

    //                 $product->discount = $promotion->discount_value . '%';
    //             } else {
    //                 $product->final_price = $product->sale_price - $promotion->discount_value;

    //                 $product->discount = '$' . $promotion->discount_value;
    //             }
    //         }
    //         $product->images = $product->image->pluck('image_url');
    //         unset($product->image);
    //         return $product;
    //     });

    //     return response()->json($products);
    // }

    public function index()
    {
        $today = Carbon::today();

        $products = ProductsModel::with([
            'image',
            'category',
            'brand',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])
            ->where('status', true)
            ->get();

        $products = $products->map(function ($product) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = $product->promotions->first();

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {

                    $final_price = $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $discount = number_format(
                        $promotion->discount_value,
                        2
                    ) . '%';
                } else {

                    $final_price = $product->sale_price -
                        $promotion->discount_value;

                    $discount = '$' . number_format(
                        $promotion->discount_value,
                        2
                    );
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'unit' => $product->unit,
                'quantity' => $product->quantity,

                'sale_price' => number_format(
                    $product->sale_price,
                    2,
                    '.',
                    ''
                ),

                'final_price' => number_format(
                    $final_price,
                    2,
                    '.',
                    ''
                ),

                'discount' => $discount,

                'category_name' => optional($product->category)->name,
                'brand_name' => optional($product->brand)->name,

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }

    public function getProductById($id)
    {
        $today = Carbon::today();

        $product = ProductsModel::with([
            'category',
            'brand',
            'image',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

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

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,

            // ✅ formatted prices
            'sale_price' => number_format($product->sale_price, 2, '.', ''),
            'final_price' => number_format($final_price, 2, '.', ''),

            'discount' => $discount,
            'quantity' => $product->quantity,
            'status' => $product->status,

            'category_name' => optional($product->category)->name,
            'brand_name' => optional($product->brand)->name,

            'images' => $product->image
                ->pluck('image_url')
                ->values(),
        ]);
    }

    // public function getProductById($id)
    // {
    //     $today = Carbon::today();

    //     $product = ProductsModel::with([
    //         'category',
    //         'brand',
    //         'image',
    //         'promotions' => function ($q) use ($today) {
    //             $q->where('status', true)
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('start_date')
    //                         ->orWhereDate('start_date', '<=', $today);
    //                 })
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('end_date')
    //                         ->orWhereDate('end_date', '>=', $today);
    //                 });
    //         }
    //     ])->find($id);

    //     if (!$product) {
    //         return response()->json([
    //             'message' => 'Product not found'
    //         ], 404);
    //     }

    //     $salePrice = (float) $product->sale_price;

    //     $finalPrice = $salePrice;

    //     $selectedPromotion = null;

    //     foreach ($product->promotions as $promotion) {

    //         if ($promotion->discount_type === 'percent') {

    //             $discountAmount =
    //                 $salePrice * $promotion->discount_value / 100;

    //             if (!is_null($promotion->max_discount)) {

    //                 $discountAmount = min(
    //                     $discountAmount,
    //                     $promotion->max_discount
    //                 );
    //             }
    //         } else {

    //             $discountAmount = $promotion->discount_value;
    //         }

    //         $currentFinalPrice = $salePrice - $discountAmount;

    //         // Skip promotion that makes product free
    //         if ($currentFinalPrice <= 0) {
    //             continue;
    //         }

    //         if ($currentFinalPrice < $finalPrice) {

    //             $finalPrice = $currentFinalPrice;

    //             $selectedPromotion = $promotion;
    //         }
    //     }

    //     $discount = null;

    //     if ($selectedPromotion) {

    //         $discount = [
    //             'discount_type'  => $selectedPromotion->discount_type,
    //             'discount_value' => $selectedPromotion->discount_value,
    //         ];
    //     }

    //     return response()->json([
    //         'id' => $product->id,
    //         'name' => $product->name,
    //         'description' => $product->description,

    //         'sale_price'  => number_format($salePrice, 2, '.', ''),
    //         'final_price' => number_format($finalPrice, 2, '.', ''),

    //         'discount' => $discount,

    //         'quantity' => $product->quantity,
    //         'status' => $product->status,

    //         'category_name' => optional($product->category)->name,
    //         'brand_name'    => optional($product->brand)->name,

    //         'images' => $product->image
    //             ->pluck('image_url')
    //             ->values(),
    //     ]);
    // }

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
            ->take(20)
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->get();

        // ✅ Eager load relationships (IMPORTANT)
        $products->load([
            'image',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ]);

        $products = $products->map(function ($product) {

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

                // ✅ formatted prices
                'sale_price' => number_format($product->sale_price, 2, '.', ''),
                'final_price' => number_format($final_price, 2, '.', ''),

                'discount' => $discount,
                'sold' => (int) $product->sold,

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }


    // public function bestSellers()
    // {
    //     $today = Carbon::today();

    //     $products = ProductsModel::leftJoin(
    //         'order_items',
    //         'products.id',
    //         '=',
    //         'order_items.product_id'
    //     )
    //         ->select('products.*')
    //         ->selectRaw('COALESCE(SUM(order_items.qty),0) as sold')
    //         ->groupBy('products.id')
    //         ->orderByDesc('sold')
    //         ->where('products.status', true)
    //         ->where('products.quantity', '>', 0)
    //         ->take(20)
    //         ->get();

    //     $products->load([
    //         'image',
    //         'promotions' => function ($q) use ($today) {
    //             $q->where('status', true)
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('start_date')
    //                         ->orWhereDate('start_date', '<=', $today);
    //                 })
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('end_date')
    //                         ->orWhereDate('end_date', '>=', $today);
    //                 });
    //         }
    //     ]);

    //     $products = $products->map(function ($product) {

    //         $salePrice = (float) $product->sale_price;

    //         $finalPrice = $salePrice;

    //         $selectedPromotion = null;

    //         foreach ($product->promotions as $promotion) {

    //             if ($promotion->discount_type === 'percent') {

    //                 $discountAmount =
    //                     $salePrice * $promotion->discount_value / 100;

    //                 if (!is_null($promotion->max_discount)) {

    //                     $discountAmount = min(
    //                         $discountAmount,
    //                         $promotion->max_discount
    //                     );
    //                 }
    //             } else {

    //                 $discountAmount = $promotion->discount_value;
    //             }

    //             $currentFinalPrice = $salePrice - $discountAmount;

    //             // មិនអនុញ្ញាត Free
    //             if ($currentFinalPrice <= 0) {
    //                 continue;
    //             }

    //             if ($currentFinalPrice < $finalPrice) {

    //                 $finalPrice = $currentFinalPrice;
    //                 $selectedPromotion = $promotion;
    //             }
    //         }

    //         $discount = null;

    //         if ($selectedPromotion) {

    //             $discount = [
    //                 'discount_type' => $selectedPromotion->discount_type,
    //                 'discount_value' => $selectedPromotion->discount_value,
    //             ];
    //         }

    //         return [
    //             'id' => $product->id,
    //             'name' => $product->name,

    //             'sale_price' => number_format($salePrice, 2, '.', ''),
    //             'final_price' => number_format($finalPrice, 2, '.', ''),

    //             'discount' => $discount,

    //             'sold' => (int) $product->sold,

    //             'images' => $product->image
    //                 ->pluck('image_url')
    //                 ->values(),
    //         ];
    //     });

    //     return response()->json($products);
    // }


    public function newArrivals()
    {
        $today = Carbon::today();

        $products = ProductsModel::with([
            'image',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])
            ->orderByDesc('created_at')
            ->take(10)
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->get();

        $products = $products->map(function ($product) {

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

                // ✅ FIXED formatting
                'sale_price' => number_format($product->sale_price, 2, '.', ''),
                'final_price' => number_format($final_price, 2, '.', ''),

                'discount' => $discount,

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }


    // public function newArrivals()
    // {
    //     $today = Carbon::today();

    //     $products = ProductsModel::with([
    //         'image',
    //         'promotions' => function ($q) use ($today) {
    //             $q->where('status', true)
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('start_date')
    //                         ->orWhereDate('start_date', '<=', $today);
    //                 })
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('end_date')
    //                         ->orWhereDate('end_date', '>=', $today);
    //                 });
    //         }
    //     ])
    //         ->where('status', true)
    //         ->where('quantity', '>', 0)
    //         ->orderByDesc('created_at')
    //         ->take(10)
    //         ->get();

    //     $products = $products->map(function ($product) {

    //         $salePrice = (float) $product->sale_price;

    //         $finalPrice = $salePrice;

    //         $selectedPromotion = null;

    //         foreach ($product->promotions as $promotion) {

    //             if ($promotion->discount_type === 'percent') {

    //                 $discountAmount =
    //                     $salePrice * $promotion->discount_value / 100;

    //                 if (!is_null($promotion->max_discount)) {

    //                     $discountAmount = min(
    //                         $discountAmount,
    //                         $promotion->max_discount
    //                     );
    //                 }
    //             } else {

    //                 $discountAmount = $promotion->discount_value;
    //             }

    //             $currentFinalPrice = $salePrice - $discountAmount;

    //             // មិនអនុញ្ញាតឱ្យតម្លៃស្មើ 0 ឬអវិជ្ជមាន
    //             if ($currentFinalPrice <= 0) {
    //                 continue;
    //             }

    //             // ជ្រើស Promotion ដែលបានតម្លៃថោកជាងគេ
    //             if ($currentFinalPrice < $finalPrice) {

    //                 $finalPrice = $currentFinalPrice;
    //                 $selectedPromotion = $promotion;
    //             }
    //         }

    //         $discount = null;

    //         if ($selectedPromotion) {

    //             $discount = [
    //                 'discount_type' => $selectedPromotion->discount_type,
    //                 'discount_value' => $selectedPromotion->discount_value,
    //             ];
    //         }

    //         return [
    //             'id' => $product->id,
    //             'name' => $product->name,

    //             'sale_price' => number_format($salePrice, 2, '.', ''),
    //             'final_price' => number_format($finalPrice, 2, '.', ''),

    //             'discount' => $discount,

    //             'images' => $product->image
    //                 ->pluck('image_url')
    //                 ->values(),
    //         ];
    //     });

    //     return response()->json($products);
    // }

    public function recommended()
    {
        $today = Carbon::today();

        $products = ProductsModel::with([
            'image',
            'category',
            'brand',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])
            ->withSum('orderItems as sold', 'qty')
            ->take(10)
            ->where('status', 1)
            ->orderBy('sale_price', 'asc')
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->get();

        $products = $products->map(function ($product) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = $product->promotions->first();

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {

                    $final_price = $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $discount = number_format($promotion->discount_value, 2) . '%';
                } else {

                    $final_price = $product->sale_price - $promotion->discount_value;

                    $discount = '$' . number_format($promotion->discount_value, 2);
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'unit' => $product->unit,
                'quantity' => $product->quantity,

                // ✅ FIXED formatting
                'sale_price' => number_format($product->sale_price, 2, '.', ''),
                'final_price' => number_format($final_price, 2, '.', ''),

                'discount' => $discount,

                'category_name' => optional($product->category)->name,
                'brand_name' => optional($product->brand)->name,

                'sold' => (int) ($product->sold ?? 0),

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }

    // public function recommended()
    // {
    //     $today = Carbon::today();

    //     $products = ProductsModel::with([
    //         'image',
    //         'category',
    //         'brand',
    //         'promotions' => function ($q) use ($today) {
    //             $q->where('status', true)
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('start_date')
    //                         ->orWhereDate('start_date', '<=', $today);
    //                 })
    //                 ->where(function ($q) use ($today) {
    //                     $q->whereNull('end_date')
    //                         ->orWhereDate('end_date', '>=', $today);
    //                 });
    //         }
    //     ])
    //         ->withSum('orderItems as sold', 'qty')
    //         ->where('status', true)
    //         ->where('quantity', '>', 0)
    //         ->orderBy('sale_price', 'asc')
    //         ->take(10)
    //         ->get();

    //     $products = $products->map(function ($product) {

    //         $salePrice = (float) $product->sale_price;

    //         $finalPrice = $salePrice;

    //         $selectedPromotion = null;

    //         foreach ($product->promotions as $promotion) {

    //             if ($promotion->discount_type === 'percent') {

    //                 $discountAmount =
    //                     $salePrice * $promotion->discount_value / 100;

    //                 if (!is_null($promotion->max_discount)) {

    //                     $discountAmount = min(
    //                         $discountAmount,
    //                         $promotion->max_discount
    //                     );
    //                 }
    //             } else {

    //                 $discountAmount = $promotion->discount_value;
    //             }

    //             $currentFinalPrice = $salePrice - $discountAmount;

    //             // មិនអនុញ្ញាតឱ្យតម្លៃ <= 0
    //             if ($currentFinalPrice <= 0) {
    //                 continue;
    //             }

    //             // ជ្រើស Promotion ដែលធ្វើឱ្យតម្លៃថោកជាងគេ
    //             if ($currentFinalPrice < $finalPrice) {

    //                 $finalPrice = $currentFinalPrice;
    //                 $selectedPromotion = $promotion;
    //             }
    //         }

    //         $discount = null;

    //         if ($selectedPromotion) {

    //             $discount = [
    //                 'discount_type' => $selectedPromotion->discount_type,
    //                 'discount_value' => $selectedPromotion->discount_value,
    //             ];
    //         }

    //         return [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'description' => $product->description,
    //             'unit' => $product->unit,
    //             'quantity' => $product->quantity,

    //             'sale_price' => number_format($salePrice, 2, '.', ''),
    //             'final_price' => number_format($finalPrice, 2, '.', ''),

    //             'discount' => $discount,

    //             'category_name' => optional($product->category)->name,
    //             'brand_name' => optional($product->brand)->name,

    //             'sold' => (int) ($product->sold ?? 0),

    //             'images' => $product->image
    //                 ->pluck('image_url')
    //                 ->values(),
    //         ];
    //     });

    //     return response()->json($products);
    // }
    public function allBestSellers()
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
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->get();

        // ✅ Eager load ALL relationships (important)
        $products->load([
            'image',
            'category',
            'brand',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ]);

        $products = $products->map(function ($product) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = $product->promotions->first();

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {

                    $final_price = $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $discount = number_format($promotion->discount_value, 2) . '%';
                } else {

                    $final_price = $product->sale_price - $promotion->discount_value;

                    $discount = '$' . number_format($promotion->discount_value, 2);
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'unit' => $product->unit,
                'quantity' => $product->quantity,

                // ✅ FIXED formatting
                'sale_price' => number_format($product->sale_price, 2, '.', ''),
                'final_price' => number_format($final_price, 2, '.', ''),

                'discount' => $discount,

                'category_name' => optional($product->category)->name,
                'brand_name' => optional($product->brand)->name,

                'sold' => (int) $product->sold,

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }

    public function allNewArrivals()
    {
        $today = Carbon::today();
        $days = 30;

        $products = ProductsModel::leftJoin(
            'order_items',
            'products.id',
            '=',
            'order_items.product_id'
        )
            ->select('products.*')
            ->selectRaw('COALESCE(SUM(order_items.qty),0) as sold')
            ->where('products.created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('products.id')
            ->orderByDesc('products.created_at')
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->get();

        // ✅ eager load everything (NO N+1)
        $products->load([
            'image',
            'category',
            'brand',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ]);

        $products = $products->map(function ($product) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = $product->promotions->first();

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {

                    $final_price = $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $discount = number_format($promotion->discount_value, 2) . '%';
                } else {

                    $final_price = $product->sale_price - $promotion->discount_value;

                    $discount = '$' . number_format($promotion->discount_value, 2);
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'unit' => $product->unit,
                'quantity' => $product->quantity,

                // ✅ FIXED formatting (IMPORTANT)
                'sale_price' => number_format($product->sale_price, 2, '.', ''),
                'final_price' => number_format($final_price, 2, '.', ''),

                'discount' => $discount,

                'category_name' => optional($product->category)->name,
                'brand_name' => optional($product->brand)->name,

                'sold' => (int) $product->sold,

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }
    public function allrecommended()
    {
        $today = Carbon::today();

        $products = ProductsModel::with([
            'image',
            'category',
            'brand',
            'promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])
            ->withSum('orderItems as sold', 'qty')
            ->where('status', 1)
            ->orderBy('sale_price', 'asc')
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->get();

        $products = $products->map(function ($product) {

            $final_price = $product->sale_price;
            $discount = null;

            $promotion = $product->promotions->first();

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {

                    $final_price = $product->sale_price -
                        ($product->sale_price * $promotion->discount_value / 100);

                    $discount = number_format($promotion->discount_value, 2) . '%';
                } else {

                    $final_price = $product->sale_price - $promotion->discount_value;

                    $discount = '$' . number_format($promotion->discount_value, 2);
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'unit' => $product->unit,
                'quantity' => $product->quantity,

                // ✅ FIXED formatting
                'sale_price' => number_format($product->sale_price, 2, '.', ''),
                'final_price' => number_format($final_price, 2, '.', ''),

                'discount' => $discount,

                'category_name' => optional($product->category)->name,
                'brand_name' => optional($product->brand)->name,

                'sold' => (int) ($product->sold ?? 0),

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];
        });

        return response()->json($products);
    }
}
