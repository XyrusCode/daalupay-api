<?php

namespace DaaluPay\Http\Controllers\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DaaluPay\Http\Controllers\BaseController;

class OpayController extends BaseController
{
    /**
     * @var string
     */
    private $merchantId;
    private $publickey;
    private $queryMerchantId;
    private $secretkey;
    private $url;
    private $queryUrl;

    /**
     * Constructor
     */
    public function __construct()
    {
         // For create cashier
        $this->merchantId = config('services.opay.merchant_id');
        $this->publickey = config('services.opay.public_key');
        $this->url = config('services.opay.url');

        // For query payment status
        $this->queryMerchantId = config('services.opay.query_merchant_id');
        $this->secretkey = config('services.opay.secret_key');
        $this->queryUrl = config('services.opay.query_url');
    }

    /**
     * Create cashier
     * @return json
     */
    public function createCashier()
    {
        $data = [
            'country' => 'EG',
            'reference' => '9835413542121', // This could be dynamically generated
            'amount' => [
                'total' => '400',
                'currency' => 'EGP',
            ],
            'returnUrl' => 'https://your-return-url',
            'callbackUrl' => 'https://your-call-back-url',
            'cancelUrl' => 'https://your-cancel-url',
            'expireAt' => 30,
            'userInfo' => [
                'userEmail' => 'xxx@xxx.com',
                'userId' => 'userid001',
                'userMobile' => '13056288895',
                'userName' => 'xxx',
            ],
            'productList' => [
                [
                    'productId' => 'productId',
                    'name' => 'name',
                    'description' => 'description',
                    'price' => 100,
                    'quantity' => 2,
                    'imageUrl' => 'https://imageUrl.com'
                ]
            ],
            'payMethod' => 'BankCard',
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->publickey,
            'MerchantId' => $this->merchantId
        ])->post($this->url, $data);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Invalid HTTP status: ' . $response->status(),
                'response' => $response->body()
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Query payment status
     * @return json
     */
    public function queryPaymentStatus(Request $request)
    {
        $data = [
            'country' => 'EG',
            'reference' => $request->input('reference'), // Get reference from request
        ];

        $dataJson = json_encode($data, JSON_UNESCAPED_SLASHES);
        $auth = $this->generateAuth($dataJson);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $auth,
            'MerchantId' => $this->queryMerchantId
        ])->post($this->queryUrl, $data);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Invalid HTTP status: ' . $response->status(),
                'response' => $response->body()
            ], $response->status());
        }

        return $response->json();
    }

    // Helper method for HMAC authentication
    private function generateAuth($data)
    {
        return hash_hmac('sha512', $data, $this->secretkey);
    }
}
