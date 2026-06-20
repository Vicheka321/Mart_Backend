<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\OrderModel;
use Barryvdh\DomPDF\Facade\Pdf;

class TelegramService
{

    // public function send($message, $order)
    // {
    //     $token = env('Token');
    //     $chat_id = env('Chat_Id');

    //     $buttons = [];

    //     if ($order->status == 'pending') {

    //         $buttons[] = [

    //             [
    //                 'text' => '✅ Accept',
    //                 'callback_data' => "accept_{$order->id}"
    //             ],

    //             [
    //                 'text' => '❌ Cancel',
    //                 'callback_data' => "cancel_{$order->id}"
    //             ]

    //         ];
    //     }

    //     if ($order->status == 'processing') {

    //         $buttons[] = [

    //             [
    //                 'text' => '✅ Successful',
    //                 'callback_data' => "complete_{$order->id}"
    //             ],

    //             [
    //                 'text' => '🖨 Print',
    //                 'callback_data' => "print_{$order->id}"
    //             ]

    //         ];
    //     }

    //     $order->loadMissing([
    //         'orderItems.product.firstImage'
    //     ]);

    //     $firstImage = null;

    //     $firstItem = $order->orderItems->first();

    //     if (
    //         $firstItem &&
    //         $firstItem->product &&
    //         $firstItem->product->firstImage
    //     ) {
    //         $firstImage =
    //             $firstItem->product
    //             ->firstImage
    //             ->image_url;
    //     }

    //     if ($firstImage) {

    //         $response = Http::post(
    //             "https://api.telegram.org/bot{$token}/sendPhoto",
    //             [
    //                 'chat_id' => $chat_id,

    //                 'photo' => $firstImage,

    //                 'caption' => $message,

    //                 'parse_mode' => 'Markdown',

    //                 'reply_markup' => json_encode([
    //                     'inline_keyboard' => $buttons
    //                 ])
    //             ]
    //         );
    //     } else {

    //         $response = Http::post(
    //             "https://api.telegram.org/bot{$token}/sendMessage",
    //             [
    //                 'chat_id' => $chat_id,

    //                 'text' => $message,

    //                 'parse_mode' => 'Markdown',

    //                 'reply_markup' => json_encode([
    //                     'inline_keyboard' => $buttons
    //                 ])
    //             ]
    //         );
    //     }

    //     $data = $response->json();

    //     if (
    //         isset($data['ok']) &&
    //         $data['ok'] &&
    //         isset($data['result']['message_id'])
    //     ) {

    //         $order->update([

    //             'telegram_message_id' =>
    //             $data['result']['message_id'],

    //             'telegram_chat_id' =>
    //             $chat_id,

    //             'is_sent' => false,
    //         ]);
    //     }
    // }

    public function sendProductImages($order)
    {
        $token = env('Token');
        $chat_id = env('Chat_Id');

        $order->loadMissing([
            'orderItems.product.firstImage'
        ]);

        $media = [];

        foreach ($order->orderItems as $index => $item) {

            if (
                !$item->product ||
                !$item->product->firstImage
            ) {
                continue;
            }

            $media[] = [

                'type' => 'photo',

                'media' =>
                $item->product->firstImage->image_url,

                'caption' =>
                $index == 0
                    ? "🛒 Order #{$order->id}"
                    : ''
            ];
        }

        if (empty($media)) {
            return;
        }

        Http::post(
            "https://api.telegram.org/bot{$token}/sendMediaGroup",
            [
                'chat_id' => $chat_id,
                'media'   => json_encode($media)
            ]
        );
    }

    public function send($message, $order)
    {
        $token = env('Token');
        $chat_id = env('Chat_Id');

        $buttons = [];

        if ($order->status == 'pending') {
            $buttons[] = [
                ['text' => '✅ Accept', 'callback_data' => "accept_{$order->id}"],
                ['text' => '❌ Cancel', 'callback_data' => "cancel_{$order->id}"],
            ];
        }

        if ($order->status == 'processing') {
            $buttons[] = [
                ['text' => '✅ Successful', 'callback_data' => "complete_{$order->id}"],
                [
                    'text' => '🖨 Print',
                    'callback_data' => "print_{$order->id}"
                ]

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
                'is_sent' => false,
            ]);
        }
    }


    public function edit($order)
    {
        if (
            !$order->telegram_message_id ||
            !$order->telegram_chat_id
        ) {
            return;
        }

        $order->load([
            'user',
            'payment',
            'orderItems.product'
        ]);

        $token = env('Token');

        $statusEmoji = match ($order->status) {
            'pending' => '📦',
            'processing' => '🔄',
            'completed' => '✅',
            'cancelled' => '❌',
            default => '📦',
        };

        $statusText = match ($order->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($order->status),
        };

        $mapUrl =
            "https://www.google.com/maps?q={$order->lat},{$order->lng}";

        $customerName =
            $order->user?->full_name ?? 'Customer';

        $phone =
            $order->user?->phone ?? 'N/A';

        $paymentMethod = strtoupper(
            $order->payment?->payment_method
                ?? $order->payment_method
                ?? 'N/A'
        );

        $itemsText = '';

        foreach ($order->orderItems as $item) {

            $itemsText .=
                "• {$item->product->name}\n" .
                "   Qty: {$item->qty}\n" .
                "   Price: $" .
                number_format($item->price, 2) . "\n" .
                "   Amount: $" .
                number_format(
                    $item->qty * $item->price,
                    2
                ) . "\n\n";
        }

        $text =
            "🛒 *ORDER UPDATE*\n" .
            "━━━━━━━━━━━━━━━━━━\n\n" .

            "🆔 *Order:* #{$order->id}\n\n" .

            "👤 *Customer:* {$customerName}\n" .
            "📞 *Phone:* {$phone}\n\n" .

            "📦 *Items:*\n\n" .
            $itemsText .

            "━━━━━━━━━━━━━━━━━━\n\n" .

            "📍 *Address:*\n" .
            "{$order->delivery_address}\n\n" .

            "🗺️ [Open Location]({$mapUrl})\n\n" .

            "━━━━━━━━━━━━━━━━━━\n\n";

        if ($order->promotion_discount > 0) {

            $text .=
                "🎁 *Promotion Discount:* -$" .
                number_format(
                    $order->promotion_discount,
                    2
                ) . "\n";
        }

        if ($order->coupon_discount > 0) {

            $text .= "🏷️ *Coupon";

            if ($order->coupon_code) {
                $text .= " ({$order->coupon_code})";
            }

            $text .=
                ":* -$" .
                number_format(
                    $order->coupon_discount,
                    2
                ) . "\n";
        }

        $text .=
            "\n💰 *Total:* $" .
            number_format(
                $order->total_amount,
                2
            ) . "\n" .

            "💳 *Payment:* {$paymentMethod}\n\n" .

            "━━━━━━━━━━━━━━━━━━\n\n" .

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
                    'text' => '✅ Successful',
                    'callback_data' => "complete_{$order->id}"
                ]
            ];
        }

        Http::post(
            "https://api.telegram.org/bot{$token}/editMessageText",
            [
                'chat_id' => $order->telegram_chat_id,
                'message_id' => $order->telegram_message_id,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ]),
            ]
        );
    }


    // public function edit($order)
    // {
    //     if (!$order->telegram_message_id || !$order->telegram_chat_id) {
    //         return;
    //     }

    //     $order->load([
    //         'user',
    //         'payment'
    //     ]);

    //     $token = '8685152870:AAEuHrQ7DXHm_W_y6Ty4AxhUbptWOzp4bzM';

    //     $statusEmoji = match ($order->status) {
    //         'pending'    => '📦',
    //         'processing' => '🔄',
    //         'completed'  => '✅',
    //         'cancelled'  => '❌',
    //         default      => '📦',
    //     };

    //     $statusText = match ($order->status) {
    //         'pending'    => 'Pending',
    //         'processing' => 'Processing',
    //         'completed'  => 'Completed',
    //         'cancelled'  => 'Cancelled',
    //         default      => ucfirst($order->status),
    //     };

    //     $mapUrl = "https://www.google.com/maps?q={$order->lat},{$order->lng}";

    //     $customerName = $order->user?->full_name ?? 'Customer';
    //     $phone = $order->user?->phone ?? 'N/A';

    //     $paymentMethod = strtoupper(
    //         $order->payment?->payment_method
    //             ?? $order->payment_method
    //             ?? 'N/A'
    //     );

    //     $text =
    //         "🛒 *ORDER UPDATE*\n" .
    //         "━━━━━━━━━━━━━━━━━━\n\n" .

    //         "🆔 *Order:* #{$order->id}\n\n" .

    //         "👤 *Customer:* {$customerName}\n" .
    //         "📞 *Phone:* {$phone}\n\n" .

    //         "📍 *Address:*\n" .
    //         "{$order->delivery_address}\n\n" .

    //         "🗺️ [Open Location]({$mapUrl})\n\n" .

    //         "━━━━━━━━━━━━━━━━━━\n" .

    //         "💰 *Total:* $" .
    //         number_format($order->total_amount, 2) .
    //         "\n" .

    //         "💳 *Payment:* {$paymentMethod}\n\n" .

    //         "━━━━━━━━━━━━━━━━━━\n" .

    //         "{$statusEmoji} *Status:* {$statusText}";

    //     $buttons = [];

    //     if ($order->status === 'pending') {

    //         $buttons[] = [
    //             [
    //                 'text' => '✅ Accept',
    //                 'callback_data' => "accept_{$order->id}"
    //             ],
    //             [
    //                 'text' => '❌ Cancel',
    //                 'callback_data' => "cancel_{$order->id}"
    //             ]
    //         ];
    //     }

    //     if ($order->status === 'processing') {

    //         $buttons[] = [
    //             [
    //                 'text' => '📦 Complete',
    //                 'callback_data' => "complete_{$order->id}"
    //             ],
    //         ];
    //     }

    //     Http::post(
    //         "https://api.telegram.org/bot{$token}/editMessageText",
    //         [
    //             'chat_id'    => $order->telegram_chat_id,
    //             'message_id' => $order->telegram_message_id,
    //             'text'       => $text,
    //             'parse_mode' => 'Markdown',
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => $buttons
    //             ]),
    //         ]
    //     );
    // }


    public function sendNextPending()
    {
        $next = OrderModel::with([
            'user',
            'payment',
            'orderItems.product'
        ])
            ->where('status', 'pending')
            ->where('is_sent', false)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$next) {
            return;
        }

        if ($next->telegram_message_id) {
            return;
        }

        $mapUrl =
            "https://www.google.com/maps?q={$next->lat},{$next->lng}";

        $itemsText = '';

        foreach ($next->orderItems as $item) {

            $itemsText .=
                "• {$item->product->name}\n" .
                "   Qty: {$item->qty}\n" .
                "   Price: $" .
                number_format($item->price, 2) . "\n" .
                "   Amount: $" .
                number_format(
                    $item->qty * $item->price,
                    2
                ) . "\n\n";
        }

        $message =
            "🚀 *NEW ORDER RECEIVED*\n" .
            "━━━━━━━━━━━━━━━\n\n" .

            "🆔 *Order:* #{$next->id}\n\n" .

            "👤 *Customer:* {$next->user->full_name}\n" .

            "📞 *Phone:* {$next->user->phone}\n\n" .

            "📦 *Items:*\n\n" .

            $itemsText .

            "━━━━━━━━━━━━━━━\n\n" .

            "📍 *Delivery Address:*\n" .
            "{$next->delivery_address}\n\n" .

            "🗺️ [Open Location]({$mapUrl})\n\n" .

            "━━━━━━━━━━━━━━━\n\n";

        if ($next->promotion_discount > 0) {

            $message .=
                "🎁 *Promotion Discount:* -$" .
                number_format(
                    $next->promotion_discount,
                    2
                ) . "\n";
        }

        if ($next->coupon_discount > 0) {

            $message .=
                "🏷️ *Coupon";

            if ($next->coupon_code) {
                $message .= " ({$next->coupon_code})";
            }

            $message .=
                ":* -$" .
                number_format(
                    $next->coupon_discount,
                    2
                ) . "\n";
        }

        $message .=

            "\n💰 *Total:* $" .
            number_format(
                $next->total_amount,
                2
            ) . "\n" .

            "💳 *Payment:* " .
            strtoupper(
                $next->payment->payment_method
                    ?? $next->payment_method
            ) . "\n" .

            "📦 *Status:* Pending";

        $this->send(
            $message,
            $next
        );
    }
    // public function sendNextPending()
    // {
    //     $next = OrderModel::with([
    //         'user',
    //         'payment'
    //     ])
    //         ->where('status', 'pending')
    //         ->where('is_sent', false)
    //         ->orderBy('created_at')
    //         ->first();

    //     if (!$next) {
    //         return;
    //     }

    //     if ($next->telegram_message_id) {
    //         return;
    //     }

    //     $mapUrl =
    //         "https://www.google.com/maps?q={$next->lat},{$next->lng}";

    //     $message =
    //         "🚀 *NEW ORDER RECEIVED*\n" .
    //         "━━━━━━━━━━━━━━━\n\n" .

    //         "🆔 *Order:* #{$next->id}\n" .

    //         "👤 *Customer:* {$next->user->full_name}\n" .

    //         "📞 *Phone:* {$next->user->phone}\n\n" .

    //         "📍 *Address:*\n" .
    //         "{$next->delivery_address}\n\n" .

    //         "🗺️ [Open Location]({$mapUrl})\n\n" .

    //         "━━━━━━━━━━━━━━━\n" .

    //         "💰 *Total:* $" .
    //         number_format($next->total_amount, 2) .
    //         "\n" .

    //         "💳 *Payment:* " .
    //         strtoupper($next->payment->payment_method) .
    //         "\n\n" .

    //         "📦 *Status:* Pending";

    //     $this->send($message, $next);
    // }






    public function sendInvoicePdf(
        OrderModel $order,
        $chatId,
        $replyToMessageId
    ) {

        $order->load([
            'user',
            'payment',
            'orderItems.product'
        ]);

        $pdf = Pdf::loadView(
            'admin.order.invoice',
            compact('order')
        );

        $filePath = storage_path(
            "app/invoice-{$order->id}.pdf"
        );

        $pdf->save($filePath);

        Http::attach(
            'document',
            fopen($filePath, 'r'),
            "invoice-{$order->id}.pdf"
        )->post(
            "https://api.telegram.org/bot" .
                env('Token') .
                "/sendDocument",
            [
                'chat_id' => $chatId,

                'reply_to_message_id' =>
                $replyToMessageId,

                'caption' =>
                "📄 Invoice #{$order->id}"
            ]
        );

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
