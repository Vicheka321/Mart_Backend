<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class CustomersController extends Controller
{
    public function customers()
{
    $customers = User::withCount('orders')
        ->latest()
        ->paginate(10);

    return view('admin.customers', compact('customers'));
}
}
