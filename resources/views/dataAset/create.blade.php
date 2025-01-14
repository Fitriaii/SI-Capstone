<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Data Aset</title>

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
                        <h1 class="mb-4 text-xl font-semibold whitespace-nowrap">Data Keikutsertaan Program, Kepemilikan Aset dan Layanan</h1>

                        <!-- Data Kependudukan Form -->
                        <div class="w-full p-6 mb-8 ">
                            <form id="userForm" action="{{ route('aset.store') }}" method="POST" class="space-y-3">
                                @csrf

                                <div class="grid items-center justify-center grid-cols-1 gap-6">
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


                                    <div class="w-full p-4 border border-gray-200 rounded-lg shadow-sm bg-blue-50 drop-shadow">
                                        <h2 class="mb-4 text-lg font-semibold text-center">Aset Bergerak/Tidak Bergerak</h2>
                                        <div class="grid items-center justify-between grid-cols-3 gap-4 text-center">
                                            <!-- Tabung Gas -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="TabungGas" class="text-sm">Tabung Gas 5,5 kg atau Lebih</label>
                                                <input type="hidden" name="TabungGas" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="TabungGas"
                                                    name="TabungGas"
                                                    value="Ya"
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                    @if(old('TabungGas', $dataAset->TabungGas ?? '') == 'Ya') checked @endif
                                                >
                                            </div>

                                            <!-- Emas/Perhiasan -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="EmasPerhiasan" class="text-sm">Emas/Perhiasan</label>
                                                <input type="hidden" name="EmasPerhiasan" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="EmasPerhiasan"
                                                    name="EmasPerhiasan"
                                                    value="Ya"
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                    @if(old('EmasPerhiasan', $dataAset->EmasPerhiasan ?? '') == 'Ya') checked @endif
                                                >
                                            </div>

                                            <!-- Kapal/Perahu Motor -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2">
                                                <label for="PerahuMotor" class="text-sm">Kapal/Perahu Motor</label>
                                                <input type="hidden" name="PerahuMotor" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="PerahuMotor"
                                                    name="PerahuMotor"
                                                    value="Ya"
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600"
                                                    @if(old('PerahuMotor', $dataAset->PerahuMotor ?? '') == 'Ya') checked @endif
                                                >
                                            </div>
                                        </div>

                                        <div class="grid items-center justify-between grid-cols-3 gap-4 text-center">
                                            <!-- Lemari Es -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="LemariEs" class="text-sm">Lemari Es/Kulkas</label>
                                                <input type="hidden" name="LemariEs" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="LemariEs"
                                                    name="LemariEs"
                                                    value="Ya"
                                                    @if(old('LemariEs', $dataAset->LemariEs ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Komputer/Laptop/Tablet -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="KomputerLaptopTablet" class="text-sm">Komputer/Laptop/Tablet</label>
                                                <input type="hidden" name="KomputerLaptopTablet" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="KomputerLaptopTablet"
                                                    name="KomputerLaptopTablet"
                                                    value="Ya"
                                                    @if(old('KomputerLaptopTablet', $dataAset->KomputerLaptopTablet ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Smartphone -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2">
                                                <label for="Smartphone" class="text-sm">Smartphone</label>
                                                <input type="hidden" name="Smartphone" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="Smartphone"
                                                    name="Smartphone"
                                                    value="Ya"
                                                    @if(old('Smartphone', $dataAset->Smartphone ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>
                                        </div>

                                        <div class="grid items-center justify-between grid-cols-3 gap-4 text-center">
                                            <!-- Air Conditioner (AC) -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="AC" class="text-sm">Air Conditioner (AC)</label>
                                                <input type="hidden" name="AC" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="AC"
                                                    name="AC"
                                                    value="Ya"
                                                    @if(old('AC', $dataAset->AC ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Sepeda Motor -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="SepedaMotor" class="text-sm">Sepeda Motor</label>
                                                <input type="hidden" name="SepedaMotor" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="SepedaMotor"
                                                    name="SepedaMotor"
                                                    value="Ya"
                                                    @if(old('SepedaMotor', $dataAset->SepedaMotor ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Lahan Lain (Selain yang Ditempati) -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2">
                                                <label for="LahanLain" class="text-sm">Lahan Lain(Selain yang Ditempati)</label>
                                                <input type="hidden" name="LahanLain" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="LahanLain"
                                                    name="LahanLain"
                                                    value="Ya"
                                                    @if(old('LahanLain', $dataAset->LahanLain ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>
                                        </div>

                                        <div class="grid items-center justify-between grid-cols-3 gap-4 text-center">
                                            <!-- Pemanas Air (Water Heater) -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="PemanasAir" class="text-sm">Pemanas Air (Water Heater)</label>
                                                <input type="hidden" name="PemanasAir" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="PemanasAir"
                                                    name="PemanasAir"
                                                    value="Ya"
                                                    @if(old('PemanasAir', $dataAset->PemanasAir ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Sepeda -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="Sepeda" class="text-sm">Sepeda</label>
                                                <input type="hidden" name="Sepeda" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="Sepeda"
                                                    name="Sepeda"
                                                    value="Ya"
                                                    @if(old('Sepeda', $dataAset->Sepeda ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Rumah/Bangunan di Tempat Lain -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2">
                                                <label for="RumahLain" class="text-sm">Rumah/Bangunan Ditempat Lain</label>
                                                <input type="hidden" name="RumahLain" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="RumahLain"
                                                    name="RumahLain"
                                                    value="Ya"
                                                    @if(old('RumahLain', $dataAset->RumahLain ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>
                                        </div>

                                        <div class="grid items-center justify-between grid-cols-3 gap-4 text-center">
                                            <!-- Telepon Rumah (PTSN) -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="TeleponRumah" class="text-sm">Telepon Rumah (PTSN)</label>
                                                <input type="hidden" name="TeleponRumah" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="TeleponRumah"
                                                    name="TeleponRumah"
                                                    value="Ya"
                                                    @if(old('TeleponRumah', $dataAset->TeleponRumah ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Mobil -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="Mobil" class="text-sm">Mobil</label>
                                                <input type="hidden" name="Mobil" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="Mobil"
                                                    name="Mobil"
                                                    value="Ya"
                                                    @if(old('Mobil', $dataAset->Mobil ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>
                                        </div>

                                        <div class="grid items-center justify-between grid-cols-3 gap-4 text-center">
                                            <!-- Televisi Layar Datar -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="TelevisiLayarDatar" class="text-sm">Televisi Layar Datar</label>
                                                <input type="hidden" name="TelevisiLayarDatar" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="TelevisiLayarDatar"
                                                    name="TelevisiLayarDatar"
                                                    value="Ya"
                                                    @if(old('TelevisiLayarDatar', $dataAset->TelevisiLayarDatar ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>

                                            <!-- Perahu -->
                                            <div class="flex items-center justify-between px-4 py-2 space-x-2 border-r border-gray-300">
                                                <label for="Perahu" class="text-sm">Perahu</label>
                                                <input type="hidden" name="Perahu" value="Tidak">
                                                <input
                                                    type="checkbox"
                                                    id="Perahu"
                                                    name="Perahu"
                                                    value="Ya"
                                                    @if(old('Perahu', $dataAset->Perahu ?? '') == 'Ya') checked @endif
                                                    class="px-2 py-2 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:border-gray-600">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="w-full p-6 border border-gray-200 rounded-lg shadow-sm bg-blue-50 drop-shadow">
                                        <h2 class="mb-6 text-lg font-semibold text-center text-gray-800">Jumlah Ternak yang Dimiliki (ekor)</h2>

                                        <!-- Row 1 -->
                                        <div class="grid grid-cols-3 gap-6">
                                            <div class="w-full">
                                                <label for="Sapi" class="block mb-2 text-sm font-medium text-gray-700">Sapi</label>
                                                <input
                                                    type="text"
                                                    id="Sapi"
                                                    name="Sapi"
                                                    value="{{ old('Sapi', isset($dataAset) ? $dataAset->Sapi : '') }}"
                                                    class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md outline-none hover:border-gray-600 focus:ring focus:ring-blue-300 focus:border-blue-500"
                                                >
                                                @error('Sapi')
                                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="w-full">
                                                <label for="Kuda" class="block mb-2 text-sm font-medium text-gray-700">Kuda</label>
                                                <input
                                                    type="text"
                                                    id="Kuda"
                                                    name="Kuda"
                                                    value="{{ old('Kuda', isset($dataAset) ? $dataAset->Kuda : '') }}"
                                                    class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md outline-none hover:border-gray-600 focus:ring focus:ring-blue-300 focus:border-blue-500"
                                                >
                                                @error('Kuda')
                                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="w-full">
                                                <label for="Kambing" class="block mb-2 text-sm font-medium text-gray-700">Kambing/Domba</label>
                                                <input
                                                    type="text"
                                                    id="Kambing"
                                                    name="Kambing"
                                                    value="{{ old('Kambing', isset($dataAset) ? $dataAset->Kambing : '') }}"
                                                    class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md outline-none hover:border-gray-600 focus:ring focus:ring-blue-300 focus:border-blue-500"
                                                >
                                                @error('Kambing')
                                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Row 2 -->
                                        <div class="grid grid-cols-3 gap-6 mt-6">
                                            <div class="w-full">
                                                <label for="Kerbau" class="block mb-2 text-sm font-medium text-gray-700">Kerbau</label>
                                                <input
                                                    type="text"
                                                    id="Kerbau"
                                                    name="Kerbau"
                                                    value="{{ old('Kerbau', isset($dataAset) ? $dataAset->Kerbau : '') }}"
                                                    class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md outline-none hover:border-gray-600 focus:ring focus:ring-blue-300 focus:border-blue-500"
                                                >
                                                @error('Kerbau')
                                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="w-full">
                                                <label for="Babi" class="block mb-2 text-sm font-medium text-gray-700">Babi</label>
                                                <input
                                                    type="text"
                                                    id="Babi"
                                                    name="Babi"
                                                    value="{{ old('Babi', isset($dataAset) ? $dataAset->Babi : '') }}"
                                                    class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md outline-none hover:border-gray-600 focus:ring focus:ring-blue-300 focus:border-blue-500"
                                                >
                                                @error('Babi')
                                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
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
        </script>
    </body>

</html>
