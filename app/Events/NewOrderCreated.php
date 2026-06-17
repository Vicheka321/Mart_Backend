<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// class NewOrderCreated implements ShouldBroadcast
// {
//     use Dispatchable, InteractsWithSockets, SerializesModels;

//     public $order;

//     public function __construct($order)
//     {
//         $this->order = $order;
//     }

//     public function broadcastOn(): array
//     {
//         return [
//             new Channel('orders'),
//         ];
//     }

//     public function broadcastAs()
//     {
//         return 'new-order';
//     }
// }

class NewOrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order->load([
            'user',
            'payment',
            'orderItems.product.image'
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }

    public function broadcastAs()
    {
        return 'new-order';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->order->id,
            'full_name' => $this->order->user->full_name,
            'avatar' => $this->order->user->avatar,
            'phone' => $this->order->user->phone,
            'address' => $this->order->delivery_address,
            'total' => $this->order->total_amount,
            'payment_method' => $this->order->payment_method,
            'payment_status' => $this->order->payment->payment_status,
            'status' => $this->order->status,
            'created_at' => $this->order->created_at->format('Y-m-d H:i'),
        ];
    }
}
