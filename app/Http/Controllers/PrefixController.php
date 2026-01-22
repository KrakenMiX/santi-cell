<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prefix;

class PrefixController extends Controller
{
    public function checkView()
    {
        return view('topup-produk.cek_prefix');
    }
    
    public function checkPrefix(Request $request)
    {
        $request->validate([
            'nomor' => 'required|digits_between:10,13',
        ]);
    
        $prefix = substr($request->nomor, 0, 4);
        $operator = \App\Models\Prefix::where('prefix', $prefix)->first();
    
        if (!$operator) {
            return back()->withErrors(['nomor' => 'Nomor tidak dikenal.']);
        }
    
        return redirect()->route('topup-pulsa')->with([
            'nomor' => $request->nomor,
            'operator' => $operator->operator,
        ]);
    }

}
