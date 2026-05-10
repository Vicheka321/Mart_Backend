<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentModel;

class CustomersController extends Controller
{
    public function customers(Request $request)
    {
        $roleFilter = $request->input('role', 'all'); // 'all', 'customer', 'staff'

        $query = User::query()
            ->when($roleFilter !== 'all', fn($q) => $q->where('role', $roleFilter))
            ->when($roleFilter === 'all', fn($q) => $q->whereIn('role', ['customer', 'staff']))
            ->withCount('orders')
            ->latest();

        $customers = $query->paginate(10)->withQueryString();

        $customers->getCollection()->transform(function ($customer) {
            $customer->total_spent = PaymentModel::where('payment_status', 'paid')
                ->whereHas('order', fn($q) => $q->where('user_id', $customer->id))
                ->sum('amount');
            return $customer;
        });

        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')->whereHas('orders')->count();
        $vipMembers = $customers->getCollection()
            ->filter(fn($c) => $c->total_spent > 1000)
            ->count();

        return view('admin.customers', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'vipMembers',
            'roleFilter'
        ));
    }

    public function changeCustomerRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:customer,staff',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return back()->with('success', 'User role updated successfully.');
    }
}
