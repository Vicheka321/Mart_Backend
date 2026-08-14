<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index($productId)
    {
        $reviews = ProductReview::with('user:id,full_name,avatar')
            ->where('product_id', $productId)
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
        ]);
    }


    public function store(Request $request, $productId)
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
                'nullable',
                'exists:orders,id',
            ],
        ]);

        $user = $request->user();

        $review = ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'order_id' => $validated['order_id'] ?? null,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'review' => $review,
        ], 201);
    }
}
