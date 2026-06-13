<?php

namespace App\Http\Controllers\ApiController;

use App\Events\NewOrderCreated;
use App\Http\Controllers\Controller;
use App\Models\khqr_payments;
use App\Services\KHQRService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\OrderModel;
use Illuminate\Support\Facades\DB;
use App\Services\TelegramService;
use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\ProductsModel;

class PaymentController extends Controller
{
    protected KHQRService $khqrService;


    public function __construct(KHQRService $khqrService,)
    {
        $this->khqrService = $khqrService;
    }

    // public function generateQR(Request $request): JsonResponse
    // {
    //     $validated = $request->validate([
    //         'amount' => 'required|numeric|min:0.01',
    //         'currency' => 'in:USD,KHR',
    //         'bill_number' => 'nullable|string',
    //         'mobile_number' => 'nullable|string',
    //         'store_label' => 'nullable|string',
    //         'terminal_label' => 'nullable|string',
    //         'type' => 'in:individual,merchant',
    //     ]);

    //     $type = $validated['type'] ?? 'individual';

    //     try {
    //         $result = $type === 'merchant'
    //             ? $this->khqrService->generateMerchantQR($validated)
    //             : $this->khqrService->generateIndividualQR($validated);

    //         if (isset($result['error'])) {
    //             Log::error('QR generation error', ['error' => $result['error']]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Failed to generate QR: ' . $result['error'],
    //             ], 400);
    //         }

    //         if (!isset($result['data'])) {
    //             Log::error('Invalid QR service response', ['result' => $result]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid response from QR service',
    //             ], 500);
    //         }

    //         // Save payment to database
    //         $payment = khqr_payments::create([
    //             'md5' => $result['data']['md5'],
    //             'qr_code' => $result['data']['qr'],
    //             'amount' => $validated['amount'],
    //             'currency' => $validated['currency'] ?? 'USD',
    //             'bill_number' => $validated['bill_number'] ?? null,
    //             'mobile_number' => $validated['mobile_number'] ?? null,
    //             'store_label' => $validated['store_label'] ?? null,
    //             'terminal_label' => $validated['terminal_label'] ?? null,
    //             'merchant_name' => config('services.bakong.merchant.name'),
    //             'expires_at' => now()->addMinutes(5), // 5 minute expiry
    //         ]);

    //         Log::info('Payment created', [
    //             'payment_id' => $payment->id,
    //             'md5' => $payment->md5,
    //             'amount' => $payment->amount,
    //             'currency' => $payment->currency
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'qr_code' => $result['data']['qr'],

    //             'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='
    //                 . urlencode($result['data']['qr']),

    //             'md5' => $result['data']['md5'],
    //             'payment_id' => $payment->id,
    //             'expires_at' => $payment->expires_at->toISOString(),
    //             'message' => 'QR generated successfully',
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Exception in generateQR', [
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function generateQR(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {

            /// ✅ GET ORDER
            $order = OrderModel::with('payment', 'address')
                ->findOrFail($validated['order_id']);

            /// ✅ GET PAYMENT
            $payment = $order->payment;


            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            /// ✅ CHECK IF ALREADY PAID
            if ($payment->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already paid'
                ], 400);
            }

            /// ✅ PREPARE KHQR PAYLOAD
            $payload = [
                'amount' => $payment->amount,
                'currency' => 'USD',

                'bill_number' => 'ORDER-' . $order->id,

                'mobile_number' => optional($order)->phone,

                'store_label' => 'MART SHOP',

                'terminal_label' => 'CHECKOUT',

                'type' => 'merchant',
            ];

            /// ✅ GENERATE QR
            $result = $this->khqrService
                ->generateMerchantQR($payload);

            if (isset($result['error'])) {

                Log::error('QR generation error', [
                    'error' => $result['error']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate QR: ' . $result['error'],
                ], 400);
            }

            if (!isset($result['data'])) {

                Log::error('Invalid QR service response', [
                    'result' => $result
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid response from QR service',
                ], 500);
            }

            /// ✅ SAVE TO khqr_payments
            $khqrPayment = khqr_payments::create([

                'order_id' => $order->id,

                'payment_id' => $payment->id,

                'md5' => $result['data']['md5'],

                'qr_code' => $result['data']['qr'],

                'amount' => $payment->amount,

                'currency' => 'USD',

                'bill_number' => 'ORDER-' . $order->id,

                'mobile_number' => optional($order)->phone,

                'store_label' => 'MART SHOP',

                'terminal_label' => 'CHECKOUT',

                'merchant_name' => config('services.bakong.merchant.name'),

                'payment_status' => 'pending',

                'expires_at' => now()->addMinutes(50),
            ]);

            /// ✅ UPDATE PAYMENT TABLE
            $payment->update([
                'md5' => $result['data']['md5'],
                'qr_code' => $result['data']['qr'],
                'expires_at' => $khqrPayment->expires_at,
            ]);

            Log::info('KHQR Payment created', [
                'khqr_payment_id' => $khqrPayment->id,
                'order_id' => $order->id,
                'md5' => $khqrPayment->md5,
                'amount' => $khqrPayment->amount,
            ]);

            return response()->json([

                'success' => true,

                'order_id' => $order->id,

                'payment_id' => $payment->id,

                'khqr_payment_id' => $khqrPayment->id,

                'amount' => $payment->amount,

                'qr_code' => $result['data']['qr'],

                'qr_url' =>
                'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='
                    . urlencode($result['data']['qr']),

                'md5' => $result['data']['md5'],

                'expires_at' => $khqrPayment->expires_at->toISOString(),

                'message' => 'QR generated successfully',
            ]);
        } catch (\Exception $e) {

            Log::error('Exception in generateQR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // public function checkPayment(Request $request): JsonResponse
    // {
    //     $validated = $request->validate([
    //         'md5' => 'required|string',
    //     ]);

    //     $payment = khqr_payments::where('md5', $validated['md5'])->first();

    //     if (!$payment) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Payment not found',
    //         ], 404);
    //     }

    //     // Check if already successful
    //     if ($payment->status === 'SUCCESS') {
    //         return response()->json([
    //             'success' => true,
    //             'status' => 'SUCCESS',
    //             'message' => 'Payment already completed!',
    //             'data' => [
    //                 'amount' => $payment->amount,
    //                 'currency' => $payment->currency,
    //                 'paid_at' => $payment->paid_at,
    //                 'transaction_id' => $payment->transaction_id,
    //             ],
    //         ]);
    //     }

    //     // Check if expired
    //     if ($payment->isExpired()) {
    //         $payment->markAsExpired();
    //         return response()->json([
    //             'success' => false,
    //             'status' => 'EXPIRED',
    //             'message' => 'Payment has expired',
    //         ]);
    //     }

    //     // Check with Bakong API
    //     $result = $this->khqrService->checkPayment($validated['md5']);
    //     $payment->incrementCheckAttempts();

    //     Log::info('Manual payment check', [
    //         'payment_id'      => $payment->id,
    //         'md5'             => $payment->md5,
    //         'bakong_response' => $result,
    //     ]);

    //     // NBC API returns responseCode 0 = success, non-zero = not paid / error
    //     $responseCode = $result['responseCode'] ?? -1;
    //     $isSuccess = $responseCode === 0;

    //     if ($isSuccess) {
    //         // Fetch full transaction details to get hash/amount
    //         $txInfo = $this->khqrService->getPayment($validated['md5']);
    //         $transactionId = $txInfo['data']['hash'] ??
    //             $result['data']['hash'] ??
    //             null;

    //         $payment->markAsSuccess($result, $transactionId);

    //         return response()->json([
    //             'success' => true,
    //             'status' => 'SUCCESS',
    //             'message' => 'Payment completed successfully!',
    //             'data' => [
    //                 'amount' => $payment->amount,
    //                 'currency' => $payment->currency,
    //                 'paid_at' => $payment->paid_at,
    //                 'transaction_id' => $payment->transaction_id,
    //             ],
    //         ]);
    //     }



    //     return response()->json([
    //         'success' => false,
    //         'status'  => 'PENDING',
    //         'message' => $result['responseMessage'] ?? 'Payment not yet completed',
    //         'data'    => [
    //             'check_attempts'  => $payment->check_attempts,
    //             'last_checked_at' => $payment->last_checked_at,
    //             'expires_at'      => $payment->expires_at,
    //         ],
    //     ]);
    // }

    public function checkPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'md5' => 'required|string',
        ]);

        try {

            /// ✅ FIND KHQR PAYMENT
            $payment = khqr_payments::where('md5', $validated['md5'])
                ->first();

            if (!$payment) {

                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found',
                ], 404);
            }

            /// ✅ ALREADY SUCCESS
            if ($payment->status === 'SUCCESS') {

                return response()->json([
                    'success' => true,
                    'status' => 'SUCCESS',
                    'message' => 'Payment already completed!',
                    'data' => [
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'paid_at' => $payment->paid_at,
                        'transaction_id' => $payment->transaction_id,
                    ],
                ]);
            }


            /// ✅ CHECK EXPIRED
            if ($payment->isExpired()) {

                $payment->markAsExpired();

                return response()->json([
                    'success' => false,
                    'status' => 'EXPIRED',
                    'message' => 'Payment has expired',
                ]);
            }


            /// ✅ CHECK WITH BAKONG
            $result = $this->khqrService
                ->checkPayment($validated['md5']);



            /// ✅ INCREMENT CHECK COUNT
            $payment->incrementCheckAttempts();

            Log::info('Manual payment check', [
                'payment_id'      => $payment->id,
                'md5'             => $payment->md5,
                'bakong_response' => $result,
            ]);

            /// ✅ CHECK SUCCESS
            $responseCode = $result['responseCode'] ?? -1;

            $isSuccess = $responseCode === 0;

            if ($isSuccess) {

                /// ✅ GET TRANSACTION DETAIL
                $txInfo = $this->khqrService
                    ->getPayment($validated['md5']);

                $transactionId =
                    $txInfo['data']['hash']
                    ?? $result['data']['hash']
                    ?? null;

                DB::beginTransaction();

                /// ✅ UPDATE KHQR PAYMENT
                $payment->markAsSuccess($result, $transactionId);

                /// ✅ FIND ORDER
                $order = OrderModel::with([
                    'payment',
                    'user',
                    'orderItems'
                ])->find($payment->order_id);


                broadcast(new NewOrderCreated($order));


                if ($order && $order->payment) {

                    /// ✅ UPDATE PAYMENT TABLE
                    $order->payment->update([
                        'payment_status' => 'paid',
                    ]);


                    $cart = CartModel::where(
                        'user_id',
                        $order->user_id
                    )->first();

                    if ($cart) {
                        CartItemModel::where(
                            'cart_id',
                            $cart->id
                        )->delete();
                    }
                    foreach ($order->orderItems as $item) {

                        ProductsModel::where(
                            'id',
                            $item->product_id
                        )->decrement(
                            'quantity',
                            $item->qty
                        );
                    }


                    /// ✅ SEND TELEGRAM
                    // app(TelegramService::class)->send(
                    //     "🚀 *NEW PAID ORDER*\n" .
                    //         "━━━━━━━━━━━━━━━\n" .
                    //         "🆔 Order: #{$order->id}\n" .
                    //         "👤 Customer: {$order->user->name}\n" .
                    //         "📞 Phone: {$order->phone}\n" .
                    //         "📍 Address: {$order->address}\n" .
                    //         "📍 *Location:* https://www.google.com/maps?q={$order->lat},{$order->lng}\n",
                    //         "💰 Total: $" . number_format($order->total_amount, 2) . "\n" .
                    //         "💳 Payment: {$order->payment->payment_method}\n" .
                    //         "━━━━━━━━━━━━━━━",
                    //     $order
                    // );
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'status' => 'SUCCESS',
                    'message' => 'Payment completed successfully!',
                    'data' => [
                        'order_id' => $order?->id,
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'paid_at' => $payment->paid_at,
                        'transaction_id' => $payment->transaction_id,
                    ],
                ]);
            }

            /// ❌ PAYMENT STILL PENDING
            return response()->json([
                'success' => false,
                'status'  => 'PENDING',
                'message' => $result['responseMessage']
                    ?? 'Payment not yet completed',
                'data'    => [
                    'check_attempts'  => $payment->check_attempts,
                    'last_checked_at' => $payment->last_checked_at,
                    'expires_at'      => $payment->expires_at,
                ],
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('checkPayment error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getPaymentStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payment_id' => 'required|integer|exists:payments,id',
        ]);

        $payment = khqr_payments::findOrFail($validated['payment_id']);

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'md5' => $payment->md5,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'status' => $payment->status,
                'created_at' => $payment->created_at,
                'expires_at' => $payment->expires_at,
                'paid_at' => $payment->paid_at,
                'check_attempts' => $payment->check_attempts,
                'last_checked_at' => $payment->last_checked_at,
                'telegram_sent' => $payment->telegram_sent,
            ],
        ]);
    }


    public function ABAPay(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = OrderModel::with('payment', 'address')
            ->findOrFail($validated['order_id']);

        $payment = $order->payment;

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        if ($payment->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order already paid',
            ], 400);
        }

        $ch = curl_init('https://payway.sktopupstore.com/api/aba/payment-gateway/payment/create-payment');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'currency' => 'USD',
            'amount'   => $order->total_amount,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer 4FbB353xdx62TVbW7dnAprUOXpxnyV1u',
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);

            return response()->json([
                'success' => false,
                'message' => curl_error($ch),
            ], 500);
        }

        curl_close($ch);

        $result = json_decode($response, true);
        return response()->json([
            'success'  => true,
            'deeplink' => $result['deeplink'] ?? null,
            'data'     => $result,
        ]);
    }

    public function checkStatusMD5ABA(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'md5Hash' => 'required|string',
        ]);

        $md5Hash = $validated['md5Hash'];
        $apiKey = '4FbB353xdx62TVbW7dnAprUOXpxnyV1u';

        $ch = curl_init('https://payway.sktopupstore.com/api/aba/payment-gateway/payment/check-payment/md5');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'md5_hash' => $md5Hash,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            return response()->json([
                'success' => false,
                'message' => $error,
            ], 500);
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return response()->json([
            'success' => true,
            'data' => $result,
            'payment_status' => $result['payment_status'] ?? null,
        ]);
    }
}
