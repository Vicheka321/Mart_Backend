<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteModel;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // public function myFavorites()
    // {
    //     $user_id = Auth::id();
    //     $favorites = FavoriteModel::where('user_id', $user_id)
    //         ->with('product')
    //         ->get();

    //     return response()->json($favorites);
    // }

    public function myFavorites()
    {
        $user_id = Auth::id();

        $favorites = FavoriteModel::where('user_id', $user_id)
            ->with([
                'product.firstImage',
                'product.promotions' => function ($q) {
                    $q->whereDate('start_date', '<=', now())
                        ->whereDate('end_date', '>=', now());
                }
            ])
            ->get();

        $favorites->each(function ($favorite) {

            if (!$favorite->product) {
                return;
            }

            $product = $favorite->product;

            $promotion = $product->promotions->first();

            // Default values
            $product->discount_value = 0;
            $product->discount_type = null;
            $product->final_price = $product->sale_price;

            if ($promotion) {

                $product->discount_value = $promotion->discount_value;
                $product->discount_type  = $promotion->discount_type;

                if ($promotion->discount_type == 'percentage') {

                    $product->final_price =
                        round(
                            $product->sale_price -
                                ($product->sale_price * $promotion->discount_value / 100),
                            2
                        );
                } else {

                    $product->final_price =
                        max(
                            0,
                            $product->sale_price - $promotion->discount_value
                        );
                }
            }

            // Hide promotions relationship
            unset($product->promotions);
        });

        return response()->json($favorites);
    }

    public function addFavorite(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $favorite = FavoriteModel::create([
            'user_id' => $user_id,
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'message' => 'Product added to favorites',
            'favorite' => $favorite
        ]);
    }


    public function checkFavorite($productId)
    {
        $exists = FavoriteModel::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'is_favorite' => $exists
        ]);
    }
}
