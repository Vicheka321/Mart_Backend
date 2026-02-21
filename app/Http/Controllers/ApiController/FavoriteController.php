<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteModel;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function myFavorites()
    {
        $user_id = Auth::id();
        $favorites = FavoriteModel::where('user_id', $user_id)
            ->with('product')
            ->get();

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

    public function removeFavorite($productId)
    {
        $user_id = Auth::id();

        $favorite = FavoriteModel::where('user_id', $user_id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'message' => 'Product removed from favorites'
            ]);
        } else {
            return response()->json([
                'message' => 'Product not found in favorites'
            ], 404);
        }
    }
}
