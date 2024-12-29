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
                        <form id="userForm" action="{{ route('penduduk.update', $dataKeluarga) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')

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
                                                value="{{ old('Provinsi', isset($dataKeluarga) ? $dataKeluarga->Provinsi : '') }}"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
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
                                                value="{{ old('NamaKepalaKeluarga', isset($dataKeluarga) ? $dataKeluarga->NamaKepalaKeluarga : '') }}"
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
                                                value="{{ old('Kabupaten', isset($dataKeluarga) ? $dataKeluarga->Kabupaten : '') }}"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
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
                                                value="{{ old('NomorUrutBangunanTempatTinggal', isset($dataKeluarga) ? $dataKeluarga->NomorUrutBangunanTempatTinggal : '') }}"
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
                                                value="{{ old('Kecamatan', isset($dataKeluarga) ? $dataKeluarga->Kecamatan : '') }}"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
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
                                                value="{{ old('NoUrutKeluargaHasilVerif', isset($dataKeluarga) ? $dataKeluarga->NoUrutKeluargaHasilVerif : '') }}"
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
                                                value="{{ old('Kalurahan', isset($dataKeluarga) ? $dataKeluarga->Kalurahan : '') }}"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
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
                                                value="{{ old('StatusKeluarga', isset($dataKeluarga) ? $dataKeluarga->StatusKeluarga : '') }}"
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
                                                <option value="" class="text-gray-500" hidden>Pilih Padukuhan</option>
                                                <option value="Daratan 1" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Daratan 1' ? 'selected' : '' }}>Daratan 1</option>
                                                <option value="Daratan 2" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Daratan 2' ? 'selected' : '' }}>Daratan 2</option>
                                                <option value="Daratan 3" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Daratan 3' ? 'selected' : '' }}>Daratan 3</option>
                                                <option value="Jonggaran" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Jonggaran' ? 'selected' : '' }}>Jonggaran</option>
                                                <option value="Soromintan" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Soromintan' ? 'selected' : '' }}>Soromintan</option>
                                                <option value="Kerdan" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Kerdan' ? 'selected' : '' }}>Kerdan</option>
                                                <option value="Kebitan" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Kebitan' ? 'selected' : '' }}>Kebitan</option>
                                                <option value="Tinggen" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Tinggen' ? 'selected' : '' }}>Tinggen</option>
                                                <option value="Sanan" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Sanan' ? 'selected' : '' }}>Sanan</option>
                                                <option value="Klodran" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Klodran' ? 'selected' : '' }}>Klodran</option>
                                                <option value="Ngijon" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Ngijon' ? 'selected' : '' }}>Ngijon</option>
                                                <option value="Blantikan" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Blantikan' ? 'selected' : '' }}>Blantikan</option>
                                                <option value="Toglengan" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Toglengan' ? 'selected' : '' }}>Toglengan</option>
                                                <option value="Singojayan" {{ old('Padukuhan', isset($dataKeluarga) ? $dataKeluarga->Padukuhan : '') == 'Singojayan' ? 'selected' : '' }}>Singojayan</option>
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
                                                value="{{ old('JumlahAnggotaKeluarga', isset($dataKeluarga) ? $dataKeluarga->JumlahAnggotaKeluarga : '') }}"
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
                                                    value="{{ old('KodeSLS', isset($dataKeluarga) ? $dataKeluarga->KodeSLS : '') }}"
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
                                                    value="{{ old('KodeSubSLS', isset($dataKeluarga) ? $dataKeluarga->KodeSubSLS : '') }}"
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
                                                value="{{ old('IdLandmarkWilkerStat', isset($dataKeluarga) ? $dataKeluarga->IdLandmarkWilkerStat : '') }}"
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
                                                value="{{ old('NamaSLSNonSLS', isset($dataKeluarga) ? $dataKeluarga->NamaSLSNonSLS : '') }}"
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
                                                value="{{ old('NomorKK', isset($dataKeluarga) ? $dataKeluarga->NomorKK : '') }}"
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
                                                value="{{ old('Alamat', isset($dataKeluarga) ? $dataKeluarga->Alamat : '') }}"
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
                                                <option value="" class="text-gray-500" hidden>Pilih Kode Kartu Keluarga</option>
                                                <option value="KK Sesuai" {{ old('KodeKartuKK', isset($dataKeluarga) ? $dataKeluarga->KodeKartuKK : '') == 'KK Sesuai' ? 'selected' : '' }}>0. KK Sesuai</option>
                                                <option value="Keluarga Induk" {{ old('KodeKartuKK', isset($dataKeluarga) ? $dataKeluarga->KodeKartuKK : '') == 'Keluarga Induk' ? 'selected' : '' }}>1. Keluarga Induk</option>
                                                <option value="Keluarga Pecahan" {{ old('KodeKartuKK', isset($dataKeluarga) ? $dataKeluarga->KodeKartuKK : '') == 'Keluarga Pecahan' ? 'selected' : '' }}>2. Keluarga Pecahan</option>
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
                                    Perbarui
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </body>

</html>
