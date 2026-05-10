<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\Order_itemModel;
use App\Models\ProductsModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\Auth;
use App\Services\TelegramService;
use App\Events\NewOrderCreated;

class OrdersController extends Controller
{
    public function checkout()
    {
        $user_id = Auth::id();
        $cart = CartModel::where('user_id', $user_id)->first();
        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }
        $items = CartItemModel::with('product')
            ->where('cart_id', $cart->id)
            ->get();
        if ($items->count() == 0) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }
        $total = 0;
        foreach ($items as $item) {
            if ($item->product->quantity < $item->qty) {
                return response()->json([
                    'message' => 'Not enough stock for ' . $item->product->name
                ], 400);
            }
            $total += $item->qty * $item->price;
        }
        return response()->json([
            'items' => $items,
            'total amount' => $total
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,khqr,aba',
            'address_id' => 'required|exists:address,id'
        ]);

        $user_id = Auth::id();

        DB::beginTransaction();

        try {
            $cart = CartModel::where('user_id', $user_id)->first();
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ], 404);
            }
            $items = CartItemModel::where('cart_id', $cart->id)
                ->get();
            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }
            $total = 0;

            foreach ($items as $item) {

                $product = ProductsModel::lockForUpdate()
                    ->findOrFail($item->product_id);
                if ($product->quantity < $item->qty) {
                    throw new \Exception(
                        "Not enough stock for {$product->name}"
                    );
                }

                $total += $item->qty * $item->price;
            }

            $order = OrderModel::create([
                'user_id' => $user_id,
                'address_id' => $request->address_id,
                'payment_method' => $request->payment_method,
                'total_amount' => $total,
                'status' => 'pending'
            ]);
            broadcast(new NewOrderCreated($order));
            

            foreach ($items as $item) {

                Order_itemModel::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price' => $item->price
                ]);
                ProductsModel::where('id', $item->product_id)
                    ->decrement('quantity', $item->qty);
            }
            $payment = PaymentModel::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'amount' => $total
            ]);

            CartItemModel::where('cart_id', $cart->id)
                ->delete();
            DB::commit();

            if ($request->payment_method == 'cash') {

            
                app(TelegramService::class)->send(
                    "🚀 *NEW CASH ORDER*\n" .
                        "━━━━━━━━━━━━━━━\n" .
                        "🆔 Order: #{$order->id}\n" .
                        "💰 Total: $" . number_format($total, 2) . "\n" .
                        "💳 Payment: CASH\n" .
                        "━━━━━━━━━━━━━━━",
                    $order
                );
            }

            /// ✅ RESPONSE
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',

                'data' => [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,

                    'payment_method' => $payment->payment_method,
                    'payment_status' => $payment->payment_status,

                    'amount' => number_format($total, 2, '.', ''),

                    'status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        try {

            /// ✅ GET ORDER
            $order = OrderModel::with('payment', 'address', 'user')
                ->findOrFail($validated['order_id']);

            $payment = $order->payment;

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            /// ✅ ALREADY PAID
            if ($payment->payment_status === 'paid') {

                return response()->json([
                    'success' => true,
                    'message' => 'Already paid'
                ]);
            }

            /**
             * 🔥 CHECK ABA PAYMENT HERE
             * Replace with your real Bakong check API
             */

            $paid = true;

            if (!$paid) {

                return response()->json([
                    'success' => false,
                    'message' => 'Payment pending'
                ]);
            }

            DB::beginTransaction();

            /// ✅ UPDATE PAYMENT
            $payment->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);


            DB::commit();

            /// ✅ SEND TELEGRAM
            app(TelegramService::class)->send(
                "🚀 *NEW PAID ORDER*\n" .
                    "━━━━━━━━━━━━━━━\n" .
                    "🆔 Order: #{$order->id}\n" .
                    "👤 Customer: {$order->user->name}\n" .
                    "📞 Phone: {$order->address->phone}\n" .
                    "📍 Address: {$order->address->address}\n" .
                    "💰 Total: $" . number_format($order->total_amount, 2) . "\n" .
                    "💳 Payment: {$payment->payment_method}\n" .
                    "━━━━━━━━━━━━━━━",
                $order
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'data' => [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'payment_status' => $payment->payment_status
                ]
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function placeOrder(Request $request)
    // {
    //     $request->validate([
    //         'payment_method' => 'required',
    //         'address_id' => 'required|exists:address,id'
    //     ]);

    //     $user_id = Auth::id();

    //     DB::beginTransaction();

    //     try {

    //         $cart = CartModel::where('user_id', $user_id)->first();
    //         $items = CartItemModel::where('cart_id', $cart->id)->get();

    //         if ($items->count() == 0) {
    //             return response()->json(['message' => 'Cart is empty'], 400);
    //         }

    //         $total = 0;

    //         foreach ($items as $item) {
    //             $product = ProductsModel::findOrFail($item->product_id);

    //             if ($product->quantity < $item->qty) {
    //                 throw new \Exception("Not enough stock for {$product->name}");
    //             }

    //             $total += $item->qty * $item->price;
    //         }

    //         $order = OrderModel::create([
    //             'user_id' => $user_id,
    //             'address_id' => $request->address_id,
    //             'payment_method' => $request->payment_method,
    //             'total_amount' => $total,
    //             'status' => 'pending'

    //         ]);

    //         foreach ($items as $item) {

    //             Order_itemModel::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $item->product_id,

    //                 'qty' => $item->qty,
    //                 'price' => $item->price
    //             ]);

    //             ProductsModel::where('id', $item->product_id)
    //                 ->decrement('quantity', $item->qty);
    //         }

    //         PaymentModel::create([
    //             'order_id' => $order->id,
    //             'payment_method' => $request->payment_method,
    //             'payment_status' => 'pending',
    //             'amount' => $total
    //         ]);

    //         CartItemModel::where('cart_id', $cart->id)->delete();

    //         DB::commit();
    //         event(new NewOrderCreated($order));

    //         $telegram = new TelegramService();
    //         $hasActive = OrderModel::where('status', 'pending')
    //             ->whereNotNull('telegram_message_id')
    //             ->exists();

    //         if (!$hasActive) {
    //             $next = OrderModel::where('status', 'pending')
    //                 ->whereNull('telegram_message_id')
    //                 ->orderBy('created_at', 'asc')
    //                 ->first();

    //             if ($next) {

    //                 app(TelegramService::class)->send(
    //                     "🚀 *NEW ORDER RECEIVED*\n" .
    //                         "━━━━━━━━━━━━━━━\n" .
    //                         "🆔 *Order:* #{$next->id}\n" .
    //                         "👤 *Customer:* {$next->user->name}\n" .
    //                         "📞 *Phone:* {$next->address->phone}\n" .
    //                         "📍 *Address:* {$next->address->address}\n" .
    //                         "📍 *Location:* https://www.google.com/maps?q={$next->address->lat},{$next->address->lng}\n" .
    //                         "━━━━━━━━━━━━━━━\n" .
    //                         "💰 *Total:* $" . number_format($next->total_amount, 2) . "\n" .
    //                         "💳 *Payment:* {$next->payment->payment_method}\n" .
    //                         "📦 *Status:* {$next->status}\n" .
    //                         "━━━━━━━━━━━━━━━",
    //                     $next
    //                 );
    //             }
    //         }

    //         return response()->json([
    //             'message' => 'Order placed successfully',
    //             'order_id' => $order->id
    //         ]);
    //     } catch (\Exception $e) {

    //         DB::rollback();

    //         return response()->json([
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }



    // public function placeOrder(Request $request)
    // {
    //     $request->validate([
    //         'payment_method' => 'required',
    //         'address_id' => 'required|exists:address,id'
    //     ]);

    //     $user_id = Auth::id();

    //     DB::beginTransaction();

    //     try {

    //         $cart = CartModel::where('user_id', $user_id)->first();

    //         if (!$cart) {
    //             return response()->json(['message' => 'Cart not found'], 404);
    //         }

    //         $items = CartItemModel::where('cart_id', $cart->id)->get();

    //         if ($items->isEmpty()) {
    //             return response()->json(['message' => 'Cart is empty'], 400);
    //         }

    //         // ✅ Load all products at once (NO N+1)
    //         $products = ProductsModel::whereIn('id', $items->pluck('product_id'))
    //             ->lockForUpdate() // 🔥 prevent race condition
    //             ->get()
    //             ->keyBy('id');

    //         $total = 0;

    //         foreach ($items as $item) {
    //             $product = $products[$item->product_id];

    //             if ($product->quantity < $item->qty) {
    //                 throw new \Exception("Not enough stock for {$product->name}");
    //             }

    //             $total += $item->qty * $item->price;
    //         }

    //         $order = OrderModel::create([
    //             'user_id' => $user_id,
    //             'address_id' => $request->address_id,
    //             'total_amount' => $total,
    //             'status' => 'pending'
    //         ]);

    //         $orderItems = [];

    //         foreach ($items as $item) {

    //             $orderItems[] = [
    //                 'order_id' => $order->id,
    //                 'product_id' => $item->product_id,
    //                 'qty' => $item->qty,
    //                 'price' => $item->price,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];

    //             $products[$item->product_id]->decrement('quantity', $item->qty);
    //         }

    //         // ✅ bulk insert (faster)
    //         Order_itemModel::insert($orderItems);

    //         PaymentModel::create([
    //             'order_id' => $order->id,
    //             'payment_method' => $request->payment_method,
    //             'payment_status' => 'paid',
    //             'amount' => $total
    //         ]);

    //         CartItemModel::where('cart_id', $cart->id)->delete();

    //         DB::commit();

    //         // ✅ move telegram to background (FAST response)
    //         dispatch(function () {
    //             app(TelegramService::class)->sendNextPending();
    //         });

    //         return response()->json([
    //             'message' => 'Order placed successfully',
    //             'order_id' => $order->id
    //         ]);
    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function myOrders()
    // {
    //     $user_id = Auth::id();
    //     $orders = OrderModel::with('orderItems.product.image', 'payment', 'address')
    //         ->where('user_id', $user_id)
    //         ->get();

    //     return response()->json([
    //         'orders' => $orders
    //     ]);
    // }

    public function myOrders()
    {
        $user_id = Auth::id();
        $today = now();

        $orders = OrderModel::with([
            'payment:id,order_id,payment_method,payment_status',
            'address:id,phone,address',
            'orderItems:id,order_id,product_id,qty,price',

            'orderItems.product:id,name,sale_price',
            'orderItems.product.firstImage:id,product_id,image_url',

            'orderItems.product.promotions' => function ($q) use ($today) {
                $q->where('status', true)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
        ])
            ->where('user_id', $user_id)
            ->latest()
            ->get();

        $orders = $orders->map(function ($order) {

            return [
                'id' => $order->id,
                'status' => $order->status,
                'total' => number_format($order->total_amount, 2, '.', ''),
                'payment_method' => $order->payment->payment_method ?? '',
                'payment_status' => $order->payment->payment_status ?? '',
                'phone' => $order->address->phone ?? '',
                'address' => $order->address->address ?? '',
                'created_at' => $order->created_at->format('Y-m-d H:i'),

                'items' => $order->orderItems->map(function ($item) {

                    $product = $item->product;

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
                        'name' => $product->name,
                        'qty' => $item->qty,

                        'price' => number_format($item->price, 2, '.', ''),
                        'final_price' => number_format($final_price, 2, '.', ''),
                        'discount' => $discount,

                        'image' => optional($product->firstImage)->image_url,
                    ];
                })->values()
            ];
        });

        return response()->json([
            'orders' => $orders
        ]);
    }
}
