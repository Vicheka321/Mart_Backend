<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\PromotionModel;
use App\Models\ProductsModel;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = PromotionModel::withCount('products')
            ->with('products:id')
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:promotions,name',
                'discount_type' => 'required|in:percent,fixed',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|boolean',
            ],
            [
                'name.unique' => 'Promotion name already exists.',
            ]
        );
        $normalizedName = preg_replace(
            '/\s+/',
            '',
            strtolower(trim($request->name))
        );

        $exists = PromotionModel::get()->first(function ($promotion) use ($normalizedName) {

            return preg_replace(
                '/\s+/',
                '',
                strtolower(trim($promotion->name))
            ) === $normalizedName;
        });

        if ($exists) {

            $validator->errors()->add(
                'name',
                'Promotion name already exists.'
            );
        }
        if ($validator->fails()) {

            if ($request->ajax() || $request->wantsJson()) {

                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $promotion = PromotionModel::create([
            'name'            => $validated['name'],
            'discount_type'   => $validated['discount_type'],
            'discount_value'  => $validated['discount_value'],
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'],
            'status'          => $validated['status'],
        ]);

        if ($request->ajax() || $request->wantsJson()) {

            return response()->json([
                'success' => true,
                'message' => 'Promotion created successfully.',
            ]);
        }

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion created successfully. Now select products.');
    }

    public function update(Request $request, PromotionModel $promotion)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'discount_type'   => 'required|in:percent,fixed',
            'discount_value'  => 'required|numeric|min:0',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'status'          => 'nullable|boolean',
        ]);
        $normalizedName = preg_replace(
            '/\s+/',
            '',
            strtolower(trim($request->name))
        );

        $exists = PromotionModel::where('id', '!=', $promotion->id)
            ->get()
            ->first(function ($item) use ($normalizedName) {

                return preg_replace(
                    '/\s+/',
                    '',
                    strtolower(trim($item->name))
                ) === $normalizedName;
            });

        if ($exists) {

            $validator->errors()->add(
                'name',
                'Promotion name already exists.'
            );
        }
        if ($validator->fails()) {

            if ($request->ajax() || $request->wantsJson()) {

                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

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

        if ($request->ajax() || $request->wantsJson()) {

            return response()->json([
                'success' => true,
                'message' => 'Promotion updated successfully.',
            ]);
        }

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


    public function attachProducts(Request $request, PromotionModel $promotion)
    {
        // Validate selected products
        $validated = $request->validate([
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        // If no products selected, use an empty array
        $productIds = $validated['product_ids'] ?? [];


        $promotion->products()->sync($productIds);

        // Redirect back with success message
        return redirect()
            ->route('promotions.index')
            ->with('success', 'Products assigned to promotion successfully.');
    }
}
