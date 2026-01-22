<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Services\DigiFlazzService;
use App\Services\VipaymentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class TopUpController extends Controller
{
    private $digiflazzService;
    private $vipaymentService;

    public function __construct(DigiFlazzService $digiflazzService, VipaymentService $vipaymentService)
    {
        $this->digiflazzService = $digiflazzService;
        $this->vipaymentService = $vipaymentService;
    }
    
    // Digiflazz
    public function topupViewPulsa() {
    $brands = Price::where('category', 'Pulsa')->select('brand')->distinct()->pluck('brand');
    $product_names = Price::where('category', 'Pulsa')->select('product_name')->distinct()->pluck('product_name');
    $pulsa = Price::where('category', 'Pulsa') // Hanya kategori Pulsa
        ->select('product_name', 'category', 'brand', 'type', 'seller_name', 'price', 'buyer_sku_code', 'desc')
        ->orderBy('brand', 'asc')
        ->orderBy('price', 'asc')
        ->get();

    return view('topup-produk.topup_pulsa', compact('pulsa', 'brands', 'product_names'));
    }


    public function topUp(Request $request)
    {
        // Ambil data dari request
        $buyerSkuCode = $request->input('buyer_sku_code'); // Contoh: 'xld10'
        $customerNo = $request->input('customer_no'); // Contoh: '087800001230'
        $refId = uniqid(); // Buat Ref ID unik
        $testing = $request->input('testing', true); // Default true jika tidak diisi
        $product = Price::where('buyer_sku_code', $buyerSkuCode)->first();

        // Panggil service
        $response = $this->digiflazzService->topUp($buyerSkuCode, $customerNo, $refId, $testing);
        
        \Log::info('Payload TopUp Digiflazz: ', $response);
        // Simpan ke database
        DB::table('transaksi_topup')->insert([
            'ref_id' => $refId,
            'buyer_sku_code' => $buyerSkuCode,
            'customer_no' => $customerNo,
            'product_name' => $product->product_name ?? 'Unknown',
            'price' => $product->price ?? 0,
            'status' => $response['data']['status'] ?? 'Pending',
            'message' => $response['data']['message'] ?? null,
            'sn' => $response['data']['sn'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = response()->json($response);
        return redirect()->route('topup-pulsa')->with('success', true);
    }
    
    // Vipayment
    public function topupViewGameVip()
    {
        $brands = DB::table('prices_game')->select('game')->distinct()->pluck('game');
        $product_names = DB::table('prices_game')->select('name')->distinct()->pluck('name');
    
        $games = DB::table('prices_game')
            ->select(
                'code as buyer_sku_code',
                'name as product_name',
                'game as category',
                'price_basic as price',
                'status as desc',
                'server'
            )
            ->orderBy('game', 'asc')
            ->orderBy('price_basic', 'asc')
            ->get();
    
        return view('topup-produk/topup_game', compact('games', 'brands', 'product_names'));
    }
    
    public function topUpGameVip(Request $request)
    {
        $buyerSkuCode = $request->input('buyer_sku_code'); // Kode produk
        $dataNo = $request->input('data_no');              // ID tujuan game
        $dataZone = $request->input('data_zone');          // Zone ID (opsional)
        $refId = uniqid();                                 // Ref ID unik
    
        // Ambil data produk dari tabel prices_game
        $product = DB::table('prices_game')
            ->select(
                'code as buyer_sku_code',
                'name as product_name',
                'game as category',
                'price_basic as price',
                'status as desc',
                'server'
            )
            ->where('code', $buyerSkuCode)
            ->first();
    
        // Panggil service Vipayment untuk top up
        $response = $this->vipaymentService->topUp($buyerSkuCode, $dataNo, $refId, $dataZone);
        
        $trxid = $response['data']['trxid'] ?? null;
    
        // Siapkan data transaksi ke database
        $insertData = [
            'ref_id' => $trxid,
            'buyer_sku_code' => $buyerSkuCode,
            'customer_no' => $dataNo,
            'product_name' => $product->product_name ?? 'Unknown',
            'price' => $product->price ?? 0,
            'status' => $response['data']['status'] ?? 'gagal',
            'message' => $response['message'] ?? 'Gagal tanpa pesan',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    
        // Jika berhasil, update info tambahan
        if ($response['result'] === true && isset($response['data'])) {
            $insertData['price'] = $response['data']['price'] ?? ($product->price ?? 0);
            $insertData['status'] = $response['data']['status'] ?? 'waiting';
        }
    
        // Simpan ke tabel transaksi_topup
        DB::table('transaksi_topup')->insert($insertData);
        return redirect()->route('topup-game')->with('success', true);
        
        // Kembalikan response asli dari API
        // return response()->json($response);
    }


    public function riwayatTransaksi(Request $request)
    {
        $query = DB::table('transaksi_topup')
            ->orderBy('created_at', 'desc');
    
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('customer_no', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }
    
        $transaksi = $query->paginate(25);
        
        return view('transaksi.index', compact('transaksi'));
    }

    public function detail($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.detail', compact('transaksi'));
    }

    public function topupWebhook(Request $request) {

        $post_data = file_get_contents('php://input');
        Log::info($post_data);

        return response()->json(['success' => true]);
    }
    
    public function handleCallback(Request $request)
    {
        // Signature cocok, proses data
        \Log::info('Callback valid:', [$request->all()]);
        
        // Ambil secret dari .env
        $secret = env('DIGIFLAZZ_WEBHOOK_SECRET');
    
        // Ambil body mentah dan hitung signature
        $post_data = file_get_contents('php://input');
        $signature = 'sha1=' . hash_hmac('sha1', $post_data, $secret);
    
        // Cocokkan dengan header dari Digiflazz
        if ($request->header('X-Hub-Signature') !== $signature) {
            \Log::warning('Signature tidak cocok, callback ditolak.');
            return response()->json(['message' => 'Invalid signature'], 403);
        }
    
        $data = $request->input('data');
    
        DB::table('transaksi_topup')
            ->where('ref_id', $data['ref_id'])
            ->update([
                'status' => $data['status'],
                'message' => $data['message'] ?? null,
                'sn' => $data['sn'] ?? null,
                'updated_at' => now()
            ]);
    
        return response()->json(['message' => 'Callback diterima'], 200);
    }

    public function handleCallbackVipayment(Request $request)
    {
        \Log::info('Callback VIPayment masuk:', [$request->all()]);
    
        // Validasi Signature (X-Client-Signature)
        $clientSignature = $request->header('X-Client-Signature');
        $expectedSignature = md5(env('VIPAYMENT_API_ID') . env('VIPAYMENT_API_KEY'));
    
        if ($clientSignature !== $expectedSignature) {
            \Log::warning('Signature VIPayment tidak cocok.');
            return response()->json(['message' => 'Invalid signature'], 403);
        }
    
        $data = $request->input('data'); // data utama dari JSON
    
        // Cari transaksi berdasarkan trxid
        $trxid = $data['trxid'] ?? null;
        $status = $data['status'] ?? 'pending';
    
        if (!$trxid) {
            \Log::error('trxid tidak ditemukan dalam data callback.');
            return response()->json(['message' => 'trxid missing'], 400);
        }
    
        DB::table('transaksi_topup')
            ->where('ref_id', $trxid)
            ->update([
                'status' => $status,
                'message' => $data['note'] ?? null,
                'updated_at' => now()
            ]);
    
        return response()->json(['message' => 'Callback VIPayment diproses.'], 200);
    }
    public function cekNickname(Request $request)
    {
        $code = $request->input('code');
        $userId = $request->input('user_id');
        $zoneId = $request->input('zone_id');
    
        $response = $this->vipaymentService->checkNickname($code, $userId, $zoneId);
    
        return response()->json($response);
    }
}

    // public function topupViewGame() {
    //     $brands = Price::where('category', 'Games')->select('brand')->distinct()->pluck('brand');
    //     $product_names = Price::where('category', 'Games')->select('product_name')->distinct()->pluck('product_name');
    //     $games = Price::where('category', 'Games') // Mengambil data dengan kategori "Games"
    //     ->select('product_name', 'category', 'brand', 'type', 'seller_name', 'price', 'buyer_sku_code', 'desc')
    //     ->orderBy('brand', 'asc')
    //     ->orderBy('price', 'asc')
    //     ->get();

    //     return view('topup-produk/topup_game', compact('games', 'brands', 'product_names'));
    // }
    
        // public function handleCallback(Request $request)
    // {
    //     $data = $request->input('data');

    //     DB::table('transaksi_topup')
    //         ->where('ref_id', $data['ref_id'])
    //         ->update([
    //             'status' => $data['status'],
    //             'message' => $data['message'] ?? null,
    //             'sn' => $data['sn'] ?? null,
    //             'updated_at' => now()
    //         ]);

    //     return response()->json(['message' => 'Callback diterima'], 200);
    // }
