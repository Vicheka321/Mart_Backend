<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomersController extends Controller
{
    public function customers(Request $request)
    {
        $roleFilter = $request->input('role', 'all');

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
        $activeCustomers = User::where('role', 'customer')
            ->where(function ($q) {
                $q->where('created_at', '>=', now()->subDays(30))
                    ->orWhereHas('orders');
            })
            ->count();
        $vipMembers = $customers->getCollection()
            ->filter(fn($c) => $c->total_spent > 1000)
            ->count();
        $now = now();

        $totalThisMonth = User::whereIn('role', ['customer', 'staff'])
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $totalLastMonth = User::whereIn('role', ['customer', 'staff'])
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->count();

        $totalGrowth = $totalLastMonth > 0
            ? round((($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100, 1)
            : ($totalThisMonth > 0 ? 100 : 0);
        $thisMonthCustomers = User::where('role', 'customer')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $inactiveCustomers = User::where('role', 'customer')
            ->where('created_at', '<', now()->subDays(30))
            ->whereDoesntHave('orders')
            ->count();
        $activePct = $totalCustomers > 0
            ? round(($activeCustomers / $totalCustomers) * 100)
            : 0;

        $lastMonthCustomers = User::where('role', 'customer')
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->count();

        $thisMonthPct = $totalCustomers > 0
            ? round(($thisMonthCustomers / $totalCustomers) * 100)
            : 0;

        $thisMonthGrowth = $lastMonthCustomers > 0
            ? round((($thisMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100)
            : ($thisMonthCustomers > 0 ? 100 : 0);
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date'  => $date->format('D'),
                'full'  => $date->format('M j'),
                'count' => User::where('role', 'customer')
                    ->whereDate('created_at', $date->toDateString())
                    ->count(),
            ];
        });
        return view('admin.customers', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'roleFilter',
            'totalGrowth',
            'thisMonthCustomers',
            'lastMonthCustomers',
            'thisMonthPct',
            'thisMonthGrowth',
            'activePct',
            'inactiveCustomers',
            'last7Days'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'unique:users,phone'],
            'role'       => ['required', 'in:customer,staff,admin'],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return back()->with('success', 'User created successfully.');
    }

    public function exportCustomersCSV(Request $request)
    {
        $roleFilter = $request->input('role', 'all');

        $query = User::query()
            ->when($roleFilter !== 'all', fn($q) => $q->where('role', $roleFilter))
            ->when($roleFilter === 'all', fn($q) => $q->whereIn('role', ['customer', 'staff']))
            ->withCount('orders')
            ->latest();

        $customers = $query->get();

        // Add total spent
        $customers->transform(function ($customer) {
            $customer->total_spent = PaymentModel::where('payment_status', 'paid')
                ->whereHas('order', fn($q) => $q->where('user_id', $customer->id))
                ->sum('amount');

            return $customer;
        });

        $fileName = 'customers_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Role',
                'Orders Count',
                'Total Spent',
                'VIP',
                'Joined At',
            ]);

            // Data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    ucfirst($customer->role),
                    $customer->orders_count,
                    number_format($customer->total_spent, 2, '.', ''),
                    $customer->total_spent > 1000 ? 'Yes' : 'No',
                    $customer->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCustomersPDF(Request $request)
    {
        $roleFilter = $request->input('role', 'all');

        $query = User::query()
            ->when($roleFilter !== 'all', fn($q) => $q->where('role', $roleFilter))
            ->when($roleFilter === 'all', fn($q) => $q->whereIn('role', ['customer', 'staff']))
            ->withCount('orders')
            ->latest();

        $customers = $query->get();

        // Add total spent
        $customers->transform(function ($customer) {
            $customer->total_spent = PaymentModel::where('payment_status', 'paid')
                ->whereHas('order', fn($q) => $q->where('user_id', $customer->id))
                ->sum('amount');

            return $customer;
        });

        // Summary data
        $totalCustomers = $customers->where('role', 'customer')->count();
        $activeCustomers = $customers->filter(fn($c) => $c->orders_count > 0)->count();
        $vipMembers = $customers->filter(fn($c) => $c->total_spent > 1000)->count();

        $pdf = Pdf::loadView('admin.PDF.customers_pdf', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'vipMembers',
            'roleFilter'
        ))->setPaper('A4', 'landscape');

        return $pdf->download(
            'customers_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    // public function updateCustomer(Request $request, User $user)
    // {
    //     $validated = $request->validate([
    //         'first_name' => ['required', 'string', 'max:100'],
    //         'last_name'  => ['nullable', 'string', 'max:100'],
    //         'email'      => [
    //             'required',
    //             'email',
    //             'max:255',
    //             Rule::unique('users', 'email')->ignore($user->id),
    //         ],
    //         'phone'      => [
    //             'nullable',
    //             'string',
    //             'max:30',
    //             Rule::unique('users', 'phone')->ignore($user->id),
    //         ],
    //         'role'       => ['required', 'in:customer,staff,admin'],
    //         'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
    //     ]);

    //     // Prepare data for update
    //     $data = [
    //         'first_name' => $validated['first_name'],
    //         'last_name'  => $validated['last_name'] ?? null,
    //         'email'      => $validated['email'],
    //         'phone'      => $validated['phone'] ?? null,
    //         'role'       => $validated['role'],
    //     ];

    //     // Update password only if provided
    //     if (!empty($validated['password'])) {
    //         $data['password'] = Hash::make($validated['password']);
    //     }

    //     // Save changes
    //     $user->update($data);

    //     return redirect()
    //         ->back()
    //         ->with('success', 'Customer updated successfully.');
    // }

    public function updateCustomer(Request $request, User $user)
    {
        $passwordRules = ['string', 'min:8', 'confirmed'];

        if (in_array($request->role, ['staff', 'admin']) && empty($user->password)) {
            $passwordRules = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone'      => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'role'       => ['required', 'in:staff,admin'],
            'password'   => $passwordRules,
        ]);

        // Prepare data for update
        $data = [
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'] ?? null,
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'role'       => $validated['role'],
        ];

        // Update password only if provided
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        // Save changes
        $user->update($data);

        return redirect()
            ->back()
            ->with('success', 'Customer updated successfully.');
    }
}
