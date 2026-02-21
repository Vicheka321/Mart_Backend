<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\categoriesModel;
use App\Models\ProductsModel;

class CategoriesController extends Controller
{
    public function index()
    {
        return categoriesModel::all();
    }
    
    public function getProductsByCategory($id)
    {
        $category = categoriesModel::find($id);
    
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    
        $products = ProductsModel::with(['image'])
            ->where('categories_id', $id)
            ->get();
    
        $products = $products->map(function ($product) {
            $product->image_url = optional($product->image->first())->image_url;
            unset($product->image);
            return $product;
        });
    
        $category->products = $products;
    
        return response()->json($category);
    }
    

}
