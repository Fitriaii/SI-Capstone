<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Data Kondisi Rumah</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-blue-100 ">

        @include('sweetalert::alert')

        <nav class="fixed z-10 w-[calc(100%-16rem)] bg-blue-300 border-b border-blue-300 shadow-sm ml-64">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex items-center justify-center h-16">
                    <h1 class="font-bold text-center text-white">
                        DATA REGISTRASI SOSIAL EKONOMI KELURAHAN SENDANGARUM
                    </h1>
                </div>
            </div>
        </nav>

        <div class="flex flex-col min-h-screen pt-20">
            <!-- Navbar -->
            <div class="flex min-h-screen">

                <aside class="w-64">
                    @include('components.sidebar')
                </aside>

                <div class="relative flex-1 px-8 rounded-2xl">
                    <!-- Header Section -->
                    <div class="relative flex flex-col px-4 text-blue-950">
                        <!-- Judul -->
                        <h1 class="mb-4 text-xl font-semibold whitespace-nowrap">Data Kondisi Rumah</h1>

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
                            <form id="userForm" action="{{ route('bangunan.update', $dataBangunan) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 gap-6">

                                    <!-- NomorKK & NamaKK -->
                                    <div
                                        x-data="dropdownSearch({{ $noKK }}, 'selectedNomorKK', 'NamaKepalaKeluarga', '{{ old('NomorKK', $previousNomorKK) }}', '{{ old('NamaKepalaKeluarga', $previousNamaKepalaKeluarga) }}')"
                                        class="grid grid-cols-2 gap-4"
                                    >
                                        <!-- NomorKK Input -->
                                        <div class="mb-4">
                                            <label for="NomorKK" class="block mb-2 text-xs font-medium text-black">Nomor Kartu Keluarga</label>
                                            <div class="relative">
                                                <input
                                                    id="NomorKK"
                                                    type="text"
                                                    placeholder="Cari Nomor Kartu Keluarga"
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
                                    <div
                                        x-data="{
                                            statusKepemilikan: '{{ old('StatusKepemilikanBangunan', $dataBangunan->StatusKepemilikanBangunan ?? '') }}',
                                            isBuktiKepemilikanEnabled: '{{ old('StatusKepemilikanBangunan', $dataBangunan->StatusKepemilikanBangunan ?? '') }}' === 'Milik sendiri'
                                        }"
                                        class="grid grid-cols-2 gap-4"
                                    >
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
                                                    <option value="" disabled>Pilih Status Kepemilikan</option>
                                                    <option value="Milik sendiri" :selected="statusKepemilikan === 'Milik sendiri'">1. Milik sendiri</option>
                                                    <option value="Kontrak/sewa" :selected="statusKepemilikan === 'Kontrak/sewa'">2. Kontrak/Sewa</option>
                                                    <option value="Bebas Sewa" :selected="statusKepemilikan === 'Bebas Sewa'">3. Bebas Sewa</option>
                                                    <option value="Dinas" :selected="statusKepemilikan === 'Dinas'">4. Dinas</option>
                                                    <option value="Lainnya" :selected="statusKepemilikan === 'Lainnya'">5. Lainnya</option>
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
                                                    Bukti Kepemilikan Tanah Bangunan Tempat Tinggal? <i> (*jika Milik Sendiri) </i>
                                                </label>
                                                <select
                                                    id="BuktiKepemilikan"
                                                    name="BuktiKepemilikan"
                                                    x-bind:disabled="!isBuktiKepemilikanEnabled"
                                                    class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600 disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                >
                                                    <option value="" disabled selected>Pilih Bukti Kepemilikan</option>
                                                    <option value="SHM atas nama anggota keluarga" {{ old('BuktiKepemilikan', $dataBangunan->BuktiKepemilikan ?? '') == 'SHM atas nama anggota keluarga' ? 'selected' : '' }}>1. SHM atas nama anggota keluarga</option>
                                                    <option value="SHM bukan anggota keluarga dengan perjanjian pemanfaatan tertulis" {{ old('BuktiKepemilikan', $dataBangunan->BuktiKepemilikan ?? '') == 'SHM bukan anggota keluarga dengan perjanjian pemanfaatan tertulis' ? 'selected' : '' }}>2. SHM bukan anggota keluarga dengan perjanjian pemanfaatan tertulis</option>
                                                    <option value="SHM bukan a.n anggota keluarga tanpa perjanjian pemanfaatan tertulis" {{ old('BuktiKepemilikan', $dataBangunan->BuktiKepemilikan ?? '') == 'SHM bukan a.n anggota keluarga tanpa perjanjian pemanfaatan tertulis' ? 'selected' : '' }}>3. SHM bukan a.n anggota keluarga tanpa perjanjian pemanfaatan tertulis</option>
                                                    <option value="Sertifikat selain SHM(SHGB, SHSRS)" {{ old('BuktiKepemilikan', $dataBangunan->BuktiKepemilikan ?? '') == 'Sertifikat selain SHM(SHGB, SHSRS)' ? 'selected' : '' }}>4. Sertifikat selain SHM(SHGB, SHSRS)</option>
                                                    <option value="Surat Bukti Lainnya (Girik,Letter C, dll)" {{ old('BuktiKepemilikan', $dataBangunan->BuktiKepemilikan ?? '') == 'Surat Bukti Lainnya (Girik,Letter C, dll)' ? 'selected' : '' }}>4. Surat Bukti Lainnya (Girik,Letter C, dll)</option>
                                                    <option value="Tidak punya" {{ old('BuktiKepemilikan', $dataBangunan->BuktiKepemilikan ?? '') == 'Tidak punya' ? 'selected' : '' }}>5. Tidak punya</option>
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
                                                    Luas Lantai Bangunan Tempat Tinggal (m^2)
                                                </label>
                                                <input
                                                    type="text"
                                                    id="LuasLantai"
                                                    name="LuasLantai"
                                                    value="{{ old('LuasLantai', isset($dataBangunan) ? $dataBangunan->LuasLantai : '') }}"
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
                                                    <option value="Marmer/granit" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Marmer/granit' ? 'selected' : '' }}>1. Marmer/granit</option>
                                                    <option value="Keramik" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Keramik' ? 'selected' : '' }} >2. Keramik</option>
                                                    <option value="Parket/vinil/karpet" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Parket/vinil/karpet' ? 'selected' : '' }}>3. Parket/vinil/karpet</option>
                                                    <option value="Ubin/tegel/teraso" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Ubin/tegel/teraso' ? 'selected' : '' }}>4. Ubin/tegel/teraso</option>
                                                    <option value="Kayu/papan" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Kayu/papan' ? 'selected' : '' }}>5. Kayu/papan</option>
                                                    <option value="Semen/bata merah" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Semen/bata merah' ? 'selected' : '' }}>6. Semen/bata merah</option>
                                                    <option value="Bambu" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Bambu' ? 'selected' : '' }}>7. Bambu</option>
                                                    <option value="Tanah" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Tanah' ? 'selected' : '' }}>8. Tanah</option>
                                                    <option value="Lainnya" {{ old('JenisLantai', isset($dataBangunan) ? $dataBangunan->JenisLantai : '') == 'Lainnya' ? 'selected' : '' }}>9. Lainnya</option>
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
                                                    <option value="Tembok" {{ old('JenisDindingTerluas', isset($dataBangunan) ? $dataBangunan->JenisDindingTerluas : '') == 'Tembok' ? 'selected' : '' }}>1. Tembok</option>
                                                    <option value="Plesteran anyaman bambu/kawat" {{ old('JenisDindingTerluas', isset($dataBangunan) ? $dataBangunan->JenisDindingTerluas : '') == 'Plesteran anyaman bambu/kawat' ? 'selected' : '' }}>2. Plesteran anyaman bambu/kawat</option>
                                                    <option value="Kayu/papan/gypsum/GRC/calciboard" {{ old('JenisDindingTerluas', isset($dataBangunan) ? $dataBangunan->JenisDindingTerluas : '') == 'Kayu/papan/gypsum/GRC/calciboard' ? 'selected' : '' }}>3. Kayu/papan/gypsum/GRC/calciboard</option>
                                                    <option value="Anyaman bambu" {{ old('JenisDindingTerluas', isset($dataBangunan) ? $dataBangunan->JenisDindingTerluas : '') == 'Anyaman bambu' ? 'selected' : '' }}>4. Anyaman bambu</option>
                                                    <option value="Batang kayu" {{ old('JenisDindingTerluas', isset($dataBangunan) ? $dataBangunan->JenisDindingTerluas : '') == 'Batang kayu' ? 'selected' : '' }}>5. Batang kayu</option>
                                                    <option value="Bambu" {{ old('JenisDindingTerluas', isset($dataBangunan) ? $dataBangunan->JenisDindingTerluas : '') == 'Bambu' ? 'selected' : '' }}>6. Bambu</option>
                                                    <option value="Lainnya" {{ old('JenisDindingTerluas', isset($dataBangunan) ? $dataBangunan->JenisDindingTerluas : '') == 'Lainnya' ? 'selected' : '' }}>7. Lainnya</option>
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
                                                    <option value="Beton" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Beton' ? 'selected' : '' }}>1. Beton</option>
                                                    <option value="Genteng" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Genteng' ? 'selected' : '' }}>2. Genteng</option>
                                                    <option value="Seng" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Seng' ? 'selected' : '' }}>3. Seng</option>
                                                    <option value="Asbes" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Asbes' ? 'selected' : '' }}>4. Asbes</option>
                                                    <option value="Bambu" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Bambu' ? 'selected' : '' }}>5. Bambu</option>
                                                    <option value="Kayu" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Kayu' ? 'selected' : '' }}>6. Kayu</option>
                                                    <option value="Jerami/ijuk/daun-daunan/rumbia" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Jerami/ijuk/daun-daunan/rumbia' ? 'selected' : '' }}>7. Jerami/ijuk/daun-daunan/rumbia</option>
                                                    <option value="Lainnya" {{ old('JenisAtapTerluas', isset($dataBangunan) ? $dataBangunan->JenisAtapTerluas : '') == 'Lainnya' ? 'selected' : '' }}>8. Lainnya</option>
                                                </select>
                                                @error('JenisAtapTerluas')
                                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Padukuhan & Jumlah Anggota Keluarga -->
                                    <div
                                        x-data="{
                                            sumberAirMinum: '{{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') }}',
                                            isJarakDisabled: !['Sumur bor/pompa', 'Sumur terlindung', 'Sumur tak terlindung', 'Mata air terlindung', 'Mata air tak terlindung'].includes('{{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') }}')
                                        }"
                                        class="grid grid-cols-2 gap-4"
                                    >
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
                                                    <option value="" class="text-gray-500" hidden>Pilih Sumber Air Minum</option>
                                                    <option value="Air kemasan bermerk" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Air kemasan bermerk' ? 'selected' : '' }}>1. Air kemasan bermerk</option>
                                                    <option value="Air isi ulang" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Air isi ulang' ? 'selected' : '' }}>2. Air isi ulang</option>
                                                    <option value="Ledeng" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Ledeng' ? 'selected' : '' }}>3. Ledeng</option>
                                                    <option value="Sumur bor/pompa" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Sumur bor/pompa' ? 'selected' : '' }}>4. Sumur bor/pompa</option>
                                                    <option value="Sumur terlindung" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Sumur terlindung' ? 'selected' : '' }}>5. Sumur terlindung</option>
                                                    <option value="Sumur tak terlindung" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Sumur tak terlindung' ? 'selected' : '' }}>6. Sumur tak terlindung</option>
                                                    <option value="Mata air terlindung" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Mata air terlindung' ? 'selected' : '' }}>7. Mata air terlindung</option>
                                                    <option value="Mata air tak terlindung" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Mata air tak terlindung' ? 'selected' : '' }}>8. Mata air tak terlindung</option>
                                                    <option value="Air permukaan(sungai/danau/waduk)" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Air permukaan(sungai/danau/waduk)' ? 'selected' : '' }}>9. Air permukaan (sungai/danau/waduk)</option>
                                                    <option value="Air hujan" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Air hujan' ? 'selected' : '' }}>10. Air hujan</option>
                                                    <option value="Lainnya" {{ old('SumberAirMinum', $dataBangunan->SumberAirMinum ?? '') == 'Lainnya' ? 'selected' : '' }}>11. Lainnya</option>
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
                                                    <option value="<10 Meter" {{ old('JarakSumberAirMinum', $dataBangunan->JarakSumberAirMinum ?? '') == '<10 Meter' ? 'selected' : '' }}>1. < 10 Meter</option>
                                                    <option value=">10 Meter" {{ old('JarakSumberAirMinum', $dataBangunan->JarakSumberAirMinum ?? '') == '>10 Meter' ? 'selected' : '' }}>2. > 10 Meter</option>
                                                    <option value="Tidak Tahu" {{ old('JarakSumberAirMinum', $dataBangunan->JarakSumberAirMinum ?? '') == 'Tidak Tahu' ? 'selected' : '' }}>3. Tidak Tahu</option>
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
                                                    data-initial-value="{{ old('SumberPeneranganUtama', isset($dataBangunan) ? $dataBangunan->SumberPeneranganUtama : '') }}"
                                                >
                                                    <option value="" class="text-gray-500" hidden>Pilih Sumber Penerangan Utama</option>
                                                    <option value="Listrik PLN dengan meteran" {{ old('SumberPeneranganUtama', isset($dataBangunan) ? $dataBangunan->SumberPeneranganUtama : '') == 'Listrik PLN dengan meteran' ? 'selected' : '' }}>1. Listrik PLN dengan meteran</option>
                                                    <option value="Listrik PLN tanpa meteran" {{ old('SumberPeneranganUtama', isset($dataBangunan) ? $dataBangunan->SumberPeneranganUtama : '') == 'Listrik PLN tanpa meteran' ? 'selected' : '' }}>2. Listrik PLN tanpa meteran</option>
                                                    <option value="Listrik Non PLN" {{ old('SumberPeneranganUtama', isset($dataBangunan) ? $dataBangunan->SumberPeneranganUtama : '') == 'Listrik Non PLN' ? 'selected' : '' }}>3. Listrik Non PLN</option>
                                                    <option value="Bukan Listrik" {{ old('SumberPeneranganUtama', isset($dataBangunan) ? $dataBangunan->SumberPeneranganUtama : '') == 'Bukan Listrik' ? 'selected' : '' }}>4. Bukan Listrik</option>
                                                </select>
                                                @error('SumberPeneranganUtama')
                                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="p-2 rounded bg-blue-50">
                                            <h3 class="mb-2 text-xs font-medium text-black">
                                                Daya Yang Terpasang <i>(*Jika Sumber Penerangan Utama Berkode 1)</i>
                                            </h3>
                                            <div class="grid gap-4 sm:grid-cols-3">
                                                <!-- Meteran 1 -->
                                                <div class="flex flex-col items-center">
                                                    <label for="Meteran1" class="mb-1 text-xs font-medium text-black">Meteran 1</label>
                                                    <select
                                                        id="Meteran1"
                                                        name="Meteran1"
                                                        class="w-full px-4 py-2 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-500 focus:border-blue-500"
                                                    >
                                                        <option value="" disabled selected>Pilih Daya</option>
                                                        <option value="450 watt" {{ old('Meteran1', isset($dataBangunan) ? $dataBangunan->Meteran1 : '') == '450 watt' ? 'selected' : '' }}>1. 450 watt</option>
                                                        <option value="900 watt" {{ old('Meteran1', isset($dataBangunan) ? $dataBangunan->Meteran1 : '') == '900 watt' ? 'selected' : '' }}>2. 900 watt</option>
                                                        <option value="1.300 watt" {{ old('Meteran1', isset($dataBangunan) ? $dataBangunan->Meteran1 : '') == '1.300 watt' ? 'selected' : '' }}>3. 1.300 watt</option>
                                                        <option value="2.200 watt" {{ old('Meteran1', isset($dataBangunan) ? $dataBangunan->Meteran1 : '') == '2.200 watt' ? 'selected' : '' }}>4. 2.200 watt</option>
                                                        <option value=">2.200 watt" {{ old('Meteran1', isset($dataBangunan) ? $dataBangunan->Meteran1 : '') == '>2.200 watt' ? 'selected' : '' }}>5. >2.200 watt</option>
                                                    </select>

                                                </div>

                                                <!-- Meteran 2 -->
                                                <div class="flex flex-col items-center">
                                                    <label for="Meteran2" class="mb-1 text-xs font-medium text-black">Meteran 2</label>
                                                    <select
                                                        id="Meteran2"
                                                        name="Meteran2"
                                                        class="w-full px-4 py-2 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-500 focus:border-blue-500"
                                                    >
                                                        <option value="" disabled selected>Pilih Daya</option>
                                                        <option value="450 watt" {{ old('Meteran2', isset($dataBangunan) ? $dataBangunan->Meteran2 : '') == '450 watt' ? 'selected' : '' }}>1. 450 watt</option>
                                                        <option value="900 watt" {{ old('Meteran2', isset($dataBangunan) ? $dataBangunan->Meteran2 : '' ) == '900 watt' ? 'selected' : '' }}>2. 900 watt</option>
                                                        <option value="1.300 watt" {{ old('Meteran2', isset($dataBangunan) ? $dataBangunan->Meteran2 : '') == '1.300 watt' ? 'selected' : '' }}>3. 1.300 watt</option>
                                                        <option value="2.200 watt" {{ old('Meteran2', isset($dataBangunan) ? $dataBangunan->Meteran2 : '') == '2.200 watt' ? 'selected' : '' }}>4. 2.200 watt</option>
                                                        <option value=">2.200 watt" {{ old('Meteran2', isset($dataBangunan) ? $dataBangunan->Meteran2 : '') == '>2.200 watt' ? 'selected' : '' }}>5. >2.200 watt</option>
                                                    </select>

                                                </div>

                                                <!-- Meteran 3 -->
                                                <div class="flex flex-col items-center">
                                                    <label for="Meteran3" class="mb-1 text-xs font-medium text-black">Meteran 3</label>
                                                    <select
                                                        id="Meteran3"
                                                        name="Meteran3"
                                                        class="w-full px-4 py-2 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-500 focus:border-blue-500"
                                                    >
                                                        <option value="" disabled selected>Pilih Daya</option>
                                                        <option value="450 watt" {{ old('Meteran3', isset($dataBangunan) ? $dataBangunan->Meteran3 : '') == '450 watt' ? 'selected' : '' }}>1. 450 watt</option>
                                                        <option value="900 watt" {{ old('Meteran3', isset($dataBangunan) ? $dataBangunan->Meteran3 : '') == '900 watt' ? 'selected' : '' }}>2. 900 watt</option>
                                                        <option value="1.300 watt" {{ old('Meteran3', isset($dataBangunan) ? $dataBangunan->Meteran3 : '') == '1.300 watt' ? 'selected' : '' }}>3. 1.300 watt</option>
                                                        <option value="2.200 watt" {{ old('Meteran3', isset($dataBangunan) ? $dataBangunan->Meteran3 : '') == '2.200 watt' ? 'selected' : '' }}>4. 2.200 watt</option>
                                                        <option value=">2.200 watt" {{ old('Meteran3', isset($dataBangunan) ? $dataBangunan->Meteran3 : '') == '>2.200 watt' ? 'selected' : '' }}>5. >2.200 watt</option>
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
                                                    <option value="Listrik" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Listrik' ? 'selected' : '' }}>1. Listrik</option>
                                                    <option value="Gas Elpiji 5 kg/blue gas" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Gas Elpiji 5 kg/blue gas' ? 'selected' : '' }}>2. Gas Elpiji 5 kg/blue gas</option>
                                                    <option value="Gas Elpiji 12 kg" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Gas Elpiji 12 kg' ? 'selected' : '' }}>3. Gas Elpiji 12 kg</option>
                                                    <option value="Gas Elpiji 3 kg" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Gas Elpiji 3 kg' ? 'selected' : '' }}>4. Gas Elpiji 3 kg</option>
                                                    <option value="Gas kota/Meteran GPN" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Gas kota/Meteran GPN' ? 'selected' : '' }}>5. Gas Meteran GPN</option>
                                                    <option value="Biogas" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Biogas' ? 'selected' : '' }}>6. Biogas</option>
                                                    <option value="Minyak Tanah" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Minyak Tanah' ? 'selected' : '' }}>7. Minyak Tanah</option>
                                                    <option value="Breket" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Breket' ? 'selected' : '' }}>8. Breket</option>
                                                    <option value="Arang" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Arang' ? 'selected' : '' }}>9. Arang</option>
                                                    <option value="Kayu Bakar" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Kayu Bakar' ? 'selected' : '' }}>10. Kayu Bakar</option>
                                                    <option value="Lainnya" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Lainnya' ? 'selected' : '' }}>11. Lainnya</option>
                                                    <option value="Tidak Memasak dirumah" {{ old('BahanBakarEnergiMemasak', isset($dataBangunan) ? $dataBangunan->BahanBakarEnergiMemasak : '') == 'Tidak Memasak dirumah' ? 'selected' : '' }}>12. Tidak Memasak di rumah</option>
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
                                                    <option value="Tangki septik" {{ old('TempatPembuanganAkhirTinja', isset($dataBangunan) ? $dataBangunan->TempatPembuanganAkhirTinja : '') == 'Tangki septik' ? 'selected' : '' }}>1. Tangki septik</option>
                                                    <option value="IPAL" {{ old('TempatPembuanganAkhirTinja', isset($dataBangunan) ? $dataBangunan->TempatPembuanganAkhirTinja : '') == 'IPAL' ? 'selected' : '' }}>2. IPAL</option>
                                                    <option value="Kolam/sawah/Sungai/danau/laut" {{ old('TempatPembuanganAkhirTinja', isset($dataBangunan) ? $dataBangunan->TempatPembuanganAkhirTinja : '') == 'Kolam/sawah/Sungai/danau/laut' ? 'selected' : '' }}>3. Kolam/Sawah/Sungai/Danau/Laut</option>
                                                    <option value="Lubang tanah" {{ old('TempatPembuanganAkhirTinja', isset($dataBangunan) ? $dataBangunan->TempatPembuanganAkhirTinja : '') == 'Lubang tanah' ? 'selected' : '' }}>4. Lubang tanah</option>
                                                    <option value="Pantai/tanah lapang/kebun" {{ old('TempatPembuanganAkhirTinja', isset($dataBangunan) ? $dataBangunan->TempatPembuanganAkhirTinja : '') == 'Pantai/tanah lapang/kebun' ? 'selected' : '' }}>5. Pantai/Tanah lapang/Kebun</option>
                                                    <option value="Lainnya" {{ old('TempatPembuanganAkhirTinja', isset($dataBangunan) ? $dataBangunan->TempatPembuanganAkhirTinja : '') == 'Lainnya' ? 'selected' : '' }}>6. Lainnya</option>
                                                </select>
                                                @error('TempatPembuanganAkhirTinja')
                                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kepemilikan dan Penggunaan Fasilitas Tempat Buang Air Besar & Jenis Kloset -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Kepemilikan BAB -->
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
                                                    data-initial-value="{{ old('KepemilikanBAB', isset($dataBangunan) ? $dataBangunan->KepemilikanBAB : '') }}"
                                                >
                                                    <option value="" class="text-gray-500" disabled selected>Pilih Kepemilikan dan Penggunaan Fasilitas</option>
                                                    <option value="Ada, digunakan hanya anggota keluarga sendiri" {{ old('KepemilikanBAB', isset($dataBangunan) ? $dataBangunan->KepemilikanBAB : '') == 'Ada, digunakan hanya anggota keluarga sendiri' ? 'selected' : '' }}>1. Ada, digunakan hanya anggota keluarga sendiri</option>
                                                    <option value="Ada, digunakan bersama anggota keluarga dari keluarga tertentu" {{ old('KepemilikanBAB', isset($dataBangunan) ? $dataBangunan->KepemilikanBAB : '') == 'Ada, digunakan bersama anggota keluarga dari keluarga tertentu' ? 'selected' : '' }}>2. Ada, digunakan bersama anggota keluarga dari keluarga tertentu</option>
                                                    <option value="Ada, di MCK komunal" {{ old('KepemilikanBAB', isset($dataBangunan) ? $dataBangunan->KepemilikanBAB : '') == 'Ada, di MCK komunal' ? 'selected' : '' }}>3. Ada, di MCK komunal</option>
                                                    <option value="Ada, di MCK Umum" {{ old('KepemilikanBAB', isset($dataBangunan) ? $dataBangunan->KepemilikanBAB : '') == 'Ada, di MCK Umum' ? 'selected' : '' }}>4. Ada, di MCK Umum</option>
                                                    <option value="Ada, Anggota keluarga tidak menggunakan" {{ old('KepemilikanBAB', isset($dataBangunan) ? $dataBangunan->KepemilikanBAB : '') == 'Ada, Anggota keluarga tidak menggunakan' ? 'selected' : '' }}>5. Ada, Anggota keluarga tidak menggunakan</option>
                                                    <option value="Tidak Ada" {{ old('KepemilikanBAB', isset($dataBangunan) ? $dataBangunan->KepemilikanBAB : '') == 'Tidak Ada' ? 'selected' : '' }}>6. Tidak Ada</option>
                                                </select>
                                                @error('KepemilikanBAB')
                                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Jenis Kloset -->
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
                                                    data-initial-value="{{ old('JenisKloset', isset($dataBangunan) ? $dataBangunan->JenisKloset : '') }}"
                                                >
                                                    <option value="" class="text-gray-500" disabled selected>Pilih Jenis Kloset</option>
                                                    <option value="Leher Angsa" {{ old('JenisKloset', isset($dataBangunan) ? $dataBangunan->JenisKloset : '') == 'Leher Angsa' ? 'selected' : '' }}>1. Leher Angsa</option>
                                                    <option value="Plesengan dengan tutup" {{ old('JenisKloset', isset($dataBangunan) ? $dataBangunan->JenisKloset : '') == 'Plesengan dengan tutup' ? 'selected' : '' }}>2. Plesengan dengan tutup</option>
                                                    <option value="Plengsengan tanpa tutup" {{ old('JenisKloset', isset($dataBangunan) ? $dataBangunan->JenisKloset : '') == 'Plengsengan tanpa tutup' ? 'selected' : '' }}>3. Plengsengan tanpa tutup</option>
                                                    <option value="Cemplung/Bubluk" {{ old('JenisKloset', isset($dataBangunan) ? $dataBangunan->JenisKloset : '') == 'Cemplung/Bubluk' ? 'selected' : '' }}>4. Cemplung/Bubluk</option>
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
                                        Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
            @include('components.footer')
        </div>


        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('dropdownSearch', (options, selectedModel, nameModel, defaultSelected = '', defaultDisplay = '') => ({
                    options: options, // Data NomorKK dan NamaKepalaKeluarga dari backend
                    search: '', // Input pencarian
                    open: false, // Status dropdown terbuka
                    [selectedModel]: defaultSelected || '', // NomorKK yang dipilih (default jika ada)
                    [nameModel]: defaultDisplay || '', // Nama Kepala Keluarga yang dipilih (default jika ada)

                    init() {
                        // Inisialisasi filteredOptions dengan semua data
                        if (defaultSelected && defaultDisplay) {
                            this[selectedModel] = defaultSelected; // Set default NomorKK
                            this[nameModel] = defaultDisplay; // Set default NamaKepalaKeluarga
                            this.search = defaultSelected; // Tampilkan default NomorKK di input pencarian
                        }
                        this.filteredOptions = this.options; // Semua data awalnya terlihat
                    },

                    get filteredOptions() {
                        // Filter data berdasarkan input pencarian
                        if (!this.search.trim()) {
                            return this.options; // Jika input kosong, tampilkan semua data
                        }
                        return this.options.filter(option => {
                            const nomorKK = option.NomorKK ? String(option.NomorKK).toLowerCase() : '';
                            return nomorKK.includes(this.search.toLowerCase());
                        });
                    },

                    selectOption(option) {
                        // Set data ketika opsi dipilih
                        this[selectedModel] = option.NomorKK;
                        this[nameModel] = option.NamaKepalaKeluarga;
                        this.search = option.NomorKK; // Tampilkan NomorKK yang dipilih di input pencarian
                        this.open = false; // Tutup dropdown
                    },

                    closeDropdown() {
                        // Tutup dropdown saat klik di luar
                        this.open = false;
                    },

                    toggleDropdown() {
                        // Toggle status dropdown
                        this.open = !this.open;
                    },
                }));
            });

            function toggleMeteranInputs() {
                const sumberPenerangan = document.getElementById('SumberPeneranganUtama').value;
                const initialValue = document.getElementById('SumberPeneranganUtama').dataset.initialValue;
                const meteran1 = document.getElementById('Meteran1');
                const meteran2 = document.getElementById('Meteran2');
                const meteran3 = document.getElementById('Meteran3');

                const isPLN = sumberPenerangan === "Listrik PLN dengan meteran" || initialValue === "Listrik PLN dengan meteran";

                meteran1.disabled = !isPLN;
                meteran2.disabled = !isPLN;
                meteran3.disabled = !isPLN;
            }

            // Run on page load
            toggleMeteranInputs();

            function toggleJenisKloset() {
                const kepemilikanBAB = document.getElementById('KepemilikanBAB').value;
                const jenisKloset = document.getElementById('JenisKloset');
                const initialKepemilikanBAB = document.getElementById('KepemilikanBAB').dataset.initialValue;
                const initialJenisKloset = document.getElementById('JenisKloset').dataset.initialValue;

                // Check if the previous KepemilikanBAB value was 1, 2, or 3
                const isKepemilikanBABValid = kepemilikanBAB === "Ada, digunakan hanya anggota keluarga sendiri" ||
                                            kepemilikanBAB === "Ada, digunakan bersama anggota keluarga dari keluarga tertentu" ||
                                            kepemilikanBAB === "Ada, di MCK komunal";

                // If the initial value of KepemilikanBAB was 1, 2, or 3, keep the JenisKloset enabled
                const shouldEnableJenisKloset = isKepemilikanBABValid || initialKepemilikanBAB === "Ada, digunakan hanya anggota keluarga sendiri" ||
                                            initialKepemilikanBAB === "Ada, digunakan bersama anggota keluarga dari keluarga tertentu" ||
                                            initialKepemilikanBAB === "Ada, di MCK komunal";

                if (shouldEnableJenisKloset) {
                    jenisKloset.disabled = false;  // Enable dropdown
                } else {
                    jenisKloset.disabled = true;   // Disable dropdown
                }
            }

            // Run on page load
            toggleJenisKloset();
        </script>
    </body>

</html>
