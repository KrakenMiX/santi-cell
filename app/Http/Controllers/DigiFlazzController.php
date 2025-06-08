<?php

namespace App\Http\Controllers;

use App\Services\DigiFlazzService;

class DigiFlazzController extends Controller
{
    private $digiFlazz;

    public function __construct(DigiFlazzService $digiFlazz)
    {
        $this->digiFlazz = $digiFlazz;
    }

    public function cekRespon()
    {
        $response = $this->digiFlazz->post('cek-saldo', []);
        return response()->json($response);
    }

    // Add another endpoints for testing below here
}
