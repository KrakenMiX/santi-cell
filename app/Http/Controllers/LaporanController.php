<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function cetakPDF(Request $request)
    {
        $query = DB::table('transaksi_topup');
    
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }
    
        if ($request->filled('status')) {
            if ($request->status == 'Sukses') {
                $query->where('status', 'Sukses');
                $query->whereOr('status', 'success');
            } elseif ($request->status == 'Gagal') {
                $query->where('status', 'Gagal');
                $query->whereOr('status', 'gagal');
                $query->whereOr('status', 'error');
            } elseif ($request->status == 'Pending') {
                $query->where('status', 'Pending');
                $query->whereOr('status', 'waiting');
            } 
        }
        
        $query->orderBy('created_at', 'desc');
    
        $data = $query->get();
    
        $pdf = Pdf::loadView('pdf.laporan', compact('data'))->setPaper('a4', 'portrait');
        return $pdf->stream('laporan-transaksi.pdf');
    }
}
