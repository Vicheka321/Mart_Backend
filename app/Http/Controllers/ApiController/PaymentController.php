<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;


use App\Models\PaymentModel;
use App\Services\BakongService;
use Illuminate\Http\Request;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use Symfony\Component\CssSelector\Exception\ExpressionErrorException;

class PaymentController extends Controller
{
    public function __construct(protected BakongService $bakong) {}

    /**
     * Generate a new QR code for a payment.
     */
    public function generate()
    {
        $info = new IndividualInfo(
            bakongAccountID: 'long_vicheka@aba',
            merchantName: 'VICHEKA LONG',
            merchantCity: 'PHNOM PENH',
            currency: KHQRData::CURRENCY_KHR,
            amount: 1000,
            billNumber: 'INV-' . time(),
            
        );

        $response = BakongKHQR::generateIndividual($info);

        if ($response->status['code'] === 0) {
            return response()->json([
                'qr' => $response->data['qr'],
                'md5' => $response->data['md5'],
            ]);
        }

        return response()->json([
            'error' => 'QR generation failed',
            'status' => $response->status
        ], 500);
    }
    /**
     * Poll to check payment status.
     */
    public function status(string $orderId)
    {
        $payment = PaymentModel::where('order_id', $orderId)->firstOrFail();

        if ($payment->status === 'paid') {
            return response()->json(['status' => 'paid']);
        }

        if ($payment->expires_at && now()->gt($payment->expires_at)) {
            $payment->update(['status' => 'expired']);
            return response()->json(['status' => 'expired']);
        }

        $paid = $this->bakong->checkPayment($payment->md5_hash);

        if ($paid) {
            $payment->update(['status' => 'paid']);
        }

        return response()->json(['status' => $payment->fresh()->status]);
    }
}
