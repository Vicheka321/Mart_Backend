<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AddressModel;

class AddressController extends Controller
{
    public function storeAddress(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'phone' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $address = AddressModel::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'phone'=> $request->phone,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return response()->json([
            'address_id' => $address->id
        ]);
    }
}
