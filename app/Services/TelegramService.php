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
                'telegram_chat_id' => $chat_id,
                'is_sent' => true,
            ]);
        }
    }


    public function edit($order)
    {
        if (!$order->telegram_message_id || !$order->telegram_chat_id) {
            return;
        }

        $order->load([
            'user',
            'payment'
        ]);

        $token = env('TELEGRAM_BOT_TOKEN');

        $statusEmoji = match ($order->status) {
            'pending'    => '📦',
            'processing' => '🔄',
            'completed'  => '✅',
            'cancelled'  => '❌',
            default      => '📦',
        };

        $statusText = match ($order->status) {
            'pending'    => 'Pending',
            'processing' => 'Processing',
            'completed'  => 'Completed',
            'cancelled'  => 'Cancelled',
            default      => ucfirst($order->status),
        };

        $mapUrl = "https://www.google.com/maps?q={$order->lat},{$order->lng}";

        $customerName = $order->user?->full_name ?? 'Customer';
        $phone = $order->user?->phone ?? 'N/A';

        $paymentMethod = strtoupper(
            $order->payment?->payment_method
                ?? $order->payment_method
                ?? 'N/A'
        );

        $text =
            "🛒 *ORDER UPDATE*\n" .
            "━━━━━━━━━━━━━━━━━━\n\n" .

            "🆔 *Order:* #{$order->id}\n\n" .

            "👤 *Customer:* {$customerName}\n" .
            "📞 *Phone:* {$phone}\n\n" .

            "📍 *Address:*\n" .
            "{$order->delivery_address}\n\n" .

            "🗺️ [Open Location]({$mapUrl})\n\n" .

            "━━━━━━━━━━━━━━━━━━\n" .

            "💰 *Total:* $" .
            number_format($order->total_amount, 2) .
            "\n" .

            "💳 *Payment:* {$paymentMethod}\n\n" .

            "━━━━━━━━━━━━━━━━━━\n" .

            "{$statusEmoji} *Status:* {$statusText}";

        $buttons = [];

        if ($order->status === 'pending') {

            $buttons[] = [
                [
                    'text' => '✅ Accept',
                    'callback_data' => "accept_{$order->id}"
                ],
                [
                    'text' => '❌ Cancel',
                    'callback_data' => "cancel_{$order->id}"
                ]
            ];
        }

        if ($order->status === 'processing') {

            $buttons[] = [
                [
                    'text' => '📦 Complete',
                    'callback_data' => "complete_{$order->id}"
                ],
                [
                    'text' => '❌ Cancel',
                    'callback_data' => "cancel_{$order->id}"
                ]
            ];
        }

        Http::post(
            "https://api.telegram.org/bot{$token}/editMessageText",
            [
                'chat_id'    => $order->telegram_chat_id,
                'message_id' => $order->telegram_message_id,
                'text'       => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ]),
            ]
        );
    }

    public function sendNextPending()
    {

        $next = OrderModel::with([
            'user',
            'payment'
        ])
            ->where('status', 'pending')
            ->where('is_sent', false)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$next) {
            return;
        }

        $mapUrl =
            "https://www.google.com/maps?q={$next->lat},{$next->lng}";

        $message =
            "🚀 *NEW ORDER RECEIVED*\n" .
            "━━━━━━━━━━━━━━━\n\n" .

            "🆔 *Order:* #{$next->id}\n" .

            "👤 *Customer:* {$next->user->full_name}\n" .

            "📞 *Phone:* {$next->user->phone}\n\n" .

            "📍 *Address:*\n" .
            "{$next->delivery_address}\n\n" .

            "🗺️ [Open Location]({$mapUrl})\n\n" .

            "━━━━━━━━━━━━━━━\n" .

            "💰 *Total:* $" .
            number_format($next->total_amount, 2) .
            "\n" .

            "💳 *Payment:* " .
            strtoupper($next->payment->payment_method) .
            "\n\n" .

            "📦 *Status:* Pending";

        $this->send($message, $next);
    }
}
