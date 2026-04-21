<?php

namespace App\Services;

use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use KHQR\Models\SourceInfo;

class BakongService
{
    protected BakongKHQR $khqr;

    public function __construct()
    {
        $this->khqr = new BakongKHQR(config('bakong.token'));
    }

    /**
     * Generate a dynamic KHQR for a payment.
     * Returns ['qr' => '...', 'md5' => '...']
     */
    public function generateQR(float $amount, string $currency = 'USD', string $billNumber = null): array
    {
        $info = new IndividualInfo(
            bakongAccountID: config('bakong.account_id'),
            merchantName:    config('bakong.merchant_name'),
            merchantCity:    config('bakong.merchant_city'),
            currency:        $currency === 'KHR' ? KHQRData::CURRENCY_KHR : KHQRData::CURRENCY_USD,
            amount:          $amount,
            billNumber:      $billNumber ?? 'INV-' . time(),
        );

        $response = BakongKHQR::generateIndividual($info);

        if ($response->status['code'] !== 0) {
            throw new \Exception('Failed to generate KHQR: ' . $response->status['message']);
        }

        return $response->data; // ['qr' => '...', 'md5' => '...']
    }

    /**
     * Check if a transaction has been paid.
     * Returns true if paid, false if unpaid.
     */
    public function checkPayment(string $md5): bool
    {
        $response = $this->khqr->checkTransactionByMD5($md5);

        // responseCode 0 = paid
        return isset($response['responseCode']) && $response['responseCode'] === 0;
    }

    /**
     * Generate a Bakong deeplink for mobile apps.
     */
    public function generateDeepLink(string $qr, string $callbackUrl): string
    {
        $sourceInfo = new SourceInfo(
            appIconUrl:          url('/images/logo.png'),
            appName:             config('app.name'),
            appDeepLinkCallback: $callbackUrl
        );

        $response = BakongKHQR::generateDeepLink($qr, $sourceInfo);

        return $response->data->shortLink ?? '';
    }

    /**
     * Renew token if expired (token lasts ~90 days).
     */
    public function renewToken(string $email): array
    {
        return BakongKHQR::renewToken($email);
    }
}