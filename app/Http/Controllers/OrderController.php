<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $status = request('status');

        $orders = OrderModel::with([
            'orderItems.product.category',
            'orderItems.product.brand',
            'orderItems.product.image',
            'user',
            'address',
            'payment'
        ])
            // Show only orders where payment status = paid
            ->whereHas('payment', function ($q) {
                $q->where('payment_status', 'paid');
            })

            // Optional order status filter
            ->when($status && $status != 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })

            // Custom order status sorting
            ->orderByRaw("
            CASE 
                WHEN status = 'pending' THEN 1
                WHEN status = 'processing' THEN 2
                WHEN status = 'completed' THEN 3
                WHEN status = 'cancelled' THEN 4
            END
        ")
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'first_name' => $order->user->first_name ?? 'Customer',
                'last_name'  => $order->user->last_name ?? '',
                'avatar'     => $order->user->avatar ?? null,
                'phone' => $order->address->phone ?? '',
                'address' => $order->address->address ?? '',
                'total' => $order->total_amount,
                'payment_method' => $order->payment->payment_method ?? '',
                'payment_status' => $order->payment->payment_status ?? '',
                'status' => $order->status,
                'created_at' => $order->created_at->format('Y-m-d H:i'),

                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'name' => $item->product->name ?? '',
                        'qty' => $item->qty,
                        'price' => $item->price,
                        'image' => $item->product->image->first()->image_url ?? null,
                        'category' => $item->product->category->name ?? '',
                        'brand' => $item->product->brand->name ?? '',
                    ];
                })->values()->toArray()
            ];
        });

        $totalOrders = OrderModel::whereHas('payment', function ($q) {
            $q->where('payment_status', 'paid');
        })->count();

        $pendingOrders = OrderModel::where('status', 'pending')
            ->whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->count();

        $processingOrders = OrderModel::where('status', 'processing')
            ->whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->count();

        $completedOrders = OrderModel::where('status', 'completed')
            ->whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->count();

        $cancelledOrders = OrderModel::where('status', 'cancelled')
            ->whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->count();

        return view('admin.orders', compact('orders', 'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders', 'cancelledOrders'));
    }
    public function latest()
    {
        $order = OrderModel::with(['user', 'address', 'payment'])
            ->orderBy('updated_at', 'desc') // 🔥 important
            ->first();

        if (!$order) return response()->json(null);

        return response()->json([
            'id' => $order->id,
            'user_name' => $order->user->name ?? '',
            'phone' => $order->address->phone ?? '',
            'address' => $order->address->address ?? '',
            'total' => $order->total_amount,
            'payment_method' => $order->payment->payment_method ?? '',
            'status' => $order->status,
            'created_at' => $order->created_at->format('Y-m-d H:i'),
            'updated_at' => $order->updated_at->timestamp // 🔥 key
        ]);
    }

    public function notifications()
    {


        $orders = OrderModel::select('orders.*')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('orders.status', 'pending')
            ->where('payments.payment_status', 'paid')
            ->with('payment')
            ->orderBy('payments.updated_at', 'desc')
            ->take(100)
            ->get();


        return response()->json($orders->map(function ($o) {
            return [
                'id' => $o->id,
                'total' => $o->total_amount,
                'time' => $o->created_at->diffForHumans()
            ];
        }));
    }

    public function changeStatus(Request $request, $id)
    {
        $order = OrderModel::findOrFail($id);

        $valid = ['pending', 'processing', 'completed', 'cancelled'];

        if (!in_array($request->status, $valid)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        if ($order->status == 'completed') {
            return response()->json(['error' => 'Already completed'], 400);
        }

        // ✅ UPDATE STATUS
        $order->update([
            'status' => $request->status
        ]);

        // 🔥 UPDATE TELEGRAM MESSAGE
        app(\App\Services\TelegramService::class)->edit($order);

        // ===============================
        // 🚀 SEND NEXT ORDER (FIFO)
        // ===============================

        if (in_array($request->status, ['processing', 'cancelled'])) {

            $next = OrderModel::where('status', 'pending')
                ->whereNull('telegram_message_id')
                ->orderBy('created_at', 'asc')
                ->first();

            if ($next) {

                app(\App\Services\TelegramService::class)->send(
                    "🚀 *NEW ORDER RECEIVED*\n" .
                        "━━━━━━━━━━━━━━━\n" .
                        "🆔 *Order:* #{$next->id}\n" .
                        "👤 *Customer:* {$next->user->name}\n" .
                        "📞 *Phone:* {$next->address->phone}\n" .
                        "📍 *Address:* {$next->address->address}\n" .
                        "📍 *Location:* https://www.google.com/maps?q={$next->address->lat},{$next->address->lng}\n" .
                        "━━━━━━━━━━━━━━━\n" .
                        "💰 *Total:* $" . number_format($next->total_amount, 2) . "\n" .
                        "💳 *Payment:* {$next->payment->payment_method}\n" .
                        "📦 *Status:* {$next->status}\n" .
                        "━━━━━━━━━━━━━━━",
                    $next
                );
            }
        }

        return response()->json([
            'message' => 'Updated successfully'
        ]);
    }

    public function cancel($id)
    {
        $order = OrderModel::findOrFail($id);


        if ($order->status != 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel this order');
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Order cancelled successfully');
    }


    public function show($id)
    {
        $order = OrderModel::with([
            'orderItems.product.category',
            'orderItems.product.brand',
            'orderItems.product.image',
            'user',
            'address',
            'payment'
        ])->findOrFail($id);

        return response()->json([
            'id' => $order->id,
            'user_name' => $order->user->name ?? '',
            'phone' => $order->address->phone ?? '',
            'address' => $order->address->address ?? '',
            'total' => $order->total_amount,
            'payment_method' => $order->payment->payment_method ?? '',
            'status' => $order->status,
            'created_at' => $order->created_at->format('Y-m-d H:i'),

            'items' => $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->product->name ?? '',
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'image' => $item->product->image->first()->image_url ?? null,
                    'category' => $item->product->category->name ?? '',
                    'brand' => $item->product->brand->name ?? '',
                ];
            })->values()->toArray()
        ]);
    }

    public function exportCSV()
    {
        $fileName = "orders.csv";

        $orders = OrderModel::with('user')
            ->orderBy('id')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'ID',
                'Customer',
                'Phone',
                'Total',
                'Payment Method',
                'Status',
                'Created At'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'Customer',
                    $order->phone,
                    $order->total,
                    $order->payment_method,
                    $order->status,
                    $order->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF()
    {
        $orders = OrderModel::with('user')
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView('Admin.PDF.orders_pdf', compact('orders'));

        return $pdf->download('orders.pdf');
    }
}
