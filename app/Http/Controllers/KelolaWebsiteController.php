<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Price;
use App\Models\Prefix;
use App\Models\User;

class KelolaWebsiteController extends Controller
{
    public function pengguna()
    {
        $search = request('search');

        $users = User::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('kelola-website.pengguna', compact('users'));
    }
    
    public function storePengguna(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    
        return redirect()->route('kelola-website.pengguna')->with('success', 'User berhasil ditambahkan.');
    }
    
    public function destroyPengguna($id)
    {
        $user = User::findOrFail($id);
    
        // Opsional: jangan sampai admin menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
    
        $user->delete();
    
        return redirect()->route('kelola-website.pengguna')->with('success', 'User berhasil dihapus.');
    }

    public function provider()
    {
        $digiflazzStatus = ['status' => 'gagal', 'color' => 'red'];
        $vipaymentStatus = ['status' => 'gagal', 'color' => 'red'];
    
        // Cek Digiflazz
        try {
            $response = \Http::timeout(5)->post('https://api.digiflazz.com/v1/price-list', [
                'cmd' => 'prepaid',
                'username' => env('DIGIFLAZZ_USERNAME'),
                'sign' => md5(env('DIGIFLAZZ_USERNAME') . env('DIGIFLAZZ_API_KEY') . 'prepaid'),
            ]);
    
            $data = $response->json();
    
            if ($response->successful() && isset($data['data'])) {
                $digiflazzStatus = ['status' => 'terhubung', 'color' => 'green'];
            }
        } catch (\Exception $e) {
            $digiflazzStatus = ['status' => 'gagal', 'color' => 'red'];
        }
    
        // Cek VIPayment
        try {
            $response = \Http::timeout(5)->post('https://vip-reseller.co.id/api/game-feature', [
                'key'  => env('VIP_KEY'),
                'sign' => md5(env('VIP_KEY') . env('VIP_SECRET')),
                'type' => 'services'
            ]);
    
            $data = $response->json();
    
            if ($response->successful() && isset($data['data'])) {
                $vipaymentStatus = ['status' => 'terhubung', 'color' => 'green'];
            }
        } catch (\Exception $e) {
            $vipaymentStatus = ['status' => 'gagal', 'color' => 'red'];
        }
    
        return view('kelola-website.provider', compact('digiflazzStatus', 'vipaymentStatus'));
    }



    public function prefix()
    {
        $search = request('search');

        $prefixes = Prefix::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('prefix', 'like', '%' . $search . '%')
                      ->orWhere('operator', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('operator', 'asc')
            ->paginate(25)
            ->withQueryString();
    
        return view('kelola-website.prefix', compact('prefixes'));
    }

    public function supplier()
    {
        $search = request('search');

        $suppliers = Price::where('category', 'Pulsa')
            ->select('product_name', 'seller_name', 'price')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', '%' . $search . '%')
                      ->orWhere('seller_name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('seller_name', 'asc')
            ->orderBy('price', 'asc')
            ->paginate(25)
            ->withQueryString();
    
        return view('kelola-website.supplier', compact('suppliers'));
    }
}
