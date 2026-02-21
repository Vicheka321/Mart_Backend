<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductsModel;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
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

            $cartItem->qty += $request->quantity;
            $cartItem->save();
        } else {

            CartItemModel::create([
                'cart_id'   => $cart->id,
                'product_id' => $product->id,
                'qty'       => $request->quantity,
                'price'     => $product->sale_price
            ]);
        }

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_id' => $cart->id
        ]);
    }

    public function getCard()
    {
        $user_id = Auth::id();
        $cart = CartModel::where('user_id', $user_id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cartItems = CartItemModel::where('cart_id', $cart->id)->get();

        $items = [];
        foreach ($cartItems as $item) {
            $product = ProductsModel::find($item->product_id);
            $items[] = [
                'product_id' => $item->product_id,
                'name' => $product->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'total_price' => $item->qty * $item->price
            ];
        }

        return response()->json([
            'cart_id' => $cart->id,
            'items' => $items
        ]);
    }

    public function updateCart(Request $request)
    {
        $user_id = Auth::id();
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);
        $cart = CartModel::where('user_id', $user_id)->firstOrFail();
        $cartItem = CartItemModel::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->firstOrFail();

        $cartItem->update([
            'qty' => $request->quantity
        ]);
        return response()->json([
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
