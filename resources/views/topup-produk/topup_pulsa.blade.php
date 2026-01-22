@extends('layouts.homedash')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-center">Top Up Produk Pulsa</h1>
    <form action="/topup" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="brand" class="block font-medium mb-1">Brand</label>
            <select name="brand" id="brand" class="w-full p-2 border rounded-md">
                <option value="0" selected disabled>Pilih brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}" {{ session('operator') == $brand ? 'selected' : '' }}>
                        {{ $brand }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="product_name" class="block font-medium mb-1">Nama Produk</label>
            <select name="product_name" id="product_name" class="w-full p-2 border rounded-md">
                <option value="0" selected disabled>Pilih Nama Produk</option>
            </select>
        </div>

        <div>
            <label for="buyer_sku_code" class="block font-medium mb-1">Buyer SKU Code (kode produk)</label>
            <input type="text" name="buyer_sku_code" id="buyer_sku_code" class="w-full p-2 border rounded-md" required>
        </div>

        <div>
            <label for="price" class="block font-medium mb-1">Harga Produk</label>
            <input type="text" name="price" id="price" class="w-full p-2 border rounded-md" required>
        </div>

        <div>
            <label for="customer_no" class="block font-medium mb-1">Customer No</label>
            <input type="text" name="customer_no" id="customer_no" value="{{ session('nomor') }}" class="w-full p-2 border rounded-md" required>
        </div>

        <div>
            <label for="testing" class="block font-medium mb-1">Testing</label>
            <select name="testing" id="testing" class="w-full p-2 border rounded-md">
                <option value="true">True</option>
                <option value="false">False</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700 transition">
                Submit
            </button>
        </div>
    </form>
</div>
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil dibuat transaksi',
                text: 'Transaksi berhasil dibuat. Cek status di riwayat transaksi.',
                confirmButtonColor: '#16a34a',
                confirmButtonText: 'Tutup'
            });
        });
    </script>
@endif
@endsection

@section('script')
<script>
    const pulsa = [
        @foreach($pulsa as $item)
        {
            brand: "{{ $item->brand }}",
            product_name: "{{ $item->product_name }}",
            buyer_sku_code: "{{ $item->buyer_sku_code }}",
            price: "Rp{{ number_format($item->price, 0, ',', '.') }}",
        },
        @endforeach
    ]

    $('#brand').change(function () {
        const selected = $(this).val()
        const filtered = pulsa.filter(v => v.brand == selected)
        $('#product_name').html('<option value="0" selected disabled>Pilih Nama Produk</option>')
        filtered.forEach(item => {
            $('#product_name').append(`<option value="${ item.buyer_sku_code }">${ item.product_name } (${ item.price })</option>`)
        })
        $('#buyer_sku_code').val('')
        $('#price').val('')
    })
    
    // Jika session operator sudah terisi saat load halaman, jalankan filter otomatis
    $(document).ready(function () {
        // Cek apakah ada nilai brand dari session
        const currentBrand = "{{ session('operator') }}";
        if (currentBrand) {
            $('#brand').val(currentBrand).trigger('change');
    
            // Setelah brand di-set dan product list diisi, otomatis pilih produk pertama
            setTimeout(() => {
                const firstProduct = $('#product_name option').eq(1); // index 1 = produk pertama (karena index 0 = "Pilih")
                if (firstProduct.length) {
                    $('#product_name').val(firstProduct.val()).trigger('change');
                }
            }, 100); // Delay sedikit agar produk sempat di-render
        }
    })

    $('#product_name').change(function() {
        const code = $(this).val()
        const selected = pulsa.find(v => v.buyer_sku_code == code)
        $('#buyer_sku_code').val(selected.buyer_sku_code)
        $('#price').val(selected.price)
    })
    
    function closePopup() {
        document.getElementById('success-popup').classList.add('hidden');
    }
    
    $('#topup').submit(function(e) {
        e.preventDefault(); // Hindari reload
        const formData = $(this).serialize();
    
        $.ajax({
            url: '/topup',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.data && response.data.status) {
                    document.getElementById('success-popup').classList.remove('hidden');
                } else {
                    alert('Transaksi gagal atau tidak valid');
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat melakukan transaksi');
            }
        });
    });
</script>
@endsection
