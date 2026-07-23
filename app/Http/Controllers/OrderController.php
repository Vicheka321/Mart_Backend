<?php

namespace App\Http\Controllers;

use App\Events\PaymentStatusChanged;
use App\Models\OrderModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DeviceToken;
use App\Services\FirebaseNotificationService;

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
            'payment'
        ])
            // Show only orders where payment status = paid
            ->whereHas('payment', function ($q) {
                $q->whereIn('payment_status', [
                    'paid',
                    'unpaid'
                ]);
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
                'full_name' => $order->user->full_name ?? 'Customer',
                'avatar'     => $order->user->avatar ?? null,
                'phone' => $order->user->phone ?? '',
                'address' => $order->delivery_address ?? '',
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
            $q->whereIn('payment_status', [
                'paid',
                'unpaid'
            ]);
        })->count();

        $pendingOrders = OrderModel::where('status', 'pending')
            ->whereHas('payment', fn($q) => $q->whereIn('payment_status', ['paid', 'unpaid']))
            ->count();

        $processingOrders = OrderModel::where('status', 'processing')
            ->whereHas('payment', fn($q) => $q->whereIn('payment_status', ['paid', 'unpaid']))
            ->count();

        $completedOrders = OrderModel::where('status', 'completed')
            ->whereHas('payment', fn($q) => $q->whereIn('payment_status', ['paid', 'unpaid']))
            ->count();

        $cancelledOrders = OrderModel::where('status', 'cancelled')
            ->whereHas('payment', fn($q) => $q->whereIn('payment_status', ['paid', 'unpaid']))
            ->count();

        return view('Admin.orders', compact('orders', 'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders', 'cancelledOrders'));
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
            ->whereIn('payments.payment_status', [
                'paid',
                'unpaid'
            ])
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
    
    public function changeStatus(Request $request, $id, FirebaseNotificationService $firebase)
    {
        $order = OrderModel::with('payment')
            ->findOrFail($id);

        $valid = [
            'pending',
            'processing',
            'completed',
            'cancelled'
        ];

        if (!in_array($request->status, $valid)) {
            return response()->json([
                'error' => 'Invalid status'
            ], 400);
        }

        if (
            $request->status === 'cancelled' &&
            $order->status !== 'pending'
        ) {
            return response()->json([
                'error' => 'Only pending orders can be cancelled'
            ], 400);
        }

        if ($order->status === 'completed') {
            return response()->json([
                'error' => 'Already completed'
            ], 400);
        }
        $order->update([
            'status' => $request->status
        ]);

        $order->refresh();

        if (in_array($order->status, [
            'processing',
            'cancelled',
            'completed'
        ])) {
            $this->sendOrderNotification(
                $order,
                $firebase
            );
        }
        
        $order->load([
            'user',
            'payment',
            'orderItems.product'
        ]);

        if (
            $request->status === 'processing' &&
            $order->telegram_chat_id &&
            $order->telegram_message_id
        ) {
            app(\App\Services\TelegramService::class)
                ->sendInvoicePdf(
                    $order,
                    $order->telegram_chat_id,
                    $order->telegram_message_id
                );
        }

        if (
            $request->status === 'completed' &&
            $order->payment &&
            $order->payment->payment_status === 'unpaid'
        ) {
            $order->payment->update([
                'payment_status' => 'paid'
            ]);
            broadcast(
                new PaymentStatusChanged(
                    $order->id,
                    'paid'
                )
            );
        }

        app(\App\Services\TelegramService::class)
            ->edit($order);

        if (
            in_array(
                $request->status,
                ['processing', 'cancelled']
            )
        ) {
            app(\App\Services\TelegramService::class)
                ->sendNextPending();
        }

        return response()->json([
            'message' => 'Updated successfully'
        ]);
    }

    private function sendOrderNotification(
        OrderModel $order,
        FirebaseNotificationService $firebase
    ) {
        $tokens = DeviceToken::where('user_id', $order->user_id)
            ->where('is_active', true)
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            return;
        }

        switch ($order->status) {

            case 'processing':
                $title = 'Order Accepted 🎉';
                $body = "Your order #{$order->id} has been accepted.";
                break;

            case 'cancelled':
                $title = 'Order Cancelled ❌';
                $body = "Your order #{$order->id} has been cancelled.";
                break;

            case 'completed':
                $title = 'Order Completed ✅';
                $body = "Your order #{$order->id} has been completed.";
                break;

            default:
                return;
        }

        $firebase->sendToTokens(
            tokens: $tokens,
            title: $title,
            body: $body,
            data: [
                'type' => 'order',
                'order_id' => (string) $order->id,
                'status' => $order->status,
            ]
        );
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
    // {
    //     $fileName = "orders.csv";

    //     $orders = OrderModel::with('user')
    //         ->orderBy('id')
    //         ->get();

    //     $headers = [
    //         "Content-type"        => "text/csv",
    //         "Content-Disposition" => "attachment; filename={$fileName}",
    //     ];

    //     $callback = function () use ($orders) {
    //         $file = fopen('php://output', 'w');

    //         // Header
    //         fputcsv($file, [
    //             'No.',
    //             'Client',
    //             'Phone',
    //             'Total',
    //             'Payment Method',
    //             'Status',
    //             'address',
    //             'Created At'
    //         ]);

    //         foreach ($orders as $order) {
    //             fputcsv($file, [
    //                 $order->id,
    //                 $order->user->name ?? 'Customer',
    //                 $order->phone,
    //                 $order->total,
    //                 $order->payment_method,
    //                 $order->status,
    //                 $order->delivery_address,
    //                 $order->created_at,
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }

    public function exportCSV()
    {
        $fileName = 'orders_' . now()->format('Ymd_His') . '.csv';

        $orders = OrderModel::with('user')
            ->orderBy('id')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($orders) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM (Excel Khmer/Chinese support)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Order ID',
                'Customer Name',
                'Phone',
                'Total Amount',
                'Payment Method',
                'Order Status',
                'Delivery Address',
                'Created At',
            ]);

            foreach ($orders as $order) {

                fputcsv($file, [
                    $order->id,

                    $order->user?->full_name
                        ?? $order->user?->name
                        ?? 'Guest',

                    $order->user->phone,

                    number_format($order->total_amount, 2),

                    ucfirst($order->payment_method),

                    ucfirst($order->status),

                    $order->delivery_address,

                    optional($order->created_at)
                        ->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            200,
            $headers
        );
    }
    public function exportPDF()
    {
        $orders = OrderModel::with('user')
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView('Admin.PDF.orders_pdf', compact('orders'));

        return $pdf->download('orders.pdf');
    }


    public function invoice($id)
    {
        $order = OrderModel::with([
            'user',
            'payment',
            'orderItems.product'
        ])->findOrFail($id);

        return view(
            'Admin.order.invoice',
            compact('order')
        );
    }

    public function invoicePdf($id)
    {
        $order = OrderModel::with([
            'user',
            'payment',
            'orderItems.product'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'Admin.order.invoice',
            compact('order')
        );

        return $pdf->download(
            "invoice-{$order->id}.pdf"
        );
    }
}
