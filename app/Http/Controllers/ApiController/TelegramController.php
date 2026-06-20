<?php

namespace App\Http\Controllers\ApiController;

use App\Events\OrderStatusChanged;
use App\Events\PaymentStatusChanged;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\OrderModel;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;
class TelegramController extends Controller
{
    // public function handle(Request $request)
    // {
    //     $data = $request->all();

    //     if (isset($data['callback_query'])) {

    //         $callback = $data['callback_query'];

    //         $chat_id = $callback['message']['chat']['id'];
    //         $message_id = $callback['message']['message_id'];
    //         $callback_id = $callback['id'];

    //         $text = $callback['data'];

    //         [$action, $orderId] = explode('_', $text);

    //         $order = OrderModel::find($orderId);

    //         if (!$order) return;
    //         if ($action === 'accept') {

    //             $order->update([
    //                 'status' => 'processing'
    //             ]);
    //             broadcast(
    //                 new OrderStatusChanged(
    //                     $order->id,
    //                     'processing'
    //                 )
    //             );

    //             $order->refresh();

    //             app(\App\Services\TelegramService::class)
    //                 ->edit($order);

    //             app(\App\Services\TelegramService::class)
    //                 ->sendNextPending();
    //         } elseif ($action === 'complete') {

    //             $order->update([
    //                 'status' => 'completed'
    //             ]);

    //             if (
    //                 $order->payment &&
    //                 $order->payment->payment_method === 'cash' &&
    //                 $order->payment->payment_status === 'unpaid'
    //             ) {

    //                 $order->payment->update([
    //                     'payment_status' => 'paid'
    //                 ]);

    //                 broadcast(
    //                     new PaymentStatusChanged(
    //                         $order->id,
    //                         'paid'
    //                     )
    //                 );
    //             }

    //             broadcast(
    //                 new OrderStatusChanged(
    //                     $order->id,
    //                     'completed'
    //                 )
    //             );


    //             $order->refresh();

    //             app(\App\Services\TelegramService::class)
    //                 ->edit($order);
    //         } elseif ($action === 'print') {

    //             $invoiceUrl =
    //                 url("/admin/orders/{$order->id}/invoice");

    //             $token = '8685152870:AAEuHrQ7DXHm_W_y6Ty4AxhUbptWOzp4bzM';

    //             Http::post(
    //                 "https://api.telegram.org/bot{$token}/sendMessage",
    //                 [
    //                     'chat_id' => $chat_id,
    //                     'text' =>
    //                     "🖨 Invoice Link\n\n" .
    //                         $invoiceUrl
    //                 ]
    //             );
    //         } elseif ($action === 'cancel') {

    //             if ($order->status !== 'pending') {
    //                 return response()->json([
    //                     'error' => 'Only pending orders can cancel'
    //                 ]);
    //             }

    //             $order->update([
    //                 'status' => 'cancelled'
    //             ]);

    //             $order->refresh();

    //             app(TelegramService::class)->edit($order);

    //             app(TelegramService::class)->sendNextPending();
    //         }
    //     }

    //     return response()->json(['ok' => true]);
    // }


    public function handle(Request $request)
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

                if ($order->status !== 'pending') {
                    return response()->json([
                        'error' => 'Only pending orders can cancel'
                    ]);
                }

                $order->update([
                    'status' => 'cancelled'
                ]);

                $order->refresh();

                app(TelegramService::class)->edit($order);

                app(TelegramService::class)->sendNextPending();
            }
        }

        return response()->json(['ok' => true]);
    }
}
