@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Riwayat Transaksi Top Up</h2>
    
    <div class="mb-4">
    <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
        placeholder="Cari produk atau nomor..."
        class="border border-gray-300 px-4 py-2 rounded w-full max-w-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>
    
    <button id="btnCetakPdf" class="bg-gray-800 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-2 mb-4">
        Cetak PDF
    </button>
    <button id="refreshButton"
        class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
        🔄 Refresh
    </button>

    <div id="popupCetak" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 shadow-md w-full max-w-md relative">
            <h2 class="text-center text-lg font-semibold mb-4">Filter Cetak Laporan</h2>
            <form id="formCetak" method="GET" action="{{ secure_url('/laporan/cetak') }}" target="_blank">
                <div class="mb-4">
                    <label class="block font-bold">Tanggal:</label>
                    <input type="date" name="tanggal" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block font-bold">Status:</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="">Pilih Disini</option>
                        <option value="Sukses">Sukses</option>
                        <option value="Pending">Pending</option>
                        <option value="Gagal">Gagal</option>
                        <!-- atau generate dari controller -->
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Cetak PDF</button>
                    <button type="button" id="closePopup" class="px-4 py-2 bg-gray-500 text-black rounded-lg">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Customer No</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th> 
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($transaksi as $trx)
                    <tr>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $trx->product_name }}</td>
                        <td class="px-4 py-2">{{ $trx->customer_no }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($trx->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">
                                @if ($trx->status == 'Sukses' || $trx->status == 'success')
                                    <span class="font-semibold" style="color:green">
                                        Sukses
                                    </span>
                                @elseif ($trx->status == 'Gagal' || $trx->status == 'error' || $trx->status == 'gagal' )
                                    <span class="font-semibold" style="color:red">
                                        Gagal
                                    </span>
                                @elseif ($trx->status == 'Pending' || $trx->status == 'waiting')
                                    <span class="font-semibold" style="color:orange">
                                        Menunggu
                                    </span>
                                @else
                                    <span class="font-semibold" style="color:black">
                                        {{  $trx->status }}
                                    </span>
                                @endif
                        </td>
                        <td class="px-4 py-2">
                            <button 
                                class="bg-blue-500 hover:bg-blue-600 text-black px-3 py-1 rounded text-xs detail-btn"
                                data-id='{{ $trx->id }}'>
                                Detail
                            </button>
                        </td>                       
                    </tr>
                @endforeach
            </tbody>
            
            {{-- Modal popup untuk detail transaksi --}}
            <div id="detailModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white w-full max-w-lg">
                    <div class="p-4" id="detailContent">
                        Loading...
                    </div>
                    <div class="flex justify-center px-2 py-2">
                        <button type="button" id="closeModal" 
                            class="bg-gray-800 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </table>
        
</div>
@endsection

@section('script')
<script>
    const searchInput = document.getElementById('searchInput');

    let timeout = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            const keyword = searchInput.value.trim();
            const params = new URLSearchParams(window.location.search);
            if (keyword) {
                params.set('search', keyword);
            } else {
                params.delete('search');
            }
            params.delete('page'); // reset ke halaman pertama
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }, 1000); // debounce 1 detik
    });
    
    document.getElementById('btnCetakPdf').addEventListener('click', function () {
        document.getElementById('popupCetak').classList.remove('hidden');
    });

    document.getElementById('closePopup').addEventListener('click', function () {
        document.getElementById('popupCetak').classList.add('hidden');
    });
    
    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('detailModal').classList.add('hidden');
    });
    
    document.addEventListener('click', function (event) {
        const popup = document.getElementById('popupCetak');
        const popupContent = popup.querySelector('div'); // ambil konten dalam popup
    
        if (!popup.classList.contains('hidden') && !popupContent.contains(event.target) && event.target !== document.getElementById('btnCetakPdf')) {
            popup.classList.add('hidden');
        }
    });
    
    document.addEventListener('click', function (event) {
        const popup = document.getElementById('detailModal');
        const popupContent = popup.querySelector('div'); // ambil konten dalam popup
    
        if (!popup.classList.contains('hidden') && !popupContent.contains(event.target) && event.target !== document.getElementById('closeModal')) {
            popup.classList.add('hidden');
        }
    });
    
    document.getElementById('refreshButton').addEventListener('click', function () {
        location.reload(); // reload halaman
    });
    
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('detailModal');
        const closeModal = document.getElementById('closeModal');
        const detailContent = document.getElementById('detailContent');
    
        document.querySelectorAll('.detail-btn').forEach(button => {
            button.addEventListener('click', function () {
                let id = this.getAttribute('data-id');
    
                fetch(`/transaksi/${id}/detail`)
                    .then(res => res.text())
                    .then(html => {
                        detailContent.innerHTML = html;
                        modal.classList.remove('hidden');
                    });
            });
        });
    
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    
        // Klik luar modal untuk close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endsection

