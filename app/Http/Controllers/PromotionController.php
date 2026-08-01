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
        // Remove extra spaces
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim($request->name))
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {

                        $normalized = strtolower(
                            preg_replace('/\s+/', '', trim($value))
                        );

                        $exists = PromotionModel::get()->contains(function ($promotion) use ($normalized) {

                            return strtolower(
                                preg_replace('/\s+/', '', trim($promotion->name))
                            ) === $normalized;
                        });

                        if ($exists) {
                            $fail('Promotion name already exists.');
                        }
                    }
                ],

                'discount_type'  => 'required|in:percent,fixed',
                'discount_value' => 'required|numeric|min:0',
                'start_date'     => 'required|date',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'status'         => 'required|boolean',
            ]
        );

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

        PromotionModel::create([
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
            ->with('success', 'Promotion created successfully.');
    }

    public function update(Request $request, PromotionModel $promotion)
    {
        // Remove extra spaces
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim($request->name))
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($promotion) {

                        $normalized = strtolower(
                            preg_replace('/\s+/', '', trim($value))
                        );

                        $exists = PromotionModel::where('id', '!=', $promotion->id)
                            ->get()
                            ->contains(function ($item) use ($normalized) {

                                return strtolower(
                                    preg_replace('/\s+/', '', trim($item->name))
                                ) === $normalized;
                            });

                        if ($exists) {
                            $fail('Promotion name already exists.');
                        }
                    }
                ],

                'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'discount_type'   => 'required|in:percent,fixed',
                'discount_value'  => 'required|numeric|min:0',
                'start_date'      => 'required|date',
                'end_date'        => 'required|date|after_or_equal:start_date',
                'status'          => 'nullable|boolean',
            ]
        );

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

        // Keep current image
        $imageUrl = $promotion->image_url;

        // Upload new image
        if ($request->hasFile('image')) {

            if ($promotion->image_url) {
                $oldPath = str_replace('/storage/', '', $promotion->image_url);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('promotions', 'public');
            $imageUrl = \Illuminate\Support\Facades\Storage::url($path);
        }

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
