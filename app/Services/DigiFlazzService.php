<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DigiFlazzService
{
    private $username;
    private $apiKey;
    private $baseUrl = 'https://api.digiflazz.com/v1/'; // Base URL tetap

    public function __construct()
    {
        $this->username = env('DIGIFLAZZ_USERNAME');
        $this->apiKey = env('DIGIFLAZZ_API_KEY');
    }

    /**
     * Method for sending POST request to DigiFlazz API
     * @param string $endpoint
     * @param array $data
     * @return array|mixed
     */
    public function post($endpoint, $data = [])
    {
        $payload = array_merge($data, [
            'username' => $this->username,
            'sign' => $this->generateSignature(),
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . $endpoint, $payload);

        return $response->json();
    }

    /**
     * Generate DigiFlazz API Signature
     *
     * @return string
     */
    private function generateSignature($refId)
    {
        return md5($this->username . $this->apiKey . $refId);
    }

    private function generateDepositSignature()
    {
        return md5($this->username . $this->apiKey . 'depo');
    }
    
    public function cekSaldo()
    {
        $payload = [
            'cmd' => 'deposit',
            'username' => $this->username,
            'sign' => $this->generateDepositSignature(), 
        ];
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'cek-saldo', $payload);
    
        return $response->json();
    }

    public function getPriceList($category = null)
    {
        $payload = [
            'cmd' => 'prepaid',
            'username' => $this->username,
            'sign' => md5($this->username . $this->apiKey . 'pricelist'),
        ];

        if ($category) {
            $payload['category'] = $category;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'price-list', $payload);

        return $response->json();
    }

    public function topUp($buyerSkuCode, $customerNo, $refId, $testing = false)
    {
        $payload = [
            'username' => $this->username,
            'buyer_sku_code' => $buyerSkuCode,
            'customer_no' => $customerNo,
            'ref_id' => $refId,
            'testing' => filter_var($testing, FILTER_VALIDATE_BOOLEAN), // Pastikan boolean
            'sign' => $this->generateSignature($refId),
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'transaction', $payload);

        return $response->json();
        // dd($payload, $response->json());
    }


}
