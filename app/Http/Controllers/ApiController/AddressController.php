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
            'full_name' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $address = AddressModel::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return response()->json([
            'address_id' => $address->id
        ]);
    }


    public function myAddress()
    {
        $addresses = AddressModel::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return response()->json([
            'data' => $addresses
        ]);
    }

    public function updateAddress(
        Request $request,
        $id
    ) {
        $request->validate([
            'address' => 'required',
            'lat'     => 'required',
            'lng'     => 'required',
        ]);

        $address = AddressModel::where(
            'user_id',
            Auth::id()
        )->find($id);

        if (!$address) {
            return response()->json([
                'message' => 'Address not found'
            ], 404);
        }

        $address->update([
            'address' => $request->address,
            'lat'     => $request->lat,
            'lng'     => $request->lng,
        ]);

        return response()->json([
            'message' => 'Address updated successfully',
            'data' => $address
        ]);
    }

    public function deleteAddress($id)
    {
        $address = AddressModel::where(
            'user_id',
            Auth::id()
        )->find($id);

        if (!$address) {
            return response()->json([
                'message' => 'Address not found'
            ], 404);
        }

        $address->delete();

        return response()->json([
            'message' => 'Address deleted successfully'
        ]);
    }

    public function setDefaultAddress($id)
    {
        $address = AddressModel::where(
            'user_id',
            Auth::id()
        )->find($id);

        if (!$address) {
            return response()->json([
                'message' => 'Address not found'
            ], 404);
        }

        AddressModel::where(
            'user_id',
            Auth::id()
        )->update([
            'is_default' => false
        ]);

        $address->update([
            'is_default' => true
        ]);

        return response()->json([
            'message' => 'Default address updated'
        ]);
    }
}
