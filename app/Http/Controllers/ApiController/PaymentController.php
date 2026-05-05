<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\khqr_payments;
use App\Services\KHQRService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;




class PaymentController extends Controller
{
    protected KHQRService $khqrService;


    public function __construct(KHQRService $khqrService,)
    {
        $this->khqrService = $khqrService;
    }

    public function generateQR(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'in:USD,KHR',
            'bill_number' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'store_label' => 'nullable|string',
            'terminal_label' => 'nullable|string',
            'type' => 'in:individual,merchant',
        ]);

        $type = $validated['type'] ?? 'individual';

        try {
            $result = $type === 'merchant'
                ? $this->khqrService->generateMerchantQR($validated)
                : $this->khqrService->generateIndividualQR($validated);

            if (isset($result['error'])) {
                Log::error('QR generation error', ['error' => $result['error']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate QR: ' . $result['error'],
                ], 400);
            }

            if (!isset($result['data'])) {
                Log::error('Invalid QR service response', ['result' => $result]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid response from QR service',
                ], 500);
            }

            // Save payment to database
            $payment = khqr_payments::create([
                'md5' => $result['data']['md5'],
                'qr_code' => $result['data']['qr'],
                'amount' => $validated['amount'],
                'currency' => $validated['currency'] ?? 'USD',
                'bill_number' => $validated['bill_number'] ?? null,
                'mobile_number' => $validated['mobile_number'] ?? null,
                'store_label' => $validated['store_label'] ?? null,
                'terminal_label' => $validated['terminal_label'] ?? null,
                'merchant_name' => config('services.bakong.merchant.name'),
                'expires_at' => now()->addMinutes(5), // 5 minute expiry
            ]);

            Log::info('Payment created', [
                'payment_id' => $payment->id,
                'md5' => $payment->md5,
                'amount' => $payment->amount,
                'currency' => $payment->currency
            ]);

            return response()->json([
                'success' => true,
                'qr_code' => $result['data']['qr'],

                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='
                    . urlencode($result['data']['qr']),

                'md5' => $result['data']['md5'],
                'payment_id' => $payment->id,
                'expires_at' => $payment->expires_at->toISOString(),
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

    public function checkPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'md5' => 'required|string',
        ]);

        $payment = khqr_payments::where('md5', $validated['md5'])->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        // Check if already successful
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

        // Check if expired
        if ($payment->isExpired()) {
            $payment->markAsExpired();
            return response()->json([
                'success' => false,
                'status' => 'EXPIRED',
                'message' => 'Payment has expired',
            ]);
        }

        // Check with Bakong API
        $result = $this->khqrService->checkPayment($validated['md5']);
        $payment->incrementCheckAttempts();

        Log::info('Manual payment check', [
            'payment_id'      => $payment->id,
            'md5'             => $payment->md5,
            'bakong_response' => $result,
        ]);

        // NBC API returns responseCode 0 = success, non-zero = not paid / error
        $responseCode = $result['responseCode'] ?? -1;
        $isSuccess = $responseCode === 0;

        if ($isSuccess) {
            // Fetch full transaction details to get hash/amount
            $txInfo = $this->khqrService->getPayment($validated['md5']);
            $transactionId = $txInfo['data']['hash'] ??
                $result['data']['hash'] ??
                null;

            $payment->markAsSuccess($result, $transactionId);

            return response()->json([
                'success' => true,
                'status' => 'SUCCESS',
                'message' => 'Payment completed successfully!',
                'data' => [
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'paid_at' => $payment->paid_at,
                    'transaction_id' => $payment->transaction_id,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'status'  => 'PENDING',
            'message' => $result['responseMessage'] ?? 'Payment not yet completed',
            'data'    => [
                'check_attempts'  => $payment->check_attempts,
                'last_checked_at' => $payment->last_checked_at,
                'expires_at'      => $payment->expires_at,
            ],
        ]);
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
}
