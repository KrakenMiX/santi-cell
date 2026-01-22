<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaProdukController extends Controller
{
    public function game()
    {
        $search = request('search');

        $games = DB::table('prices_game')
            ->select(
                'code as buyer_sku_code',
                'name as product_name',
                'game as category',
                'price_basic as price',
                'status as desc',
                'server'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('game', 'like', '%' . $search . '%')
                      ->orWhere('server', 'like', '%' . $search . '%');
                });
            })
            ->paginate(25)
            ->withQueryString();

        return view('kelola-produk.game', compact('games'));
    }

    public function pulsa()
    {
        $search = request('search');

        $pulsas = \App\Models\Price::where('category', 'Pulsa')
            ->select('product_name', 'category', 'brand', 'price', 'buyer_sku_code', 'seller_name', 'desc')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', '%' . $search . '%')
                      ->orWhere('brand', 'like', '%' . $search . '%')
                      ->orWhere('category', 'like', '%' . $search . '%')
                      ->orWhere('seller_name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('brand', 'asc')
            ->orderBy('price', 'asc')
            ->paginate(25)
            ->withQueryString();
    
        return view('kelola-produk.pulsa', compact('pulsas'));
    }
}
