<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use App\Services\DigiFlazzService; // Import service
use Illuminate\Support\Facades\Log;

class TopUpController extends Controller
{
    private $digiflazzService;

    public function __construct(DigiFlazzService $digiflazzService)
    {
        $this->digiflazzService = $digiflazzService;
    }

    public function topupViewGame() {
        $brands = Price::select('brand')->distinct()->pluck('brand');
        $product_names = Price::select('product_name')->distinct()->pluck('product_name');
        $games = Price::where('category', 'Games') // Mengambil data dengan kategori "Games"
        ->select('product_name', 'category', 'brand', 'type', 'seller_name', 'price', 'buyer_sku_code', 'desc')
        ->get();

        return view('topup-produk/topup_game', compact('games', 'brands', 'product_names'));
    }

    public function topUp(Request $request)
    {
        // Ambil data dari request
        $buyerSkuCode = $request->input('buyer_sku_code'); // Contoh: 'xld10'
        $customerNo = $request->input('customer_no'); // Contoh: '087800001230'
        $refId = uniqid(); // Buat Ref ID unik
        $testing = $request->input('testing', true); // Default true jika tidak diisi

        // Panggil service
        $response = $this->digiflazzService->topUp($buyerSkuCode, $customerNo, $refId, $testing);

        $response = response()->json($response);
        return $response;
        
    }

    public function topupWebhook(Request $request) {
        // $secret = 'mysecret';

        $post_data = file_get_contents('php://input');
        Log::info($post_data);

        // error_log('dapet');
        // error_log(json_decode($request->getContent(), true));

        // $signature = hash_hmac('sha1', $post_data, $secret);
        // Log::info($signature);

        // if ($request->header('X-Hub-Signature') == 'sha1='.$signature) {
        Log::info(json_decode($request->getContent(), true));
        // }

        return response()->json(['success' => true]);
    }

    public function handleCallback(Request $request)
    {
        $data = $request->input('data');

        // Simpan atau update status transaksi berdasarkan ref_id
        $ref_id = $data['ref_id'] ?? null;
        $status = $data['status'] ?? 'Unknown';

        // Contoh: update transaksi di database
        DB::table('transaksi_topup')
            ->where('ref_id', $ref_id)
            ->update([
                'status' => $status,
                'message' => $data['message'] ?? '',
                'sn' => $data['sn'] ?? '',
            ]);

        // Wajib kirim response 200 OK agar Digiflazz tahu callback berhasil
        return response()->json(['message' => 'Callback diterima'], 200);
    }

}
