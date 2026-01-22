<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\PriceGame;
use App\Services\DigiFlazzService;
use App\Services\VipaymentService;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    private $digiFlazzService;
    protected $vipayment;

    public function __construct(DigiFlazzService $digiFlazzService, VipaymentService $vipayment)
    {
        $this->digiFlazzService = $digiFlazzService;
        $this->vipayment = $vipayment;
    }

    public function indexGame()
    {
        $search = request('search');
        
        $games = DB::table('prices_game')
        ->select('code as buyer_sku_code', 'name as product_name', 'game as category', 'price_basic as price', 'status as desc', 'server')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('game', 'like', '%' . $search . '%')
                  ->orWhere('server', 'like', '%' . $search . '%');
            });
        })
        ->paginate(25)
        ->withQueryString();

        return view('daftar-produk.game', compact('games'));
    }

    public function indexPulsa()
    {
        $search = request('search');
        
        $pulsas = Price::where('category', 'Pulsa') // Mengambil data dengan kategori "Pulsa"
            ->select('product_name', 'category', 'brand', 'type', 'seller_name', 'price', 'buyer_sku_code', 'desc')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', '%' . $search . '%')
                      ->orWhere('brand', 'like', '%' . $search . '%')
                      ->orWhere('seller_name', 'like', '%' . $search . '%')
                      ->orWhere('category', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('brand', 'asc')
            ->orderBy('price', 'asc')
            ->paginate(25);

        return view('daftar-produk.pulsa', compact('pulsas'));
    }

    // (Khusus Digiflazz:)
    public function fetchAndSavePulsaPrices()
    {
        $category = 'Pulsa'; // Set kategori Pulsa
        $response = $this->digiFlazzService->getPriceList($category);

        if (isset($response['data'])) {
            foreach ($response['data'] as $item) {
                Price::updateOrCreate(
                    ['buyer_sku_code' => $item['buyer_sku_code']],
                    [
                        'product_name' => $item['product_name'],
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'type' => $item['type'],
                        'seller_name' => $item['seller_name'],
                        'price' => $item['price'],
                        'buyer_product_status' => $item['buyer_product_status'],
                        'seller_product_status' => $item['seller_product_status'],
                        'unlimited_stock' => $item['unlimited_stock'],
                        'stock' => $item['stock'] ?? null,
                        'multi' => $item['multi'],
                        'start_cut_off' => $item['start_cut_off'] ?? null,
                        'end_cut_off' => $item['end_cut_off'] ?? null,
                        'desc' => $item['desc'] ?? null,
                    ]
                );
            }

            return response()->json(['message' => 'Pulsa prices updated successfully.'], 200);
        }

        return response()->json(['message' => 'Failed to fetch Pulsa prices.'], 500);
    }

    // (Khusus Vipayment:)
    public function fetchAndSaveVipaymentGamePrices()
    {
        try {
            $vipaymentService = new VipaymentService();
            $response = $vipaymentService->fetchGameServices();

            if (!$response['result']) {
                return response()->json(['message' => 'Gagal mengambil data dari VIPayment', 'error' => $response['message']], 500);
            }

            foreach ($response['data'] as $item) {
                PriceGame::updateOrCreate(
                    ['code' => $item['code']],
                    [
                        'game'           => $item['game'],
                        'name'           => $item['name'],
                        'price_basic'    => $item['price']['basic'] ?? 0,
                        'price_premium'  => $item['price']['premium'] ?? 0,
                        'price_special'  => $item['price']['special'] ?? 0,
                        'server'         => $item['server'] ?? '-',
                        'status'         => $item['status'] ?? 'unknown',
                    ]
                );
            }

            return response()->json(['message' => 'Data produk game dari VIPayment berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}

    // (khusus digiflazz tarik data game:)
    // public function fetchAndSavePrices()
    // {
    //     $category = 'Games'; // Set kategori sesuai kebutuhan
    //     $response = $this->digiFlazzService->getPriceList($category);

    //     if (isset($response['data'])) {
    //         foreach ($response['data'] as $item) {
    //             Price::updateOrCreate(
    //                 ['buyer_sku_code' => $item['buyer_sku_code']],
    //                 [
    //                     'product_name' => $item['product_name'],
    //                     'category' => $item['category'],
    //                     'brand' => $item['brand'],
    //                     'type' => $item['type'],
    //                     'seller_name' => $item['seller_name'],
    //                     'price' => $item['price'],
    //                     'buyer_product_status' => $item['buyer_product_status'],
    //                     'seller_product_status' => $item['seller_product_status'],
    //                     'unlimited_stock' => $item['unlimited_stock'],
    //                     'stock' => $item['stock'] ?? null,
    //                     'multi' => $item['multi'],
    //                     'start_cut_off' => $item['start_cut_off'] ?? null,
    //                     'end_cut_off' => $item['end_cut_off'] ?? null,
    //                     'desc' => $item['desc'] ?? null,
    //                 ]
    //             );
    //         }

    //         return response()->json(['message' => 'Prices updated successfully.']);
    //     }
    //     return response()->json(['message' => 'Data produk game dari Digiflazz berhasil disimpan ke tabel prices_game']);
    // }
    
    // ambil dari prices_game, bukan prices
        // $games = Price::where('category', 'Games') // Mengambil data dengan kategori "Games"
        //     ->select('product_name', 'category', 'brand', 'type', 'seller_name', 'price', 'buyer_sku_code', 'desc')
        //     ->orderBy('brand', 'asc')
        //     ->orderBy('price', 'asc')
        //     ->get();
