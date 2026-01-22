<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class VipaymentService
{
    private $apiId;
    private $apiKey;
    private $signature;
    private $endpoint;

    public function __construct()
    {
        $this->apiId    = env('VIPAYMENT_API_ID');
        $this->apiKey   = env('VIPAYMENT_API_KEY');
        $this->endpoint = env('VIPAYMENT_URL', 'https://vip-reseller.co.id/api/game-feature');
        $this->signature = md5($this->apiId . $this->apiKey);
    }

    /**
     * Mengambil daftar produk game dari VIPayment
     */
    public function fetchGameServices()
    {
        $params = [
            'key'           => $this->apiKey,
            'sign'          => $this->signature,
            'type'          => 'services',
            'filter_type'   => 'game',
            'filter_status' => 'available',
        ];

        \Log::info('VIPayment fetchGameServices() triggered');
        \Log::info('Params: ', $params);

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        if ($response === false) {
            \Log::error('CURL Error: ' . curl_error($ch));
            curl_close($ch);
            return ['result' => false, 'message' => 'CURL Error: ' . curl_error($ch)];
        }

        curl_close($ch);

        \Log::info('Response from VIPayment: ' . $response);

        return json_decode($response, true);
    }
    
    public function topUp(string $serviceCode, string $dataNo, string $refId, string $dataZone = null): array
    {
        $url = 'https://vip-reseller.co.id/api/game-feature';
    
        $apiKey = $this->apiKey;
        $apiId = $this->apiId;
        $sign = $this->signature;

        // Siapkan payload
        $payload = [
            'key' => $apiKey,
            'sign' => $sign,
            'type' => 'order',
            'service' => $serviceCode,
            'data_no' => $dataNo,
        ];
    
        // Tambahkan data_zone jika ada
        if (!empty($dataZone)) {
            $payload['data_zone'] = $dataZone;
        }

        try {
            $response = Http::asForm()->post($url, $payload);
    
            if ($response->successful()) {
                return $response->json();
            }
    
            return [
                'result' => false,
                'message' => 'Gagal melakukan pemesanan',
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'result' => false,
                'message' => 'Terjadi kesalahan saat menghubungi API VIPayment',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function getProfile()
    {
        $url = 'https://vip-reseller.co.id/api/profile';
    
        $payload = [
            'key'  => $this->apiKey,
            'sign' => $this->signature,
        ];
    
        try {
            $response = Http::asForm()->post($url, $payload);
    
            if ($response->successful()) {
                return $response->json();
            }
    
            return [
                'result' => false,
                'message' => 'Gagal mengambil data profil',
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'result' => false,
                'message' => 'Terjadi kesalahan saat menghubungi API VIPayment',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function checkNickname($code, $userId, $zoneId = null)
    {
        $payload = [
            'key' => $this->apiKey,
            'sign' => $this->signature,
            'type' => 'get-nickname',
            'code' => $code,
            'target' => $userId,
            'additional_target' => $zoneId
        ];
    
        try {
            $response = Http::asForm()->post($this->endpoint, $payload);
            return $response->json();
        } catch (\Exception $e) {
            return [
                'result' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

}
