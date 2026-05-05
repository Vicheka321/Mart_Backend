<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\OrderModel;

class TelegramService
{
    public function send($message, $order)
    {
        $token = "8685152870:AAEuHrQ7DXHm_W_y6Ty4AxhUbptWOzp4bzM";
        $chat_id = "1689734393";

        $buttons = [];

        if ($order->status == 'pending') {
            $buttons[] = [
                ['text' => '✅ Accept', 'callback_data' => "accept_{$order->id}"],
                ['text' => '❌ Cancel', 'callback_data' => "cancel_{$order->id}"],
            ];
        }

        if ($order->status == 'processing') {
            $buttons[] = [
                ['text' => '📦 Complete', 'callback_data' => "complete_{$order->id}"],
                ['text' => '❌ Cancel', 'callback_data' => "cancel_{$order->id}"],
            ];
        }

        $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons
            ])
        ]);

        $data = $response->json();
        if (isset($data['ok']) && $data['ok'] && isset($data['result']['message_id'])) {

            $order->update([
                'telegram_message_id' => $data['result']['message_id'],
                'telegram_chat_id' => $chat_id
            ]);
        }
    }


    public function edit($order)
    {
        if (!$order->telegram_message_id || !$order->telegram_chat_id) {
            return;
        }

        $token = "8685152870:AAEuHrQ7DXHm_W_y6Ty4AxhUbptWOzp4bzM";

        $statusEmoji = match ($order->status) {
            'processing' => '🔄',
            'completed'  => '✅',
            'cancelled'  => '❌',
            default      => '📦',
        };

        $mapUrl = "https://www.google.com/maps?q={$order->address->lat},{$order->address->lng}";

        $text =
            "🛒 *ORDER UPDATED*  •  `#{$order->id}`\n" .
            "━━━━━━━━━━━━━━━━━━\n\n" .
            "👤 *{$order->user->name}*\n" .
            "📞 `{$order->address->phone}`\n\n" .
            "📍 *Address*\n" .
            "{$order->address->address}\n" .
            "[View on map]({$mapUrl})\n\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "💰 *\$" . number_format($order->total_amount, 2) . "*" .
            "   •   💳 {$order->payment->payment_method}\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "{$statusEmoji} Status: `{$order->status}`";

        $buttons = [];

        if ($order->status === 'processing') {
            $buttons[] = [
                ['text' => '📦 Complete', 'callback_data' => "complete_{$order->id}"],
                ['text' => '❌ Cancel',   'callback_data' => "cancel_{$order->id}"],
            ];
        }

        Http::post("https://api.telegram.org/bot{$token}/editMessageText", [
            'chat_id'      => $order->telegram_chat_id,
            'message_id'   => $order->telegram_message_id,
            'text'         => $text,
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode(['inline_keyboard' => $buttons]),
        ]);
    }

    public function sendNextPending()
    {
        $next = OrderModel::with(['user', 'address', 'payment'])
            ->where('status', 'pending')
            ->whereNull('telegram_message_id')
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$next) return;

        $mapUrl = "https://www.google.com/maps?q={$next->address->lat},{$next->address->lng}";

        $message =
            "🚀 *NEW ORDER*  •  `#{$next->id}`\n" .
            "━━━━━━━━━━━━━━━━━━\n\n" .
            "👤 *{$next->user->name}*\n" .
            "📞 `{$next->address->phone}`\n\n" .
            "📍 *Address*\n" .
            "{$next->address->address}\n" .
            "[View on map]({$mapUrl})\n\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "💰 *\$" . number_format($next->total_amount, 2) . "*" .
            "   •   💳 {$next->payment->payment_method}\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "📦 Status: `{$next->status}`";

        $this->send($message, $next);
    }
}
