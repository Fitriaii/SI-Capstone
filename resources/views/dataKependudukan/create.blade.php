<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Data Kependudukan</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Styles / Scripts -->
        {{-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Tailwind CSS auto-generated styles here */
            </style>
        @endif --}}

        @vite(['resources/css/app.css', 'resources/js/app.js'])
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

                    <!-- Notifikasi -->
                    @if(session()->has('success'))
                        <div class="fixed top-0 right-0 p-4 m-4 text-white bg-green-500 rounded-lg shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="inline-block w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Data Kependudukan Form -->
                    <div class="w-full p-1 mx-auto mb-8 ">
                        <form id="userForm" action="{{ route('penduduk.store') }}" method="POST" class="space-y-3">
                            @csrf

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Provinsi & NamaKK -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="Provinsi" class="block mb-2 text-xs font-medium text-black">
                                                Provinsi
                                            </label>
                                            <input
                                                type="text"
                                                id="Provinsi"
                                                name="Provinsi"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                value="DI Yogyakarta"
                                                readonly
                                            >
                                            @error('Provinsi')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="NamaKepalaKeluarga" class="block mb-2 text-xs font-medium text-black">
                                                Nama Kepala Keluarga (KK)
                                            </label>
                                            <input
                                                type="text"
                                                id="NamaKepalaKeluarga"
                                                name="NamaKepalaKeluarga"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('NamaKepalaKeluarga')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Kabupaten/Kota & NomorUrt -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="Kabupaten" class="block mb-2 text-xs font-medium text-black">
                                                Kabupaten/Kota
                                            </label>
                                            <input
                                                type="text"
                                                id="Kabupaten"
                                                name="Kabupaten"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                value="Sleman"
                                                readonly
                                                >
                                            @error('Kabupaten')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="NomorUrutBangunanTempatTinggal" class="block mb-2 text-xs font-medium text-black">
                                                Nomor Urut Bangunan Tempat Tinggal
                                            </label>
                                            <input
                                                type="text"
                                                id="NomorUrutBangunanTempatTinggal"
                                                name="NomorUrutBangunanTempatTinggal"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('NomorUrutBangunanTempatTinggal')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Kecamatan & Nomor Urut Keluarga Hasil Verifikasi -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="Kecamatan" class="block mb-2 text-xs font-medium text-black">
                                                Kecamatan
                                            </label>
                                            <input
                                                type="text"
                                                id="Kecamatan"
                                                name="Kecamatan"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                value="Minggir"
                                                readonly
                                            >
                                            @error('Kecamatan')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="NoUrutKeluargaHasilVerif" class="block mb-2 text-xs font-medium text-black">
                                                Nomor Urut Keluarga Hasil Verifikasi
                                            </label>
                                            <input
                                                type="text"
                                                id="NoUrutKeluargaHasilVerif"
                                                name="NoUrutKeluargaHasilVerif"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('NoUrutKeluargaHasilVerif')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Desa/Kelurahan & Status Keluarga -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="Kalurahan" class="block mb-2 text-xs font-medium text-black">
                                                Desa/Kelurahan
                                            </label>
                                            <input
                                                type="text"
                                                id="Kalurahan"
                                                name="Kalurahan"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                value="Sendangarum"
                                                readonly
                                            >
                                            @error('Kalurahan')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="StatusKeluarga" class="block mb-2 text-xs font-medium text-black">
                                                Status Keluarga
                                            </label>
                                            <input
                                                type="text"
                                                id="StatusKeluarga"
                                                name="StatusKeluarga"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('StatusKeluarga')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Padukuhan & Jumlah Anggota Keluarga -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="Padukuhan" class="block mb-2 text-xs font-medium text-black">
                                                Padukuhan
                                            </label>
                                            <select
                                                id="Padukuhan"
                                                name="Padukuhan"
                                                class="block w-full p-1 px-4 py-2 text-xs placeholder-gray-400 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Padukuhan</option>
                                                <option value="Daratan 1">Daratan 1</option>
                                                <option value="Daratan 2">Daratan 2</option>
                                                <option value="Daratan 3">Daratan 3</option>
                                                <option value="Jonggrangan">Jonggrangan</option>
                                                <option value="Soromintan">Soromintan</option>
                                                <option value="Kerdan">Kerdan</option>
                                                <option value="Kebitan">Kebitan</option>
                                                <option value="Tinggen">Tinggen</option>
                                                <option value="Sanan">Sanan</option>
                                                <option value="Klodran">Klodran</option>
                                                <option value="Ngijon">Ngijon</option>
                                                <option value="Blantikan">Blantikan</option>
                                                <option value="Toglengan">Toglengan</option>
                                                <option value="Singojayan">Singojayan</option>
                                            </select>
                                            @error('Padukuhan')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="JumlahAnggotaKeluarga" class="block mb-2 text-xs font-medium text-black">
                                                Jumlah Anggota Keluarga
                                            </label>
                                            <input
                                                type="text"
                                                id="JumlahAnggotaKeluarga"
                                                name="JumlahAnggotaKeluarga"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('JumlahAnggotaKeluarga')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Kode SLS/Non SLS & ID Landmark Wilkerstat -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="flex items-center">
                                            <div class="w-full">
                                                <label for="KodeSLS" class="block mb-2 text-xs font-medium text-black">
                                                    Kode SLS/Non SLS
                                                </label>
                                                <input
                                                    type="text"
                                                    id="KodeSLS"
                                                    name="KodeSLS"
                                                    class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                >
                                                @error('KodeSLS')
                                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="w-full">
                                                <label for="KodeSubSLS" class="block mb-2 text-xs font-medium text-black">
                                                    Kode Sub SLS
                                                </label>
                                                <input
                                                    type="text"
                                                    id="KodeSubSLS"
                                                    name="KodeSubSLS"
                                                    class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                >
                                                @error('KodeSubSLS')
                                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="IdLandmarkWilkerStat" class="block mb-2 text-xs font-medium text-black">
                                                ID Landmark Wilkerstat
                                            </label>
                                            <input
                                                type="text"
                                                id="IdLandmarkWilkerStat"
                                                name="IdLandmarkWilkerStat"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('IdLandmarkWilkerStat')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Nama SLS/Non SLS & Nomor Kartu Keluarga -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="NamaSLSNonSLS" class="block mb-2 text-xs font-medium text-black">
                                                Nama SLS/Non SLS
                                            </label>
                                            <input
                                                type="text"
                                                id="NamaSLSNonSLS"
                                                name="NamaSLSNonSLS"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('NamaSLSNonSLS')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="NomorKK" class="block mb-2 text-xs font-medium text-black">
                                                Nomor Kartu Keluarga
                                            </label>
                                            <input
                                                type="text"
                                                id="NomorKK"
                                                name="NomorKK"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('NomorKK')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat (Jalan/Gang, Nomor Rumah) & Kode Kartu Keluarga -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="Alamat" class="block mb-2 text-xs font-medium text-black">
                                                Alamat (Jalan/Gang, Nomor Rumah)
                                            </label>
                                            <input
                                                type="text"
                                                id="Alamat"
                                                name="Alamat"
                                                class="w-full px-4 py-6 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                            @error('Alamat')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="KodeKartuKK" class="block mb-2 text-xs font-medium text-black">
                                                Kode Kartu Keluarga
                                            </label>
                                            <select
                                                id="KodeKartuKK"
                                                name="KodeKartuKK"
                                                class="block w-full p-1 px-4 py-2 text-xs placeholder-gray-400 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                <option value="" class="text-gray-500" disabled selected>Pilih Kode Kartu Keluarga</option>
                                                <option value="KK Sesuai">0. KK Sesuai</option>
                                                <option value="Keluarga Induk">1. Keluarga Induk</option>
                                                <option value="Keluarga Pecahan">2. Keluarga Pecahan</option>
                                            </select>
                                            @error('KodeKartuKK')
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



    </body>
</html>
