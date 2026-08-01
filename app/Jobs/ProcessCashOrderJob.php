<?php

namespace App\Jobs;

use App\Models\OrderModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCashOrderJob implements ShouldQueue
{
    use Queueable;

    protected int $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = OrderModel::with([
            'user',
            'payment',
            'orderItems.product.image'
        ])->find($this->orderId);

        if (!$order) {
            return;
        }

        // Background tasks will be added here step by step.
    }
}