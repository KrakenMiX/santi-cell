<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil nama user yang sedang login
        $userName = Auth::user()->name;

        // Kirimkan data ke view
        return view('dashboard', compact('userName'));
    }
}
