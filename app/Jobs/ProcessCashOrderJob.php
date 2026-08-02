<?php

namespace App\Jobs;

use App\Events\NewOrderCreated;
use App\Models\OrderModel;
use App\Models\ProductsModel;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessCashOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Backoff (seconds) between retries — grows on each attempt.
     */
    public array $backoff = [10, 30, 60];

    /**
     * Kill the job if it's still running after this long.
     */
    public int $timeout = 60;

    protected int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Which queue this job runs on. Keep notifications off your default/high-priority queue.
     */
    public function viaQueue(): string
    {
        return 'notifications';
    }

    public function handle(TelegramService $telegram): void
    {
        $order = OrderModel::with([
            'user',
            'payment',
            'orderItems.product.firstImage',
        ])->find($this->orderId);

        if (!$order) {
            Log::warning("ProcessCashOrderJob: order #{$this->orderId} not found, skipping.");
            return;
        }

        $this->broadcastNewOrder($order);
        $this->checkStockAlerts($order, $telegram);
        $this->notifyTelegramIfFirstPending($order, $telegram);
    }

    private function broadcastNewOrder(OrderModel $order): void
    {
        broadcast(new NewOrderCreated($order));
    }

    /**
     * Stock was already decremented synchronously in the controller.
     * Here we just read current quantities and fire alerts if thresholds are crossed.
     */
    private function checkStockAlerts(OrderModel $order, TelegramService $telegram): void
    {
        $limit = 20;

        foreach ($order->orderItems as $item) {
            $product = ProductsModel::find($item->product_id);

            if (!$product) {
                continue;
            }

            if ($product->quantity <= 0) {
                $telegram->sendOutOfStockAlert($product);
                continue;
            }

            if ($product->quantity <= $limit) {
                $telegram->sendLowStockAlert($product);
            }
        }
    }

    /**
     * Preserve original behavior: only the oldest still-unsent pending order
     * triggers the Telegram "new order" message + product images.
     */
    private function notifyTelegramIfFirstPending(OrderModel $order, TelegramService $telegram): void
    {
        $firstPending = OrderModel::where('status', 'pending')
            ->where('is_sent', false)
            ->orderBy('created_at')
            ->first();

        if (!$firstPending || $firstPending->id !== $order->id) {
            return;
        }

        $telegram->sendProductImages($order);
        $telegram->send($this->buildTelegramMessage($order), $order);
    }

    private function buildTelegramMessage(OrderModel $order): string
    {
        $productsText = '';

        foreach ($order->orderItems as $item) {
            $productsText .=
                "• {$item->product->name}\n" .
                "Qty: {$item->qty}\n" .
                "Price: $" . number_format($item->price, 2) . "\n\n";
        }

        $mapUrl = "https://www.google.com/maps?q={$order->lat},{$order->lng}";

        $message =
            "🚀 *NEW ORDER*\n\n" .
            "🆔 *Order:* #{$order->id}\n\n" .
            "👤 *Customer:* {$order->user->full_name}\n" .
            "📞 *Phone:* {$order->user->phone}\n\n" .
            "📍 *Address:*\n{$order->delivery_address}\n\n" .
            "🗺️ [Open Location]({$mapUrl})\n\n";

        if (filled($order->note)) {
            $message .= "📝 *Order Note:*\n{$order->note}\n\n";
        }

        $message .= "🛒 *Products*\n\n" . $productsText . "━━━━━━━━━━━━━━━\n";

        if ($order->promotion_discount > 0) {
            $message .= "🎁 *Promotion Discount:* -$" . number_format($order->promotion_discount, 2) . "\n";
        }

        if ($order->coupon_discount > 0) {
            $message .= "🏷️ *Coupon";
            if ($order->coupon_code) {
                $message .= " ({$order->coupon_code})";
            }
            $message .= ":* -$" . number_format($order->coupon_discount, 2) . "\n";
        }

        $message .=
            "\n💰 *Total:* $" . number_format($order->total_amount, 2) . "\n\n" .
            "💳 *Payment:* " . strtoupper($order->payment->payment_method ?? $order->payment_method) . "\n\n" .
            "📦 *Status:* Pending";

        return $message;
    }

    /**
     * Called when the job fails permanently (all retries exhausted).
     */
    public function failed(Throwable $exception): void
    {
        Log::error("ProcessCashOrderJob failed for order #{$this->orderId}: {$exception->getMessage()}", [
            'order_id' => $this->orderId,
            'trace'    => $exception->getTraceAsString(),
        ]);

        // Optional: notify an admin Telegram/Slack channel that a background job
        // failed, so a human can check the order manually. Kept out by default
        // so a failing notification job doesn't cascade into another failure.
    }
}