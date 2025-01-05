<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Data Kondisi Rumah</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Tailwind CSS auto-generated styles here */
            </style>
        @endif
    </head>
    <body class="bg-blue-100 ">

        <nav class="fixed z-10 w-[calc(100%-16rem)] bg-blue-300 border-b border-blue-300 shadow-sm ml-64">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex items-center justify-center h-16">
                    <h1 class="font-semibold text-center text-blue-950">
                        DATA REGISTRASI SOSIAL EKONOMI KELURAHAN SENDANGARUM
                    </h1>
                </div>
            </div>
        </nav>

        <div class="flex min-h-screen pt-20">
            <!-- Navbar -->
            <div class="w-64">
                @include('components.sidebar')
            </div>

            <div class="relative flex-1 px-8 rounded-2xl">
                <!-- Header Section -->
                <div class="relative flex flex-col px-4 text-blue-950">
                    <!-- Judul -->
                    <h1 class="mb-4 text-xl font-semibold whitespace-nowrap">Data Kependudukan</h1>

                    <!-- Success Message -->
                    @if(session()->has('success'))
                        <script>
                            alert("{{ session('success') }}");
                        </script>
                        <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if ($errors->any())
                        <script>
                            alert("Terjadi kesalahan. Silakan cek kembali input Anda.");
                        </script>
                        <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded">
                            <h4 class="font-bold">Terjadi Kesalahan:</h4>
                            <ul class="mt-2 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Data Kependudukan Form -->
                    <div class="w-full p-1 mx-auto mb-8 ">
                        <form id="userForm" action="{{ route('bangunan.store') }}" method="POST" class="space-y-3">
                            @csrf

                            <div class="grid grid-cols-1 gap-6">

                                <!-- NomorKK & NamaKK -->
                                <div x-data="dropdownSearch({{ $noKK }}, 'selectedNomorKK', 'NamaKepalaKeluarga')" class="grid grid-cols-2 gap-4">
                                    <!-- NomorKK Input -->
                                    <div class="mb-4">
                                        <label for="NomorKK" class="block mb-2 text-xs font-medium text-black">Nomor Kartu Keluarga</label>
                                        <div class="relative">
                                            <input
                                                id="NomorKK"
                                                type="text"
                                                placeholder="Cari NomorKK..."
                                                x-model="search"
                                                @focus="open = true"
                                                @click.away="open = false"
                                                @keydown.escape="open = false"
                                                class="block w-full px-4 py-2 text-sm border border-gray-400 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            />
                                            <div
                                                x-show="open && filteredOptions.length > 0"
                                                class="absolute z-10 w-full mt-1 overflow-y-auto bg-white border border-gray-300 rounded shadow max-h-48"
                                                style="display: none;"
                                            >
                                                <template x-for="option in filteredOptions" :key="option.NomorKK">
                                                    <div
                                                        @click="selectOption(option)"
                                                        class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-500 hover:text-white"
                                                    >
                                                        <span x-text="option.NomorKK"></span>
                                                    </div>
                                                </template>
                                                <div
                                                    x-show="filteredOptions.length === 0"
                                                    class="px-4 py-2 text-sm text-gray-500"
                                                >
                                                    Tidak ada hasil.
                                                </div>
                                            </div>
                                            @error('NomorKK')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="NomorKK" :value="selectedNomorKK">
                                    </div>

                                    <!-- Nama Kepala Keluarga -->
                                    <div class="mb-4">
                                        <label for="NamaKepalaKeluarga" class="block mb-2 text-xs font-medium text-black">Nama Kepala Keluarga</label>
                                        <input
                                            id="NamaKepalaKeluarga"
                                            type="text"
                                            x-model="NamaKepalaKeluarga"
                                            readonly
                                            class="block w-full px-4 py-2 text-sm bg-gray-100 border border-gray-400 rounded focus:outline-none"
                                        />
                                        @error('NamaKepalaKeluarga')
                                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="NamaKepalaKeluarga" :value="NamaKepalaKeluarga">
                                </div>


                                <!-- Kabupaten/Kota & NomorUrt -->
                                <div x-data="{ statusKepemilikan: '', isBuktiKepemilikanEnabled: false }" class="grid grid-cols-2 gap-4">
                                    <!-- Dropdown Status Kepemilikan Bangunan -->
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="StatusKepemilikanBangunan" class="block mb-2 text-xs font-medium text-black">
                                                Status Kepemilikan Bangunan Tempat Tinggal yang Ditempati
                                            </label>
                                            <select
                                                id="StatusKepemilikanBangunan"
                                                name="StatusKepemilikanBangunan"
                                                x-model="statusKepemilikan"
                                                @change="isBuktiKepemilikanEnabled = (statusKepemilikan === 'Milik sendiri')"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" disabled selected>Pilih Status Kepemilikan</option>
                                                <option value="Milik sendiri">1. Milik sendiri</option>
                                                <option value="Kontrak/sewa">2. Kontrak/Sewa</option>
                                                <option value="Bebas Sewa">3. Bebas Sewa</option>
                                                <option value="Dinas">4. Dinas</option>
                                                <option value="Lainnya">5. Lainnya</option>
                                            </select>
                                            @error('StatusKepemilikanBangunan')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Dropdown Bukti Kepemilikan -->
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="BuktiKepemilikan" class="block mb-2 text-xs font-medium text-black">
                                                Bukti Kepemilikan Tanah Bangunan Tempat Tinggal? <i> (*Jika Milik Sendiri)</i>
                                            </label>
                                            <select
                                                id="BuktiKepemilikan"
                                                name="BuktiKepemilikan"
                                                x-bind:disabled="!isBuktiKepemilikanEnabled"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600 disabled:bg-gray-200 disabled:cursor-not-allowed"
                                            >
                                                <option value="" disabled selected>Pilih Bukti Kepemilikan</option>
                                                <option value="SHM atas nama anggota keluarga">1. SHM atas nama anggota keluarga</option>
                                                <option value="SHM bukan anggota keluarga dengan perjanjian pemanfaatan tertulis">2. SHM bukan anggota keluarga dengan perjanjian pemanfaatan tertulis</option>
                                                <option value="SHM bukan a.n anggota keluarga tanpa perjanjian pemanfaatan tertulis">3. SHM bukan a.n anggota keluarga tanpa perjanjian pemanfaatan tertulis</option>
                                                <option value="Sertifikat selain SHM(SHGB, SHSRS)">4. Sertifikat selain SHM(SHGB, SHSRS)</option>
                                                <option value="Surat Bukti Lainnya (Girik,Letter C, dll)">4. Surat Bukti Lainnya (Girik,Letter C, dll)</option>
                                                <option value="Tidak punya">5. Tidak punya</option>
                                            </select>
                                            @error('BuktiKepemilikan')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Kecamatan & Nomor Urut Keluarga Hasil Verifikasi -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="LuasLantai" class="block mb-2 text-xs font-medium text-black">
                                                Luas Lantai Bangunan Tempat Tinggal (m2)
                                            </label>
                                            <input
                                                type="text"
                                                id="LuasLantai"
                                                name="LuasLantai"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('LuasLantai')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="JenisLantai" class="block mb-2 text-xs font-medium text-black">
                                                Jenis Lantai Terluas
                                            </label>
                                            <select
                                                id="JenisLantai"
                                                name="JenisLantai"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" disabled selected>Pilih Jenis Lantai</option>
                                                <option value="Marmer/granit">1. Marmer/granit</option>
                                                <option value="Keramik">2. Keramik</option>
                                                <option value="Parket/vinil/karpet">3. Parket/vinil/karpet</option>
                                                <option value="Ubin/tegel/teraso">4. Ubin/tegel/teraso</option>
                                                <option value="Kayu/papan">5. Kayu/papan</option>
                                                <option value="Semen/bata merah">6. Semen/bata merah</option>
                                                <option value="Bambu">7. Bambu</option>
                                                <option value="Tanah">8. Tanah</option>
                                                <option value="Lainnya">9. Lainnya</option>
                                            </select>
                                            @error('JenisLantai')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <!-- Desa/Kelurahan & Status Keluarga -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="JenisDindingTerluas" class="block mb-2 text-xs font-medium text-black">
                                                Jenis Dinding Terluas
                                            </label>
                                            <select
                                                id="JenisDindingTerluas"
                                                name="JenisDindingTerluas"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" disabled selected>Pilih Jenis Dinding</option>
                                                <option value="Tembok">1. Tembok</option>
                                                <option value="Plesteran anyaman bambu/kawat">2. Plesteran anyaman bambu/kawat</option>
                                                <option value="Kayu/papan/gypsum/GRC/calciboard">3. Kayu/papan/gypsum/GRC/calciboard</option>
                                                <option value="Anyaman bambu">4. Anyaman bambu</option>
                                                <option value="Batang kayu">5. Batang kayu</option>
                                                <option value="Bambu">6. Bambu</option>
                                                <option value="Lainnya">7. Lainnya</option>
                                            </select>
                                            @error('JenisDindingTerluas')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="JenisAtapTerluas" class="block mb-2 text-xs font-medium text-black">
                                                Jenis Atap Terluas
                                            </label>
                                            <select
                                                id="JenisAtapTerluas"
                                                name="JenisAtapTerluas"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" disabled selected>Pilih Jenis Atap</option>
                                                <option value="Beton">1. Beton</option>
                                                <option value="Genteng">2. Genteng</option>
                                                <option value="Seng">3. Seng</option>
                                                <option value="Asbes">4. Asbes</option>
                                                <option value="Bambu">5. Bambu</option>
                                                <option value="Kayu">6. Kayu</option>
                                                <option value="Jerami/ijuk/daun-daunan/rumbia">7. Jerami/ijuk/daun-daunan/rumbia</option>
                                                <option value="Lainnya">8. Lainnya</option>
                                            </select>
                                            @error('JenisAtapTerluas')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <!-- Padukuhan & Jumlah Anggota Keluarga -->
                                <div x-data="{ isJarakDisabled: true, sumberAirMinum: '' }" class="grid grid-cols-2 gap-4">
                                    <!-- Sumber Air Minum -->
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="SumberAirMinum" class="block mb-2 text-xs font-medium text-black">
                                                Sumber Air Minum Utama
                                            </label>
                                            <select
                                                id="SumberAirMinum"
                                                name="SumberAirMinum"
                                                x-model="sumberAirMinum"
                                                @change="isJarakDisabled = !['Sumur bor/pompa', 'Sumur terlindung', 'Sumur tak terlindung', 'Mata air terlindung', 'Mata air tak terlindung'].includes(sumberAirMinum)"
                                                class="block w-full p-1 px-4 py-2 text-xs placeholder-gray-400 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Sumber Air Minum</option>
                                                <option value="Air kemasan bermerk">1. Air kemasan bermerk</option>
                                                <option value="Air isi ulang">2. Air isi ulang</option>
                                                <option value="Ledeng">3. Ledeng</option>
                                                <option value="Sumur bor/pompa">4. Sumur bor/pompa</option>
                                                <option value="Sumur terlindung">5. Sumur terlindung</option>
                                                <option value="Sumur tak terlindung">6. Sumur tak terlindung</option>
                                                <option value="Mata air terlindung">7. Mata air terlindung</option>
                                                <option value="Mata air tak terlindung">8. Mata air tak terlindung</option>
                                                <option value="Air permukaan(sungai/danau/waduk)">9. Air permukaan (sungai/danau/waduk)</option>
                                                <option value="Air hujan">10. Air hujan</option>
                                                <option value="Lainnya">11. Lainnya</option>
                                            </select>
                                            @error('SumberAirMinum')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Jarak Sumber Air Minum -->
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="JarakSumberAirMinum" class="block mb-2 text-xs font-medium text-black">
                                                Jarak Sumber Air Minum <i> (*Jika Sumber Air Minum Utama Berkode 4, 5, 6, 7, atau 8) </i>
                                            </label>
                                            <select
                                                id="JarakSumberAirMinum"
                                                name="JarakSumberAirMinum"
                                                x-bind:disabled="isJarakDisabled"
                                                class="block w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600 disabled:bg-gray-200 disabled:cursor-not-allowed"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Jarak Sumber Air Minum</option>
                                                <option value="<10 Meter">1. < 10 Meter</option>
                                                <option value=">10 Meter">2. > 10 Meter</option>
                                                <option value="Tidak Tahu">3. Tidak Tahu</option>
                                            </select>
                                            @error('JarakSumberAirMinum')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>



                                <!-- Kode SLS/Non SLS & ID Landmark Wilkerstat -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="SumberPeneranganUtama" class="block mb-2 text-xs font-medium text-black">
                                                Sumber Penerangan Utama
                                            </label>
                                            <select
                                                id="SumberPeneranganUtama"
                                                name="SumberPeneranganUtama"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                onchange="toggleMeteranInputs()"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Sumber Penerangan Utama</option>
                                                <option value="Listrik PLN dengan meteran">1. Listrik PLN dengan meteran</option>
                                                <option value="Listrik PLN tanpa meteran">2. Listrik PLN tanpa meteran</option>
                                                <option value="Listrik Non PLN">3. Listrik Non PLN</option>
                                                <option value="Bukan Listrik">4. Bukan Listrik</option>
                                            </select>
                                            @error('SumberPeneranganUtama')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="p-2 rounded bg-blue-50">
                                        <h3 class="mb-2 text-xs font-medium text-black">Daya Yang Terpasang <i> (*Jika Sumber Penerangan Utama Berkode 1) </i></h3>
                                        <div class="grid gap-4 sm:grid-cols-3">
                                            <!-- Meteran 1 -->
                                            <div class="flex flex-col items-center">
                                                <label for="Meteran1" class="mb-1 text-xs font-medium text-black">Meteran 1</label>
                                                <select
                                                    id="Meteran1"
                                                    name="Meteran1"
                                                    class="w-full px-4 py-2 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-500 focus:border-blue-500 disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                    disabled
                                                >
                                                    <option value="" disabled selected>Pilih Daya</option>
                                                    <option value="450 watt">1. 450 watt</option>
                                                    <option value="900 watt">2. 900 watt</option>
                                                    <option value="1.300 watt">3. 1.300 watt</option>
                                                    <option value="2.200 watt">4. 2.200 watt</option>
                                                    <option value=">2.200 watt">5. >2.200 watt</option>
                                                </select>
                                            </div>

                                            <!-- Meteran 2 -->
                                            <div class="flex flex-col items-center">
                                                <label for="Meteran2" class="mb-1 text-xs font-medium text-black">Meteran 2</label>
                                                <select
                                                    id="Meteran2"
                                                    name="Meteran2"
                                                    class="w-full px-4 py-2 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-500 focus:border-blue-500 disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                    disabled
                                                >
                                                    <option value="" disabled selected>Pilih Daya</option>
                                                    <option value="450 watt">1. 450 watt</option>
                                                    <option value="900 watt">2. 900 watt</option>
                                                    <option value="1.300 watt">3. 1.300 watt</option>
                                                    <option value="2.200 watt">4. 2.200 watt</option>
                                                    <option value=">2.200 watt">5. >2.200 watt</option>
                                                </select>
                                            </div>

                                            <!-- Meteran 3 -->
                                            <div class="flex flex-col items-center">
                                                <label for="Meteran3" class="mb-1 text-xs font-medium text-black">Meteran 3</label>
                                                <select
                                                    id="Meteran3"
                                                    name="Meteran3"
                                                    class="w-full px-4 py-2 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-500 focus:border-blue-500 disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                    disabled
                                                >
                                                    <option value="" disabled selected>Pilih Daya</option>
                                                    <option value="450 watt">1. 450 watt</option>
                                                    <option value="900 watt">2. 900 watt</option>
                                                    <option value="1.300 watt">3. 1.300 watt</option>
                                                    <option value="2.200 watt">4. 2.200 watt</option>
                                                    <option value=">2.200 watt">5. >2.200 watt</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bahan Bakar/Energi Utama Untuk Memasak & Tempat Pembuangan Akhir Tinja -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="BahanBakarEnergiMemasak" class="block mb-2 text-xs font-medium text-black">
                                                Bahan Bakar/Energi Utama Untuk Memasak
                                            </label>
                                            <select
                                                id="BahanBakarEnergiMemasak"
                                                name="BahanBakarEnergiMemasak"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Bahan Bakar/Energi</option>
                                                <option value="Listrik">1. Listrik</option>
                                                <option value="Gas Elpiji 5 kg/blue gas">2. Gas Elpiji 5 kg/blue gas</option>
                                                <option value="Gas Elpiji 12 kg">3. Gas Elpiji 12 kg</option>
                                                <option value="Gas Elpiji 3 kg">4. Gas Elpiji 3 kg</option>
                                                <option value="Gas kota/Meteran GPN">5. Gas Meteran GPN</option>
                                                <option value="Biogas">6. Biogas</option>
                                                <option value="Minyak Tanah">7. Minyak Tanah</option>
                                                <option value="Breket">8. Breket</option>
                                                <option value="Arang">9. Arang</option>
                                                <option value="Kayu Bakar">10. Kayu Bakar</option>
                                                <option value="Lainnya">11. Lainnya</option>
                                                <option value="Tidak Memasak dirumah">12. Tidak Memasak di rumah</option>
                                            </select>
                                            @error('BahanBakarEnergiMemasak')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="TempatPembuanganAkhirTinja" class="block mb-2 text-xs font-medium text-black">
                                                Tempat Pembuangan Akhir Tinja
                                            </label>
                                            <select
                                                id="TempatPembuanganAkhirTinja"
                                                name="TempatPembuanganAkhirTinja"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Tempat Pembuangan Akhir Tinja</option>
                                                <option value="Tangki septik">1. Tangki septik</option>
                                                <option value="IPAL">2. IPAL</option>
                                                <option value="Kolam/sawah/Sungai/danau/laut">3. Kolam/sawah/Sungai/danau/laut</option>
                                                <option value="Lubang tanah">4. Lubang tanah</option>
                                                <option value="Pantai/tanah lapang/kebun">5. Pantai/tanah lapang/kebun</option>
                                                <option value="Lainnya">6. Lainnya</option>
                                            </select>
                                            @error('TempatPembuanganAkhirTinja')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Kepemilikan dan Penggunaan Fasilitas Tempat Buang Air Besar & Jenis Kloset -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="KepemilikanBAB" class="block mb-2 text-xs font-medium text-black">
                                                Kepemilikan dan Penggunaan Fasilitas Tempat Buang Air Besar
                                            </label>
                                            <select
                                                id="KepemilikanBAB"
                                                name="KepemilikanBAB"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                onchange="toggleJenisKloset()"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Kepemilikan dan Penggunaan Fasilitas</option>
                                                <option value="Ada, digunakan hanya anggota keluarga sendiri">1. Ada, digunakan hanya anggota keluarga sendiri</option>
                                                <option value="Ada, digunakan bersama anggota keluarga dari keluarga tertentu">2. Ada, digunakan bersama anggota keluarga dari keluarga tertentu</option>
                                                <option value="Ada, di MCK komunal">3. Ada, di MCK komunal</option>
                                                <option value="Ada, di MCK Umum">4. Ada, di MCK Umum</option>
                                                <option value="Ada, Anggota keluarga tidak menggunakan">5. Ada, Anggota keluarga tidak menggunakan</option>
                                                <option value="Tidak Ada">6. Tidak Ada</option>
                                            </select>
                                            @error('KepemilikanBAB')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="JenisKloset" class="block mb-2 text-xs font-medium text-black">
                                                Jenis Kloset <i> (*Jika Kepemilikan Tempat Buang Air Besar Berkode 1, 2, atau 3) </i>
                                            </label>
                                            <select
                                                id="JenisKloset"
                                                name="JenisKloset"
                                                class="block w-full p-1 px-4 py-2 text-xs placeholder-gray-400 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                disabled
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Jenis Kloset</option>
                                                <option value="Leher Angsa">1. Leher Angsa</option>
                                                <option value="Plesengan dengan tutup">2. Plesengan dengan tutup</option>
                                                <option value="Plengsengan tanpa tutup">3. Plengsengan tanpa tutup</option>
                                                <option value="Cemplung/Bubluk">4. Cemplung/Bubluk</option>
                                            </select>
                                            @error('JenisKloset')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="flex justify-center pt-4">
                                <button type="submit"
                                    class="px-6 py-2 text-sm font-bold text-white transition duration-150 bg-blue-500 rounded-lg hover:bg-blue-600">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('dropdownSearch', (options, selectedModel, nameModel) => ({
                    options: options, // Data NomorKK dan Nama dari backend
                    search: '', // Input pencarian
                    open: false, // Status dropdown terbuka
                    [selectedModel]: '', // NomorKK yang dipilih
                    [nameModel]: '', // Nama Kepala Keluarga sesuai NomorKK yang dipilih

                    init() {
                        // Inisialisasi filteredOptions dengan semua data
                        this.filteredOptions = this.options;
                    },

                    get filteredOptions() {
                        // Filter data berdasarkan pencarian
                        if (this.search.trim() === '') {
                            return this.options; // Tampilkan semua data jika pencarian kosong
                        }
                        return this.options.filter(option => {
                            const nomorKK = option.NomorKK ? String(option.NomorKK).toLowerCase() : ''; // Pastikan tipe string
                            return nomorKK.includes(this.search.toLowerCase());
                        });
                    },

                    selectOption(option) {
                        // Pilih opsi, isi data ke model, lalu tutup dropdown
                        this[selectedModel] = option.NomorKK;
                        this[nameModel] = option.NamaKepalaKeluarga;
                        this.search = option.NomorKK; // Tampilkan NomorKK yang dipilih di input pencarian
                        this.open = false; // Tutup dropdown
                    }
                }));
            });


            function toggleMeteranInputs() {
                const sumberPenerangan = document.getElementById('SumberPeneranganUtama').value;
                const meteran1 = document.getElementById('Meteran1');
                const meteran2 = document.getElementById('Meteran2');
                const meteran3 = document.getElementById('Meteran3');

                if (sumberPenerangan === "Listrik PLN dengan meteran") {
                    meteran1.disabled = false;
                    meteran2.disabled = false;
                    meteran3.disabled = false;
                } else {
                    meteran1.disabled = true;
                    meteran2.disabled = true;
                    meteran3.disabled = true;
                }
            }

            function toggleJenisKloset() {
                const kepemilikanBAB = document.getElementById('KepemilikanBAB').value;
                const jenisKloset = document.getElementById('JenisKloset');

                                        // Enable or disable the Jenis Kloset dropdown based on KepemilikanBAB selection
                if (kepemilikanBAB === "Ada, digunakan hanya anggota keluarga sendiri" ||
                    kepemilikanBAB === "Ada, digunakan bersama anggota keluarga dari keluarga tertentu" ||
                    kepemilikanBAB === "Ada, di MCK komunal") {
                    jenisKloset.disabled = false;  // Enable dropdown
                } else {
                    jenisKloset.disabled = true;   // Disable dropdown
                }
            }
        </script>
    </body>

</html>
