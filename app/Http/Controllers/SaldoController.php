<?php

namespace App\Http\Controllers;

use App\Services\DigiFlazzService;

class SaldoController extends Controller
{
    private $digiFlazzService;

    public function __construct(DigiFlazzService $digiFlazzService)
    {
        $this->digiFlazzService = $digiFlazzService;
    }

    public function cekSaldo()
    {
        $result = $this->digiFlazzService->cekSaldo();
        return response()->json($result);
    }
}
