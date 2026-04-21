<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $status = request('status');

        $orders = OrderModel::with(['orderItems.product.category', 'orderItems.product.brand', 'orderItems.product.image', 'user', 'address', 'payment'])
            ->when($status && $status != 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
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
            ];
        });


        return view('admin.orders', compact('orders'));
    }
    public function latest()
    {
        $order = OrderModel::latest()->first();

        return response()->json([
            'id' => $order?->id,
            'total' => $order?->total_amount
        ]);
    }

    public function notifications()
    {
        $orders = OrderModel::where('status', 'pending')
            ->latest()
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
}
