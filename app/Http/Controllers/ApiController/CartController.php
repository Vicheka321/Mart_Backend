<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductsModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PromotionModel;

class CartController extends Controller
{
    // public function addToCart(Request $request)
    // {
    //     $user_id = Auth::id();


    //     $request->validate([
    //         'product_id' => 'required|integer|exists:products,id',
    //         'quantity'   => 'required|integer|min:1'
    //     ]);


    //     $product = ProductsModel::findOrFail($request->product_id);

    //     $cart = CartModel::firstOrCreate([
    //         'user_id' => $user_id
    //     ]);

    //     $cartItem = CartItemModel::where('cart_id', $cart->id)
    //         ->where('product_id', $product->id)
    //         ->first();


    //     if ($cartItem) {

    //         $cartItem->qty += $request->quantity;
    //         $cartItem->save();
    //     } else {

    //         CartItemModel::create([
    //             'cart_id'   => $cart->id,
    //             'product_id' => $product->id,
    //             'qty'       => $request->quantity,
    //             'price'     => $product->sale_price
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => 'Item added to cart successfully',
    //         'cart_id' => $cart->id
    //     ]);
    // }



    public function addToCart(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $product = ProductsModel::findOrFail($request->product_id);

        $cart = CartModel::firstOrCreate([
            'user_id' => $user_id
        ]);

        $cartItem = CartItemModel::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {

            $newQty = $cartItem->qty + $request->quantity;

            if ($newQty > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->quantity} item(s) available in stock."
                ], 422);
            }

            $cartItem->update([
                'qty' => $newQty
            ]);
        } else {

            if ($request->quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->quantity} item(s) available in stock."
                ], 422);
            }

            CartItemModel::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'qty'        => $request->quantity,
                'price'      => $product->sale_price
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'cart_id' => $cart->id
        ]);
    }
    public function getCart()
    {
        $today = Carbon::today();

        $user_id = Auth::id();
        $cart = CartModel::where('user_id', $user_id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cartItems = CartItemModel::where('cart_id', $cart->id)->get();

        $items = [];
        $cartTotal = 0;

        foreach ($cartItems as $item) {

            $product = ProductsModel::with('image')->find($item->product_id);

            // 🔥 DEFAULT PRICE
            $final_price = $product->sale_price;
            $stock = $product->quantity;
            $discount = null;

            // 🔥 CHECK PROMOTION
            // $promotion = PromotionModel::whereHas('products', function ($q) use ($product) {
            //     $q->where('product_id', $product->id);
            // })
            //     ->where('status', true)
            //     ->whereDate('start_date', '<=', $today)
            //     ->whereDate('end_date', '>=', $today)
            //     ->first();

            // if ($promotion) {

            //     if ($promotion->discount_type === 'percent') {

            //         $final_price =
            //             $product->sale_price -
            //             ($product->sale_price * $promotion->discount_value / 100);

            //         $discount = $promotion->discount_value . '%';
            //     } else {

            //         $final_price =
            //             $product->sale_price - $promotion->discount_value;

            //         $discount = '$' . $promotion->discount_value;
            //     }
            // }

            // 🔥 CHECK PROMOTION
            $promotion = null;

            $promotions = PromotionModel::whereHas('products', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
                ->where('status', true)
                ->where(function ($q) use ($today) {
                    $q->whereNull('start_date')
                        ->orWhereDate('start_date', '<=', $today);
                })
                ->where(function ($q) use ($today) {
                    $q->whereNull('end_date')
                        ->orWhereDate('end_date', '>=', $today);
                })
                ->get();

            $final_price = (float) $product->sale_price;
            $selectedPromotion = null;

            foreach ($promotions as $promotion) {

                if ($promotion->discount_type === 'percent') {

                    $discountAmount =
                        $product->sale_price *
                        $promotion->discount_value / 100;

                    if (!is_null($promotion->max_discount)) {
                        $discountAmount = min(
                            $discountAmount,
                            $promotion->max_discount
                        );
                    }
                } else {

                    $discountAmount =
                        $promotion->discount_value;
                }

                $currentFinalPrice =
                    $product->sale_price - $discountAmount;

                // ❌ មិនអនុញ្ញាតឱ្យ price = 0 ឬ negative
                if ($currentFinalPrice <= 0) {
                    continue;
                }

                // ✅ ជ្រើស promotion ដែល final price ទាបជាងគេ
                if ($currentFinalPrice < $final_price) {

                    $final_price = $currentFinalPrice;

                    $selectedPromotion = $promotion;
                }
            }

            // 🔥 DISCOUNT
            $discount = null;

            if ($selectedPromotion) {

                $discount = [
                    'discount_type' =>
                    $selectedPromotion->discount_type,

                    'discount_value' =>
                    (float) $selectedPromotion->discount_value,
                ];
            }

            // 🔥 TOTAL PER ITEM
            $totalPrice = $item->qty * $final_price;

            $items[] = [
                'product_id' => $item->product_id,
                'name' => $product->name,
                'qty' => $item->qty,
                'stock' => $stock,

                // ✅ IMPORTANT FIX
                // 'price' => $final_price,
                // 'sale_price' => $product->sale_price,
                // 'discount' => $discount,
                // 'total_price' => round($totalPrice, 2),

                'price' => number_format($final_price, 2, '.', ''),
                'sale_price' => number_format($product->sale_price, 2, '.', ''),
                'discount' => $discount,
                'total_price' => number_format($totalPrice, 2, '.', ''),

                'images' => $product->image
                    ->pluck('image_url')
                    ->values(),
            ];

            $cartTotal += $totalPrice;
        }

        return response()->json([
            'cart_id' => $cart->id,
            'total_price' => round($cartTotal, 2),
            'items' => $items
        ]);
    }

    // public function getCart()
    // {
    //     $today = Carbon::today();

    //     $user_id = Auth::id();

    //     $cart = CartModel::where('user_id', $user_id)->first();

    //     if (!$cart) {
    //         return response()->json([
    //             'message' => 'Cart not found'
    //         ], 404);
    //     }

    //     $cartItems = CartItemModel::where('cart_id', $cart->id)->get();

    //     $items = [];
    //     $cartTotal = 0;

    //     foreach ($cartItems as $item) {

    //         $product = ProductsModel::with([
    //             'image',
    //             'promotions' => function ($q) use ($today) {

    //                 $q->where('status', true)

    //                     ->where(function ($q) use ($today) {
    //                         $q->whereNull('start_date')
    //                             ->orWhereDate(
    //                                 'start_date',
    //                                 '<=',
    //                                 $today
    //                             );
    //                     })

    //                     ->where(function ($q) use ($today) {
    //                         $q->whereNull('end_date')
    //                             ->orWhereDate(
    //                                 'end_date',
    //                                 '>=',
    //                                 $today
    //                             );
    //                     });
    //             }
    //         ])->find($item->product_id);

    //         if (!$product) {
    //             continue;
    //         }

    //         // -----------------------------------
    //         // DEFAULT PRICE
    //         // -----------------------------------

    //         $salePrice = (float) $product->sale_price;

    //         $finalPrice = $salePrice;

    //         $stock = $product->quantity;

    //         $selectedPromotion = null;

    //         // -----------------------------------
    //         // CHECK ALL ACTIVE PROMOTIONS
    //         // -----------------------------------

    //         foreach ($product->promotions as $promotion) {

    //             // -------------------------------
    //             // Calculate discount
    //             // -------------------------------

    //             if ($promotion->discount_type === 'percent') {

    //                 $discountAmount =
    //                     $salePrice *
    //                     ((float) $promotion->discount_value / 100);

    //                 // Apply max discount
    //                 if (!is_null($promotion->max_discount)) {

    //                     $discountAmount = min(
    //                         $discountAmount,
    //                         (float) $promotion->max_discount
    //                     );
    //                 }
    //             } else {

    //                 // Fixed discount
    //                 $discountAmount =
    //                     (float) $promotion->discount_value;
    //             }

    //             // -------------------------------
    //             // Calculate final price
    //             // -------------------------------

    //             $currentFinalPrice =
    //                 $salePrice - $discountAmount;

    //             // ❌ Don't allow $0 or negative
    //             if ($currentFinalPrice <= 0) {
    //                 continue;
    //             }

    //             // -------------------------------
    //             // Choose lowest final price
    //             // -------------------------------

    //             if ($currentFinalPrice < $finalPrice) {

    //                 $finalPrice = $currentFinalPrice;

    //                 $selectedPromotion = $promotion;
    //             }
    //         }

    //         // -----------------------------------
    //         // Discount information
    //         // -----------------------------------

    //         $discount = null;

    //         if ($selectedPromotion) {

    //             $discount = [
    //                 'discount_type' =>
    //                 $selectedPromotion->discount_type,

    //                 'discount_value' =>
    //                 (float) $selectedPromotion->discount_value,
    //             ];
    //         }

    //         // -----------------------------------
    //         // Round price to 2 decimals
    //         // -----------------------------------

    //         $finalPrice = round($finalPrice, 2);

    //         $totalPrice =
    //             round(
    //                 $item->qty * $finalPrice,
    //                 2
    //             );

    //         // -----------------------------------
    //         // Cart total
    //         // -----------------------------------

    //         $cartTotal += $totalPrice;

    //         // -----------------------------------
    //         // Response
    //         // -----------------------------------

    //         $items[] = [

    //             'product_id' =>
    //             $item->product_id,

    //             'name' =>
    //             $product->name,

    //             'qty' =>
    //             $item->qty,

    //             'stock' =>
    //             $stock,

    //             // IMPORTANT
    //             // Return 2 decimal places
    //             'price' =>
    //             number_format(
    //                 $finalPrice,
    //                 2,
    //                 '.',
    //                 ''
    //             ),

    //             'sale_price' =>
    //             number_format(
    //                 $salePrice,
    //                 2,
    //                 '.',
    //                 ''
    //             ),

    //             'discount' =>
    //             $discount,

    //             'total_price' =>
    //             number_format(
    //                 $totalPrice,
    //                 2,
    //                 '.',
    //                 ''
    //             ),

    //             'images' =>
    //             $product->image
    //                 ->pluck('image_url')
    //                 ->values(),
    //         ];
    //     }

    //     return response()->json([

    //         'cart_id' =>
    //         $cart->id,

    //         'total_price' =>
    //         number_format(
    //             $cartTotal,
    //             2,
    //             '.',
    //             ''
    //         ),

    //         'items' =>
    //         $items,
    //     ]);
    // }



    public function updateCart(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $cart = CartModel::where('user_id', $user_id)
            ->firstOrFail();

        $cartItem = CartItemModel::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->firstOrFail();

        $product = ProductsModel::findOrFail($request->product_id);

        // Check stock
        if ($request->quantity > $product->quantity) {

            return response()->json([
                'success' => false,
                'message' => "Only {$product->quantity} item(s) available in stock.",
                'available_stock' => $product->quantity,
            ], 422);
        }

        $cartItem->update([
            'qty' => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'item' => $cartItem
        ]);
    }

    public function deleteCart($product_id)
    {
        $user_id = Auth::id();

        $cart = CartModel::where('user_id', $user_id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cartItem = CartItemModel::where('cart_id', $cart->id)
            ->where('product_id', $product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Cart item deleted successfully'
        ]);
    }
}
