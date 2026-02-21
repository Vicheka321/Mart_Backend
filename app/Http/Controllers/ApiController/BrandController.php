<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandModel;
use App\Models\ProductsModel;
class BrandController extends Controller
{
    public function index()
    {
        return BrandModel::all();
    }
    public function getProductById($id)
    {
        $brand = BrandModel::find($id);
        $products = ProductsModel::where('brand_id', $brand->id)->get();
        $brand -> products = $products;
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        return response()->json($brand);
    }
}
