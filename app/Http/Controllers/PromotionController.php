<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\PromotionModel;
use App\Models\ProductsModel;

class PromotionController extends Controller
{
    // public function index()
    // {
    //     $promotions = PromotionModel::withCount('products')
    //         ->latest()
    //         ->paginate(10);

    //     return view('admin.promotions', compact('promotions'));
    // }

    public function index()
    {
        $promotions = PromotionModel::withCount('products')
            ->with('products:id')   // ← add this
            ->latest()
            ->paginate(10);

        $categories  = Category::orderBy('name')->get();
        $brands      = BrandModel::orderBy('name')->get();
        $products    = ProductsModel::with(['category:id,name', 'brand:id,name', 'image'])
            ->orderBy('name')->get();

        return view('Admin.promotions', [
            'promotions'  => $promotions,
            'categories'  => $categories,
            'brands'      => $brands,
            'allProducts' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:promotions,name',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);

        $promotion = PromotionModel::create([
            'name' => $request->name,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        // Redirect directly to add products page
        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion created successfully. Now select products.');
    }

    public function update(Request $request, PromotionModel $promotion)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'discount_type'   => 'required|in:percent,fixed',
            'discount_value'  => 'required|numeric|min:0',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'status'          => 'nullable|boolean',
        ]);

        // Keep current image by default
        $imageUrl = $promotion->image_url;

        // Replace image if a new one is uploaded
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($promotion->image_url) {
                $oldPath = str_replace('/storage/', '', $promotion->image_url);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }

            // Upload new image
            $path = $request->file('image')->store('promotions', 'public');
            $imageUrl = \Illuminate\Support\Facades\Storage::url($path);
        }

        // Update promotion
        $promotion->update([
            'name'            => $validated['name'],
            'image_url'       => $imageUrl,
            'discount_type'   => $validated['discount_type'],
            'discount_value'  => $validated['discount_value'],
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'],
            'status'          => $request->boolean('status'),
        ]);

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }


    public function destroy(PromotionModel $promotion)
    {

        // Delete the promotion record
        $promotion->delete();

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
    // public function manageProducts(PromotionModel $promotion)
    // {
    //     $products = ProductsModel::with([
    //         'category:id,name',
    //         'brand:id,name',
    //         'image'
    //     ])
    //         ->orderBy('name')
    //         ->paginate(20);

    //     $selectedProducts = $promotion->products()
    //         ->pluck('products.id')
    //         ->toArray();

    //     return view('admin.promotion_products', compact(
    //         'promotion',
    //         'products',
    //         'selectedProducts'
    //     ));
    // }

    public function attachProducts(Request $request, PromotionModel $promotion)
    {
        // Validate selected products
        $validated = $request->validate([
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        // If no products selected, use an empty array
        $productIds = $validated['product_ids'] ?? [];

        /*
    |--------------------------------------------------------------------------
    | Sync Products to Promotion
    |--------------------------------------------------------------------------
    | This will:
    | - Attach newly selected products
    | - Remove unselected products
    | - Keep existing selected products
    */
        $promotion->products()->sync($productIds);

        // Redirect back with success message
        return redirect()
            ->route('promotions.index')
            ->with('success', 'Products assigned to promotion successfully.');
    }
}
