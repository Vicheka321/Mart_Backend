<?php

namespace App\Http\Controllers\ApiController;

use App\Events\OrderStatusChanged;
use App\Events\PaymentStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\CouponModel;
use App\Models\CouponUsageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\OrderModel;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;
use App\Models\DeviceToken;
use App\Models\PaymentModel;
use App\Models\ProductsModel;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\DB;

class TelegramController extends Controller
{



    public function handle(Request $request, FirebaseNotificationService $firebase)
    {
        $data = $request->all();



        if (isset($data['callback_query'])) {

            $callback = $data['callback_query'];

            $chat_id = $callback['message']['chat']['id'];
            $message_id = $callback['message']['message_id'];
            $callback_id = $callback['id'];

            $text = $callback['data'];

            [$action, $orderId] = explode('_', $text);

            $order = OrderModel::find($orderId);

            if (!$order) return;
            if ($action === 'accept') {

                $order->update([
                    'status' => 'processing'
                ]);
                broadcast(
                    new OrderStatusChanged(
                        $order->id,
                        'processing'
                    )
                );

                $order->refresh();
                $this->sendOrderNotification(
                    $order,
                    $firebase
                );

                app(TelegramService::class)
                    ->sendInvoicePdf(
                        $order,
                        $chat_id,
                        $order->telegram_message_id
                    );

                app(\App\Services\TelegramService::class)
                    ->edit($order);

                app(\App\Services\TelegramService::class)
                    ->sendNextPending();
            } elseif ($action === 'complete') {

                $order->update([
                    'status' => 'completed'
                ]);

                if (
                    $order->payment &&
                    $order->payment->payment_method === 'cash' &&
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

                broadcast(
                    new OrderStatusChanged(
                        $order->id,
                        'completed'
                    )
                );


                $order->refresh();
                try {
                    $this->sendOrderNotification(
                        $order,
                        $firebase,
                        $previousStatus ?? null
                    );
                } catch (\Throwable $e) {
                    Log::error('Notification Error: ' . $e->getMessage());
                }

                app(\App\Services\TelegramService::class)
                    ->edit($order);
            } elseif ($action === 'print') {

                $invoiceUrl =
                    url("/admin/orders/{$order->id}/invoice");

                $token = '8685152870:AAEuHrQ7DXHm_W_y6Ty4AxhUbptWOzp4bzM';

                Http::post(
                    "https://api.telegram.org/bot{$token}/sendMessage",
                    [
                        'chat_id' => $chat_id,
                        'text' =>
                        "🖨 Invoice Link\n\n" .
                            $invoiceUrl
                    ]
                );
            } elseif ($action === 'cancel') {

                if (!in_array($order->status, ['pending', 'processing'])) {
                    return response()->json([
                        'error' => 'Only pending or processing orders can cancel'
                    ], 400);
                }

                $previousStatus = $order->status;

                $order->load('orderItems');

                $this->cancelOrderLogic($order);

                $order->refresh();

                broadcast(
                    new OrderStatusChanged(
                        $order->id,
                        'cancelled'
                    )
                );

                $this->sendOrderNotification(
                    $order,
                    $firebase,
                    $previousStatus
                );

                app(TelegramService::class)->edit($order);

                app(TelegramService::class)->sendNextPending();
            }
        }

        return response()->json(['ok' => true]);
    }


    // private function sendOrderNotification(
    //     OrderModel $order,
    //     FirebaseNotificationService $firebase,
    //     ?string $previousStatus = null
    // ) {

    //     $tokens = DeviceToken::where('user_id', $order->user_id)
    //         ->where('is_active', true)
    //         ->pluck('fcm_token')
    //         ->toArray();

    //     if (empty($tokens)) {
    //         return;
    //     }

    //     switch ($order->status) {

    //         case 'processing':
    //             $title = 'Order Accepted 🎉';
    //             $body = "Your order #{$order->id} has been accepted.";
    //             break;

    //         case 'completed':
    //             $title = 'Order Completed ✅';
    //             $body = "Your order #{$order->id} has been completed.";
    //             break;

    //         case 'cancelled':

    //             if ($previousStatus === 'pending') {
    //                 $title = 'Order Rejected ❌';
    //                 $body = "Your order #{$order->id} has been rejected.";
    //             } else {
    //                 $title = 'Order Cancelled ❌';
    //                 $body = "Your order #{$order->id} has been cancelled.";
    //             }

    //             break;

    //         default:
    //             return;
    //     }

    //     $firebase->sendToTokens(
    //         tokens: $tokens,
    //         title: $title,
    //         body: $body,
    //         data: [
    //             'type' => 'order',
    //             'order_id' => (string) $order->id,
    //             'status' => $order->status,
    //         ]
    //     );
    // }


    private function sendOrderNotification(
        OrderModel $order,
        FirebaseNotificationService $firebase,
        ?string $previousStatus = null
    ) {
        try {

            $tokens = DeviceToken::where('user_id', $order->user_id)
                ->where('is_active', true)
                ->pluck('fcm_token')
                ->toArray();

            if (empty($tokens)) {
                Log::info("No active FCM token for user {$order->user_id}");
                return;
            }

            switch ($order->status) {

                case 'processing':
                    $title = 'Order Accepted 🎉';
                    $body = "Your order #{$order->id} has been accepted.";
                    break;

                case 'completed':
                    $title = 'Order Completed ✅';
                    $body = "Your order #{$order->id} has been completed.";
                    break;

                case 'cancelled':

                    if ($previousStatus === 'pending') {
                        $title = 'Order Rejected ❌';
                        $body = "Your order #{$order->id} has been rejected.";
                    } else {
                        $title = 'Order Cancelled ❌';
                        $body = "Your order #{$order->id} has been cancelled.";
                    }

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

            Log::info("FCM notification sent.", [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
            ]);
        } catch (\Throwable $e) {

            Log::error('Failed to send FCM notification', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Don't throw exception.
            // Telegram workflow should continue.
        }
    }
    private function cancelOrderLogic(OrderModel $order)
    {
        DB::transaction(function () use ($order) {

            foreach ($order->orderItems as $item) {

                $product = ProductsModel::find($item->product_id);

                if ($product) {
                    $product->increment('quantity', $item->qty);
                }
            }

            $couponUsage = CouponUsageModel::where('order_id', $order->id)->first();

            if ($couponUsage) {

                $coupon = CouponModel::find($couponUsage->coupon_id);

                if ($coupon && $coupon->used_count > 0) {
                    $coupon->decrement('used_count');
                }

                $couponUsage->delete();
            }

            $order->update([
                'status' => 'cancelled'
            ]);
        });
    }
}
