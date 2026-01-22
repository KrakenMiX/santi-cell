<aside class="fixed top-0 left-0 w-64 h-screen bg-gray-800 text-white shadow-md">
    <div class="p-4 flex items-center justify-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">Santi Cell Dashboard</a>
    </div>
    <nav class="mt-8">
        <ul class="space-y-2">
            <li>
                {{-- {{ route('dashboard') }} --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Dropdown Maintenance -->
            @php
                $maintenanceRoutes = ['listjadwal', 'wallmount.index'];
                $isMaintenanceOpen = Route::is($maintenanceRoutes);
            @endphp

            <li>
                <a href="javascript:void(0);" onclick="toggleDropdown('maintenance-dropdown')"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    <span>Daftar Harga Produk</span>
                    <i class="fas fa-chevron-down ml-auto transition-transform transform rotate-chevron {{ $isMaintenanceOpen ? 'rotate-180' : '' }}"></i>
                </a>
                <ul id="maintenance-dropdown" class="ml-6 space-y-2 overflow-hidden transition-all duration-300 {{ $isMaintenanceOpen ? 'max-h-full' : 'max-h-0' }}">
                    <li>
                        <a href="{{ route('daftar-produk.game') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-calendar-day mr-3 text-sm"></i>
                            <span class="text-sm">Game</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('daftar-produk.pulsa') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-tools mr-3 text-sm"></i>
                            <span class="text-sm">Pulsa</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Dropdown Top Up -->
            @php
                $topUpRoutes = ['listjadwal', 'wallmount.index'];
                $isTopUpOpen = Route::is($topUpRoutes);
            @endphp
            <li>
                <a href="javascript:void(0);" onclick="toggleDropdown('topUp-dropdown')"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    <span>Top Up</span>
                    <i class="fas fa-chevron-down ml-auto transition-transform transform rotate-chevron {{ $isTopUpOpen ? 'rotate-180' : '' }}"></i>
                </a>
                <ul id="topUp-dropdown" class="ml-6 space-y-2 overflow-hidden transition-all duration-300 {{ $isTopUpOpen ? 'max-h-full' : 'max-h-0' }}">
                    <li>
                        <a href="{{ route('topup-game') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-calendar-day mr-3 text-sm"></i>
                            <span class="text-sm">Top Up Game</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('prefix.check') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-tools mr-3 text-sm"></i>
                            <span class="text-sm">Top Up Pulsa</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('profile') }}"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-user mr-3"></i>
                    <span>Profile Pengguna</span>
                </a>
            </li>
            <li>
                <a href="{{ route('riwayat-transaksi') }}"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-tags mr-3"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </li>
            @if(auth()->user()->role === 'admin')
            <!-- Dropdown Manajemen Asset -->
            @php
                $assetRoutes = ['barang', 'departemen', 'kategori', 'lokasi', 'penempatan', 'pengadaan', 'tambah-pengadaan',
                                ''];
                $isAssetOpen = Route::is($assetRoutes);
            @endphp

             <li>
                <a href="javascript:void(0);" onclick="toggleDropdown('asset-dropdown')"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer">
                    <i class="fas fa-boxes mr-3"></i>
                    <span>Kelola Website</span>
                    <i class="fas fa-chevron-down ml-auto transition-transform transform rotate-chevron {{ $isAssetOpen ? 'rotate-180' : '' }}"></i>
                </a>
                <ul id="asset-dropdown" class="ml-6 space-y-2 overflow-hidden transition-all duration-300 {{ $isAssetOpen ? 'max-h-full' : 'max-h-0' }}">
                <li>
                        <a href="{{ route('kelola-website.pengguna') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-building mr-3 text-sm"></i>
                            <span class="text-sm">Kelola Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelola-website.provider') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-map-marker-alt mr-3 text-sm"></i>
                            <span class="text-sm">Kelola Provider</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelola-website.prefix') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-box mr-3 text-sm"></i>
                            <span class="text-sm">Kelola Prefix</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelola-website.supplier') }}"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-shopping-cart mr-3 text-sm"></i>
                            <span class="text-sm">Kelola Supplier</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-warehouse mr-3 text-sm"></i>
                            <span class="text-sm">Data Penggunaan</span>
                        </a>
                    </li> --}}
                </ul>
                <!-- Dropdown Kelola Produk -->
                @php
                    $productRoutes = ['kelola-produk.game', 'kelola-produk.pulsa']; 
                    $isProductOpen = Route::is($productRoutes);
                @endphp
                
                <li>
                    <a href="javascript:void(0);" onclick="toggleDropdown('product-dropdown')"
                        class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer">
                        <i class="fas fa-box mr-3"></i>
                        <span>Kelola Produk</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform transform rotate-chevron {{ $isProductOpen ? 'rotate-180' : '' }}"></i>
                    </a>
                    <ul id="product-dropdown" class="ml-6 space-y-2 overflow-hidden transition-all duration-300 {{ $isProductOpen ? 'max-h-full' : 'max-h-0' }}">
                        <li>
                            <a href="{{ route('kelola-produk.game') }}"
                                class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fas fa-gamepad mr-3 text-sm"></i>
                                <span class="text-sm">Game</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelola-produk.pulsa') }}"
                                class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fas fa-mobile-alt mr-3 text-sm"></i>
                                <span class="text-sm">Pulsa</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                            </li>
                        </ul>
                    </nav>
</aside>

<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const chevron = dropdown.previousElementSibling.querySelector('.rotate-chevron');

        if (dropdown.style.maxHeight) {
            dropdown.style.maxHeight = null;
            chevron.classList.remove('rotate-180');
        } else {
            dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
            chevron.classList.add('rotate-180');
        }
    }
</script>
