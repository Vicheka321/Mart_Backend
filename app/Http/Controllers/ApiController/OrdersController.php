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
            'payment_method' => 'required',
            'address_id' => 'required|exists:addresses,id'
        ]);

        $user_id = Auth::id();

        DB::beginTransaction();

        try {

            $cart = CartModel::where('user_id', $user_id)->first();
            $items = CartItemModel::where('cart_id', $cart->id)->get();

            if ($items->count() == 0) {
                return response()->json(['message' => 'Cart is empty'], 400);
            }

            $total = 0;

            foreach ($items as $item) {
                $product = ProductsModel::findOrFail($item->product_id);

                if ($product->quantity < $item->qty) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                $total += $item->qty * $item->price;
            }

            $order = OrderModel::create([
                'user_id' => $user_id,
                'address_id' => $request->address_id,
                'total_amount' => $total,
                'status' => 'pending'
    
            ]);

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

            PaymentModel::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'amount' => $total
            ]);

            CartItemModel::where('cart_id', $cart->id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myOrders()
    {
        $user_id = Auth::id();
        $orders = OrderModel::with('orderItems.product', 'payment')
            ->where('user_id', $user_id)
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    
    }
}
