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
                        <form id="userForm" action="{{ route('layanan.store') }}" method="POST" class="space-y-3">
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

                                <!-- Kecamatan & Nomor Urut Keluarga Hasil Verifikasi -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="JenisAksesInternet" class="block mb-2 text-xs font-medium text-black">
                                                Jenis Akses Internet Utama
                                            </label>
                                            <select
                                                id="JenisAksesInternet"
                                                name="JenisAksesInternet"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" disabled selected>Pilih Jenis Akses Internet</option>
                                                <option value="Tidak Menggunakan">1. Tidak Menggunakan</option>
                                                <option value="Internet dan TV Digital berlangganan">2. Internet dan TV Digital berlangganan</option>
                                                <option value="Wifi">3. Wifi</option>
                                                <option value="Internet Handphone">4. Internet Handphone</option>
                                            </select>
                                            @error('JenisAksesInternet')
                                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <label for="KepemilikanRekeningEWallet" class="block mb-2 text-xs font-medium text-black">
                                                Kepemilikan Rekening Aktif atau Dompet Digital
                                            </label>
                                            <select
                                                id="KepemilikanRekeningEWallet"
                                                name="KepemilikanRekeningEWallet"
                                                class="w-full px-4 py-2 mt-1 text-xs bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                            >
                                                <option value="" disabled selected>Pilih Jenis Kepemilikan Rekening</option>
                                                <option value="Ya, untuk pribadi">1. Ya, untuk pribadi</option>
                                                <option value="Ya, untuk usaha">2. Ya, untuk usaha</option>
                                                <option value="Ya, untuk pribadi dan usaha">3. Ya, untuk pribadi dan usaha</option>
                                                <option value="Tidak">4. Tidak</option>
                                            </select>
                                            @error('KepemilikanRekeningEWallet')
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
        </script>
    </body>

</html>
