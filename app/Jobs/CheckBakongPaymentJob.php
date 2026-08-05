<?php

namespace App\Jobs;

use App\Http\Controllers\ApiController\PaymentController;
use App\Models\KhqrPayments;
use App\Services\KHQRService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CheckBakongPaymentJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public int $khqrPaymentId
    ) {}

    public function handle(KHQRService $bakong)
    {
        $payment = KhqrPayments::find($this->khqrPaymentId);

        if (!$payment) {
            return;
        }

        if ($payment->status != 'PENDING') {
            return;
        }
        $controller = app(PaymentController::class);

        $response = $controller->checkPayment(
            $payment->md5
        );

        if (
            ($response['responseCode'] ?? null) == 0
        ) {

            DB::transaction(function () use ($payment, $response) {

                $payment->update([
                    'status' => 'SUCCESS',
                    'transaction_id' => $response['transactionId'] ?? null,
                    'paid_at' => now(),
                    'bakong_response' => $response,
                ]);

                $payment->payment->update([
                    'payment_status' => 'paid',
                ]);

                $payment->order->update([
                    'payment_status' => 'SUCCESS',
                ]);
            });

            return;
        }

        if (now()->greaterThan($payment->expires_at)) {

            $payment->update([
                'status' => 'EXPIRED'
            ]);

            return;
        }

        self::dispatch($payment->id)
            ->delay(now()->addSeconds(10));
    }
}
