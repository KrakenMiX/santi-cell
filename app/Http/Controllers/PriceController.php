<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Services\DigiFlazzService;

class PriceController extends Controller
{
    private $digiFlazzService;

    public function __construct(DigiFlazzService $digiFlazzService)
    {
        $this->digiFlazzService = $digiFlazzService;
    }

    public function indexGame()
    {
        $games = Price::where('category', 'Games') // Mengambil data dengan kategori "Games"
            ->select('product_name', 'category', 'brand', 'type', 'seller_name', 'price', 'buyer_sku_code', 'desc')
            ->get();

        return view('daftar-produk.game', compact('games'));
    }

    public function indexPulsa()
    {
        $pulsas = Price::where('category', 'Pulsa') // Mengambil data dengan kategori "Pulsa"
            ->select('product_name', 'category', 'brand', 'type', 'seller_name', 'price', 'buyer_sku_code', 'desc')
            ->get();

        return view('daftar-produk.pulsa', compact('pulsas'));
    }

    public function fetchAndSavePrices()
    {
        $category = 'Games'; // Set kategori sesuai kebutuhan
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

            return response()->json(['message' => 'Prices updated successfully.']);
        }

        return response()->json(['message' => 'Failed to fetch prices.'], 500);
    }

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

            return response()->json(['message' => 'Pulsa prices updated successfully.']);
        }

        return response()->json(['message' => 'Failed to fetch Pulsa prices.'], 500);
    }
}
