<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $orderId,
        public string $paymentStatus
    ) {}

    public function broadcastOn()
    {
        return [
            new Channel('orders')
        ];
    }

    public function broadcastAs()
    {
        return 'payment-status-changed';
    }

    public function broadcastWith()
    {
        return [
            'orderId' => $this->orderId,
            'paymentStatus' => $this->paymentStatus,
        ];
    }
}
