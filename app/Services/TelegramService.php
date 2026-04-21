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

        $buttons = [];

        if ($order->status == 'processing') {
            $buttons[] = [
                ['text' => '📦 Complete', 'callback_data' => "complete_{$order->id}"],
                ['text' => '❌ Cancel', 'callback_data' => "cancel_{$order->id}"],
            ];
        }
        $text = "🛒 Order #{$order->id}\n"
            . "📦 Status: {$order->status}";

        Http::post("https://api.telegram.org/bot{$token}/editMessageText", [
            'chat_id' => $order->telegram_chat_id,
            'message_id' => $order->telegram_message_id,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons
            ])
        ]);
    }
}
