<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\ProductsModel;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request, ProductsModel $product)
    {
        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'review' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],
        ]);

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Create Review
        |--------------------------------------------------------------------------
        */

        $review = ProductReview::create([
            'user_id'     => $user->id,
            'product_id'  => $product->id,
            'order_id'    => $validated['order_id'],
            'rating'      => $validated['rating'],
            'review'      => $validated['review'] ?? null,
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'review'  => $review,
        ], 201);
    }
}