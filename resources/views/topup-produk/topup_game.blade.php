@extends('layouts.homedash')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-center">Top Up Produk Game (VIPayment)</h1>
    <form action="/topup-game" method="POST" id="topupForm" class="space-y-4">
        @csrf

        <div>
            <label for="brand" class="block font-medium mb-1">Game</label>
            <select name="brand" id="brand" class="select2 w-full p-2 border rounded-md">
                <option value="0" selected disabled>Pilih Game</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}">{{ $brand }}</option>
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
            <input type="text" name="buyer_sku_code" id="buyer_sku_code" class="w-full p-2 border rounded-md" readonly required>
        </div>

        <div>
            <label for="price" class="block font-medium mb-1">Harga Produk</label>
            <input type="text" name="price" id="price" class="w-full p-2 border rounded-md" readonly required>
        </div>

        <div>
            <label for="profit" class="block font-medium mb-1">Keuntungan</label>
            <select name="profit" id="profit" class="w-full p-2 border rounded-md">
                <option value="0" selected>Pilih Keuntungan</option>
                @for ($i = 1000; $i <= 10000; $i += 1000)
                    <option value="{{ $i }}">{{ number_format($i, 0, ',', '.') }}</option>
                @endfor
            </select>
        </div>

        <div>
            <label for="harga_jual" class="block font-medium mb-1">Harga Jual</label>
            <input type="text" name="harga_jual" id="harga_jual" class="w-full p-2 border rounded-md bg-gray-100" readonly>
        </div>


        <div>
            <label for="data_no" class="block font-medium mb-1">ID Game / Data No</label>
            <input type="text" name="data_no" id="data_no" class="w-full p-2 border rounded-md" placeholder="Masukkan ID akun / user ID" required>
        </div>
        
        <div>
            <label for="data_zone" class="block font-medium mb-1">ID Zone (Opsional)</label>
            <input type="text" name="data_zone" id="data_zone" class="w-full p-2 border rounded-md" placeholder="Contoh: 2685">
            <p class="text-sm text-gray-500 mt-1">Jika game membutuhkan ID Zone (seperti Mobile Legends), silakan diisi.</p>
        </div>

        <div>
            <button type="button" id="btnCekNickname" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-green-700 transition">
                Cek Nickname
            </button>
            <p id="nicknameResult" class="mt-2 text-sm text-blue-600 font-semibold hidden"></p>
        </div>

        <div>
            <label for="server" class="block font-medium mb-1">Server</label>
            <input type="text" name="server" id="server" class="w-full p-2 border rounded-md" readonly required>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
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
    $(document).ready(function () {
        $('#brand').select2({
            placeholder: "Pilih Game",
            allowClear: true,
            width: '100%',
        });
    });
    
    const games = [
        @foreach($games as $game)
        {
            brand: "{{ $game->category }}",
            product_name: "{{ $game->product_name }}",
            buyer_sku_code: "{{ $game->buyer_sku_code }}",
            price: "{{ $game->price }}",
            server: "{{ $game->server }}"
        },
        @endforeach
    ];

    // Saat brand (kategori game) dipilih
    $('#brand').on('change', function () {
        const selectedBrand = $(this).val();
        const filtered = games.filter(g => g.brand === selectedBrand);
        
        // Reset semua input dan select (kecuali brand itu sendiri)
    $('#topupForm').find('input:not([name=brand]), select:not(#brand)').val('').trigger('change');
        
        $('#product_name').html('<option value="" selected disabled>Pilih Nama Produk</option>');
        filtered.forEach(item => {
            $('#product_name').append(`<option value="${item.buyer_sku_code}" data-name="${item.product_name}">${item.product_name}</option>`);
        });

        // Kosongkan input lainnya
        $('#buyer_sku_code').val('');
        $('#price').val('');
        $('#server').val('');
        
        // Penambahan untuk tombol cek nickname
        if (gameCodeMapping.hasOwnProperty(selectedBrand)) {
            $('#btnCekNickname').removeClass('hidden');
        } else {
            $('#btnCekNickname').addClass('hidden');
        }

        // Kosongkan hasil nickname
        $('#nicknameResult').addClass('hidden').text('');
        });

    // Saat nama produk dipilih
    $('#product_name').on('change', function () {
        const selectedSKU = $(this).val();
        const product = games.find(g => g.buyer_sku_code === selectedSKU);
        if (product) {
            $('#buyer_sku_code').val(product.buyer_sku_code);
            $('#price').val(product.price);
            $('#server').val(product.server);
        }
    });
    
    function updateHargaJual() {
        const hargaProduk = parseInt($('#price').val()) || 0;
        const keuntungan = parseInt($('#profit').val()) || 0;
        const total = hargaProduk + keuntungan;
        $('#harga_jual').val(total.toLocaleString('id-ID'));
    }
    
    // Saat user memilih keuntungan
    $('#profit').on('change', function () {
        updateHargaJual();
    });
    
    // Update juga saat harga produk berubah
    $('#product_name').on('change', function () {
        const selectedSKU = $(this).val();
        const product = games.find(g => g.buyer_sku_code === selectedSKU);
        if (product) {
            $('#buyer_sku_code').val(product.buyer_sku_code);
            $('#price').val(product.price);
            $('#server').val(product.server);
            updateHargaJual(); // Tambahkan ini agar harga jual ikut terupdate
        }
    });
    
    const gameCodeMapping = {
        'Mobile Legends A': 'mobile-legends',
        'Mobile Legends B': 'mobile-legends',
        'Mobile Legends Gift': 'mobile-legends',
        'Mobile Legends (Brazil)': 'mobile-legends',
        'Mobile Legends (Global)': 'mobile-legends',
        'Mobile Legends (Malaysia)': 'mobile-legends',
        'Mobile Legends (Philippines)': 'mobile-legends',
        'Mobile Legends (Russia)': 'mobile-legends',
        'Mobile Legends (Singapore)': 'mobile-legends',
        'Lords Mobile': 'lords-mobile',
        'Ragnarok M Eternal Love (SEA)': 'ragnarok-m-eternal-love-big-cat-coin',
        'Valorant': 'valorant',
        'Dragon Raja': 'dragon-raja',
        'League of Legends': 'league-of-legends-wild-rift',
        'Free Fire': 'free-fire',
        'Free Fire Global': 'free-fire',
        'Free Fire Max': 'free-fire-max',
        'Tom and Jerry Chase': 'tom-and-jerry-chase',
        'Arena of Valor': 'arena-of-valor',
        'Call Of Duty Mobile (Indonesia)': 'call-of-duty-mobile',
        'Genshin Impact': 'genshin-impact',
    };

    
    $('#btnCekNickname').click(function () {
    const brand = $('#brand').val().trim();
    const code = gameCodeMapping[brand];
    
    if (!code) {
        console.warn('Mapping tidak ditemukan untuk:', `"${brand}"`);
        alert('Game tidak ditemukan dalam daftar kode. Silakan periksa mapping.');
        return;
    }

    const userId = $('#data_no').val();
    const zoneId = $('#data_zone').val();

    if (!code || !userId) {
        alert('Mohon pilih game dan isi ID Game terlebih dahulu.');
        return;
    }

    $.ajax({
            url: '{{ route('cek.nickname') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                code: code,
                user_id: userId,
                zone_id: zoneId
            },
            beforeSend: function () {
                $('#nicknameResult').removeClass('hidden').text('Sedang mengecek...');
            },
            success: function (res) {
                if (res.result === true) {
                    $('#nicknameResult').text('Nickname: ' + res.data);
                } else {
                    $('#nicknameResult').text('Gagal cek nickname: ' + res.message);
                }
            },
            error: function (xhr, status, error) {
                $('#nicknameResult').text('Terjadi kesalahan saat menghubungi server.');
                console.log('Status:', xhr.status);           // Kode HTTP, misalnya 500
                console.log('Status Text:', xhr.statusText);  // "Internal Server Error"
                console.log('Response Text:', xhr.responseText);
            }
        });
    });
</script>
@endsection

@push('style')
<style>
/* Samakan styling Select2 dengan Tailwind */
.select2-container--default .select2-selection--single {
    border: 1px solid #d1d5db; /* border-gray-300 */
    border-radius: 0.375rem; /* rounded-md */
    height: 2.5rem; /* h-10 */
    padding-left: 0.3rem; /* px-3 */
    display: flex;
    align-items: center; /* vertikal tengah */
    background-color: white;
}

/* Rendered text (termasuk placeholder) */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #000000 !important; /* text-gray-500 untuk placeholder */
    line-height: 2rem;
    font-size: 1.0rem; /* text-sm */
}

/* Dropdown arrow posisi */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    right: 0.75rem; /* pr-3 */
    top: 0.5rem;
    position: absolute;
}
</style>


