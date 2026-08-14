<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{


    public function index(Request $request)
    {
        $query = ProductReview::with([
            'user:id,full_name,avatar',
            'product:id,name',
            'product.firstImage:id,product_id,image_url',
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('review', 'ilike', "%{$search}%")

                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where(
                            'full_name',
                            'ilike',
                            "%{$search}%"
                        );
                    })

                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where(
                            'name',
                            'ilike',
                            "%{$search}%"
                        );
                    });
            });
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where(
                'rating',
                $request->rating
            );
        }

        // Latest first
        $reviews = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('Admin.reviews', compact('reviews'));
    }




}
