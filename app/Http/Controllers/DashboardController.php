<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DigiFlazzService;
use App\Services\VipaymentService;

class DashboardController extends Controller
{
    public function index(DigiFlazzService $digiflazz, VipaymentService $vipayment)
    {
        $userName = Auth::user()->name;

        // Ambil saldo dari DigiFlazz
        $saldoResponse = $digiflazz->cekSaldo();
        $saldo = $saldoResponse['data']['deposit'] ?? 0;
        
        // Ambil saldo dari VIPayment
        $profile = $vipayment->getProfile();
        $saldoVip = $profile['data']['balance'] ?? 0;

        return view('dashboard', compact('userName', 'saldo', 'saldoVip'));
    }
}