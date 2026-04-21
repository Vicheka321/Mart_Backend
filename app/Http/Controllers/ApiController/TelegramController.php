<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\OrderModel;
class TelegramController extends Controller
{
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
        if ($action == 'accept') {
            $order->update(['status' => 'processing']);
        }

        if ($action == 'complete') {
            $order->update(['status' => 'completed']);
        }

        if ($action == 'cancel') {
            $order->update(['status' => 'cancelled']);
        }
        $buttons = [];

        if ($order->status == 'processing') {
            $buttons[] = [
                ['text' => '📦 Complete', 'callback_data' => "complete_{$order->id}"],
                ['text' => '❌ Cancel', 'callback_data' => "cancel_{$order->id}"],
            ];
        }

        $newText = "🛒 Order #{$order->id}\n"
            . "📦 Status: {$order->status}";
        Http::post("https://api.telegram.org/bot8685152870:AAEuHrQ7DXHm_W_y6Ty4AxhUbptWOzp4bzM/editMessageText", [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $newText,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons
            ])
        ]);

        if (in_array($action, ['accept', 'cancel'])) {

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
    }

    return response()->json(['ok' => true]);
}
}
