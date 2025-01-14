<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Data Program Bantuan</title>

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
                            <form id="userForm" action="{{ route('program.update', $dataProgram) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PATCH')

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
                                        <h2 class="mb-4 text-lg font-semibold text-center">Jenis Program yang Diterima Satu Tahun Terakhir</h2>
                                        <div class="grid grid-cols-2 gap-4 p-2">
                                            <!-- Program Bantuan Sembako -->
                                            <div class="w-full p-4 bg-white rounded-lg shadow-sm">
                                                <label for="ProgramBantuanSembako" class="block mb-2 text-sm font-medium text-gray-700">Program Bantuan Sosial Sembako/BPNT</label>
                                                <div class="flex items-center space-x-4">
                                                    <!-- Radio Buttons -->
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramBantuanSembakoYa"
                                                            name="ProgramBantuanSembako"
                                                            value="Ya"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramBantuanSembako', $dataProgram->ProgramBantuanSembako ?? '') === 'Ya' ? 'checked' : '' }}>
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramBantuanSembakoTidak"
                                                            name="ProgramBantuanSembako"
                                                            value="Tidak"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramBantuanSembako', $dataProgram->ProgramBantuanSembako ?? '') === 'Tidak' ? 'checked' : '' }}>
                                                        <span class="text-sm">Tidak</span>
                                                    </label>

                                                    <!-- Input Date (Month and Year) -->
                                                    <div class="flex items-center space-x-2">
                                                        <label for="PeriodeSembako" class="text-sm">Periode:</label>
                                                        <input
                                                            type="month"
                                                            id="PeriodeSembako"
                                                            name="PeriodeSembako"
                                                            class="px-2 py-1 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            value="{{ old('PeriodeSembako', isset($dataProgram) && $dataProgram->PeriodeSembako ? date('Y-m', strtotime($dataProgram->PeriodeSembako)) : '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Program Bantuan Pemda -->
                                            <div class="w-full p-4 bg-white rounded-lg shadow-sm">
                                                <label for="ProgramBantuanPemda" class="block mb-2 text-sm font-medium text-gray-700">Program Bantuan Pemerintah Daerah</label>
                                                <div class="flex items-center space-x-4">
                                                    <!-- Radio Buttons -->
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramBantuanPemdaYa"
                                                            name="ProgramBantuanPemda"
                                                            value="Ya"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramBantuanPemda', $dataProgram->ProgramBantuanPemda ?? '') === 'Ya' ? 'checked' : '' }}>
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramBantuanPemdaTidak"
                                                            name="ProgramBantuanPemda"
                                                            value="Tidak"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramBantuanPemda', $dataProgram->ProgramBantuanPemda ?? '') === 'Tidak' ? 'checked' : '' }}>
                                                        <span class="text-sm">Tidak</span>
                                                    </label>

                                                    <!-- Input Date (Month and Year) -->
                                                    <div class="flex items-center space-x-2">
                                                        <label for="PeriodeBantuanPemda" class="text-sm">Periode:</label>
                                                        <input
                                                            type="month"
                                                            id="PeriodeBantuanPemda"
                                                            name="PeriodeBantuanPemda"
                                                            class="px-2 py-1 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            value="{{ old('PeriodeBantuanPemda', isset($dataProgram) && $dataProgram->PeriodeBantuanPemda ? date('Y-m', strtotime($dataProgram->PeriodeBantuanPemda)) : '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Program Bantuan PKH -->
                                            <div class="w-full p-4 bg-white rounded-lg shadow-sm">
                                                <label for="ProgramPKH" class="block mb-2 text-sm font-medium text-gray-700">Program Keluarga Harapan (PKH)</label>
                                                <div class="flex items-center space-x-4">
                                                    <!-- Radio Buttons -->
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramPKHYa"
                                                            name="ProgramPKH"
                                                            value="Ya"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramPKH', $dataProgram->ProgramPKH ?? '') === 'Ya' ? 'checked' : '' }}>
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramPKHTidak"
                                                            name="ProgramPKH"
                                                            value="Tidak"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramPKH', $dataProgram->ProgramPKH ?? '') === 'Tidak' ? 'checked' : '' }}>
                                                        <span class="text-sm">Tidak</span>
                                                    </label>

                                                    <!-- Input Date (Month and Year) -->
                                                    <div class="flex items-center space-x-2">
                                                        <label for="PeriodePKH" class="text-sm">Periode:</label>
                                                        <input
                                                            type="month"
                                                            id="PeriodePKH"
                                                            name="PeriodePKH"
                                                            class="px-2 py-1 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            value="{{ old('PeriodePKH', isset($dataProgram) && $dataProgram->PeriodePKH ? date('Y-m', strtotime($dataProgram->PeriodePKH)) : '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Program Bantuan Subsidi Pupuk -->
                                            <div class="w-full p-4 bg-white rounded-lg shadow-sm">
                                                <label for="ProgramSubsidiPupuk" class="block mb-2 text-sm font-medium text-gray-700">Program Subsidi Pupuk</label>
                                                <div class="flex items-center space-x-4">
                                                    <!-- Radio Buttons -->
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramSubsidiPupukYa"
                                                            name="ProgramSubsidiPupuk"
                                                            value="Ya"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramSubsidiPupuk', $dataProgram->ProgramSubsidiPupuk ?? '') === 'Ya' ? 'checked' : '' }}>
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramSubsidiPupukTidak"
                                                            name="ProgramSubsidiPupuk"
                                                            value="Tidak"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramSubsidiPupuk', $dataProgram->ProgramSubsidiPupuk ?? '') === 'Tidak' ? 'checked' : '' }}>
                                                        <span class="text-sm">Tidak</span>
                                                    </label>

                                                    <!-- Input Date (Month and Year) -->
                                                    <div class="flex items-center space-x-2">
                                                        <label for="PeriodeSubsidiPupuk" class="text-sm">Periode:</label>
                                                        <input
                                                            type="month"
                                                            id="PeriodeSubsidiPupuk"
                                                            name="PeriodeSubsidiPupuk"
                                                            class="px-2 py-1 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            value="{{ old('PeriodeSubsidiPupuk', isset($dataProgram) && $dataProgram->PeriodeSubsidiPupuk ? date('Y-m', strtotime($dataProgram->PeriodeSubsidiPupuk)) : '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Program Bantuan BLT -->
                                            <div class="w-full p-4 bg-white rounded-lg shadow-sm">
                                                <label for="ProgramBLT" class="block mb-2 text-sm font-medium text-gray-700">Program Bantuan Langsung Tunai (BLT) Desa</label>
                                                <div class="flex items-center space-x-4">
                                                    <!-- Radio Buttons -->
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramBLTYa"
                                                            name="ProgramBLT"
                                                            value="Ya"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramBLT', $dataProgram->ProgramBLT ?? '') === 'Ya' ? 'checked' : '' }}>
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramBLTTidak"
                                                            name="ProgramBLT"
                                                            value="Tidak"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramBLT', $dataProgram->ProgramBLT ?? '') === 'Tidak' ? 'checked' : '' }}>
                                                        <span class="text-sm">Tidak</span>
                                                    </label>

                                                    <!-- Input Date (Month and Year) -->
                                                    <div class="flex items-center space-x-2">
                                                        <label for="PeriodeBLT" class="text-sm">Periode:</label>
                                                        <input
                                                            type="month"
                                                            id="PeriodeBLT"
                                                            name="PeriodeBLT"
                                                            class="px-2 py-1 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            value="{{ old('PeriodeBLT', isset($dataProgram) && $dataProgram->PeriodeBLT ? date('Y-m', strtotime($dataProgram->PeriodeBLT)) : '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Program Bantuan Subsidi LPG -->
                                            <div class="w-full p-4 bg-white rounded-lg shadow-sm">
                                                <label for="ProgramSubsidiLPG" class="block mb-2 text-sm font-medium text-gray-700">Program Subsidi LPG</label>
                                                <div class="flex items-center space-x-4">
                                                    <!-- Radio Buttons -->
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramSubsidiLPGYa"
                                                            name="ProgramSubsidiLPG"
                                                            value="Ya"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramSubsidiLPG', $dataProgram->ProgramSubsidiLPG ?? '') === 'Ya' ? 'checked' : '' }}>
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramSubsidiLPGTidak"
                                                            name="ProgramSubsidiLPG"
                                                            value="Tidak"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramSubsidiLPG', $dataProgram->ProgramSubsidiLPG ?? '') === 'Tidak' ? 'checked' : '' }}>
                                                        <span class="text-sm">Tidak</span>
                                                    </label>

                                                    <!-- Input Date (Month and Year) -->
                                                    <div class="flex items-center space-x-2">
                                                        <label for="PeriodeSubsidiLPG" class="text-sm">Periode:</label>
                                                        <input
                                                            type="month"
                                                            id="PeriodeSubsidiLPG"
                                                            name="PeriodeSubsidiLPG"
                                                            class="px-2 py-1 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            value="{{ old('PeriodeSubsidiLPG', isset($dataProgram) && $dataProgram->PeriodeSubsidiLPG ? date('Y-m', strtotime($dataProgram->PeriodeSubsidiLPG)) : '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Program Bantuan Listrik -->
                                            <div class="w-full p-4 bg-white rounded-lg shadow-sm">
                                                <label for="ProgramSubsidiListrik" class="block mb-2 text-sm font-medium text-gray-700">Program Subsidi Listrik</label>
                                                <div class="flex items-center space-x-4">
                                                    <!-- Radio Buttons -->
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramSubsidiListrikYa"
                                                            name="ProgramSubsidiListrik"
                                                            value="Ya"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramSubsidiListrik', $dataProgram->ProgramSubsidiListrik ?? '') === 'Ya' ? 'checked' : '' }}>
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1">
                                                        <input
                                                            type="radio"
                                                            id="ProgramSubsidiListrikTidak"
                                                            name="ProgramSubsidiListrik"
                                                            value="Tidak"
                                                            class="w-5 h-5 bg-white border border-gray-300 rounded-full outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            {{ old('ProgramSubsidiListrik', $dataProgram->ProgramSubsidiListrik ?? '') === 'Tidak' ? 'checked' : '' }}>
                                                        <span class="text-sm">Tidak</span>
                                                    </label>

                                                    <!-- Input Date (Month and Year) -->
                                                    <div class="flex items-center space-x-2">
                                                        <label for="PeriodeSubsidiListrik" class="text-sm">Periode:</label>
                                                        <input
                                                            type="month"
                                                            id="PeriodeSubsidiListrik"
                                                            name="PeriodeSubsidiListrik"
                                                            class="px-2 py-1 bg-white border border-gray-300 rounded outline-none hover:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                                            value="{{ old('PeriodeSubsidiListrik', isset($dataProgram) && $dataProgram->PeriodeSubsidiListrik ? date('Y-m', strtotime($dataProgram->PeriodeSubsidiListrik)) : '') }}">
                                                    </div>
                                                </div>
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
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Program Bantuan Sembako
                const programBantuanSembakoYa = document.getElementById('ProgramBantuanSembakoYa');
                const programBantuanSembakoTidak = document.getElementById('ProgramBantuanSembakoTidak');
                const periodeSembako = document.getElementById('PeriodeSembako');

                // Program Bantuan Pemda
                const programBantuanPemdaYa = document.getElementById('ProgramBantuanPemdaYa');
                const programBantuanPemdaTidak = document.getElementById('ProgramBantuanPemdaTidak');
                const periodeBantuanPemda = document.getElementById('PeriodeBantuanPemda');

                // Program PKH
                const programPKHYa = document.getElementById('ProgramPKHYa');
                const programPKHTidak = document.getElementById('ProgramPKHTidak');
                const periodePKH = document.getElementById('PeriodePKH');

                // Program Subsidi Pupuk
                const programSubsidiPupukYa = document.getElementById('ProgramSubsidiPupukYa');
                const programSubsidiPupukTidak = document.getElementById('ProgramSubsidiPupukTidak');
                const periodeSubsidiPupuk = document.getElementById('PeriodeSubsidiPupuk');

                // Program BLT
                const programBLTYa = document.getElementById('ProgramBLTYa');
                const programBLTTidak = document.getElementById('ProgramBLTTidak');
                const periodeBLT = document.getElementById('PeriodeBLT');

                // Program Subsidi LPG
                const programSubsidiLPGYa = document.getElementById('ProgramSubsidiLPGYa');
                const programSubsidiLPGTidak = document.getElementById('ProgramSubsidiLPGTidak');
                const periodeSubsidiLPG = document.getElementById('PeriodeSubsidiLPG');

                // Program Subsidi Listrik
                const programSubsidiListrikYa = document.getElementById('ProgramSubsidiListrikYa');
                const programSubsidiListrikTidak = document.getElementById('ProgramSubsidiListrikTidak');
                const periodeSubsidiListrik = document.getElementById('PeriodeSubsidiListrik');

                // Fungsi untuk mengatur validasi periode berdasarkan pilihan radio
                function togglePeriodeValidation(radioYa, radioTidak, periodeInput) {
                    if (radioYa.checked) {
                        periodeInput.setAttribute('required', 'required');
                    } else {
                        periodeInput.removeAttribute('required');
                    }
                }

                // Menjalankan fungsi saat halaman dimuat
                togglePeriodeValidation(programBantuanSembakoYa, programBantuanSembakoTidak, periodeSembako);
                togglePeriodeValidation(programBantuanPemdaYa, programBantuanPemdaTidak, periodeBantuanPemda);
                togglePeriodeValidation(programPKHYa, programPKHTidak, periodePKH);
                togglePeriodeValidation(programSubsidiPupukYa, programSubsidiPupukTidak, periodeSubsidiPupuk);
                togglePeriodeValidation(programBLTYa, programBLTTidak, periodeBLT);
                togglePeriodeValidation(programSubsidiLPGYa, programSubsidiLPGTidak, periodeSubsidiLPG);
                togglePeriodeValidation(programSubsidiListrikYa, programSubsidiListrikTidak, periodeSubsidiListrik);

                // Menambahkan event listener untuk mengubah validasi saat memilih radio button
                programBantuanSembakoYa.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanSembakoYa, programBantuanSembakoTidak, periodeSembako);
                });
                programBantuanSembakoTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanSembakoYa, programBantuanSembakoTidak, periodeSembako);
                });

                programBantuanPemdaYa.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanPemdaYa, programBantuanPemdaTidak, periodeBantuanPemda);
                });
                programBantuanPemdaTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanPemdaYa, programBantuanPemdaTidak, periodeBantuanPemda);
                });

                programPKHYa.addEventListener('change', function() {
                    togglePeriodeValidation(programPKHYa, programPKHTidak, periodePKH);
                });
                programPKHTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programPKHYa, programPKHTidak, periodePKH);
                });

                programSubsidiPupukYa.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiPupukYa, programSubsidiPupukTidak, periodeSubsidiPupuk);
                });
                programSubsidiPupukTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiPupukYa, programSubsidiPupukTidak, periodeSubsidiPupuk);
                });

                programBLTYa.addEventListener('change', function() {
                    togglePeriodeValidation(programBLTYa, programBLTTidak, periodeBLT);
                });
                programBLTTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programBLTYa, programBLTTidak, periodeBLT);
                });

                programSubsidiLPGYa.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiLPGYa, programSubsidiLPGTidak, periodeSubsidiLPG);
                });
                programSubsidiLPGTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiLPGYa, programSubsidiLPGTidak, periodeSubsidiLPG);
                });

                programSubsidiListrikYa.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiListrikYa, programSubsidiListrikTidak, periodeSubsidiListrik);
                });
                programSubsidiListrikTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiListrikYa, programSubsidiListrikTidak, periodeSubsidiListrik);
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Program Bantuan Sembako
                const programBantuanSembakoYa = document.getElementById('ProgramBantuanSembakoYa');
                const programBantuanSembakoTidak = document.getElementById('ProgramBantuanSembakoTidak');
                const periodeSembako = document.getElementById('PeriodeSembako');

                // Program Bantuan Pemda
                const programBantuanPemdaYa = document.getElementById('ProgramBantuanPemdaYa');
                const programBantuanPemdaTidak = document.getElementById('ProgramBantuanPemdaTidak');
                const periodeBantuanPemda = document.getElementById('PeriodeBantuanPemda');

                // Program PKH
                const programPKHYa = document.getElementById('ProgramPKHYa');
                const programPKHTidak = document.getElementById('ProgramPKHTidak');
                const periodePKH = document.getElementById('PeriodePKH');

                // Program Subsidi Pupuk
                const programSubsidiPupukYa = document.getElementById('ProgramSubsidiPupukYa');
                const programSubsidiPupukTidak = document.getElementById('ProgramSubsidiPupukTidak');
                const periodeSubsidiPupuk = document.getElementById('PeriodeSubsidiPupuk');

                // Program BLT
                const programBLTYa = document.getElementById('ProgramBLTYa');
                const programBLTTidak = document.getElementById('ProgramBLTTidak');
                const periodeBLT = document.getElementById('PeriodeBLT');

                // Program Subsidi LPG
                const programSubsidiLPGYa = document.getElementById('ProgramSubsidiLPGYa');
                const programSubsidiLPGTidak = document.getElementById('ProgramSubsidiLPGTidak');
                const periodeSubsidiLPG = document.getElementById('PeriodeSubsidiLPG');

                // Program Subsidi Listrik
                const programSubsidiListrikYa = document.getElementById('ProgramSubsidiListrikYa');
                const programSubsidiListrikTidak = document.getElementById('ProgramSubsidiListrikTidak');
                const periodeSubsidiListrik = document.getElementById('PeriodeSubsidiListrik');

                // Fungsi untuk mengatur validasi periode berdasarkan pilihan radio
                function togglePeriodeValidation(radioYa, radioTidak, periodeInput) {
                    if (radioYa.checked) {
                        periodeInput.setAttribute('required', 'required');
                    } else {
                        periodeInput.removeAttribute('required');
                        periodeInput.value = '';  // Reset periode jika pilih "Tidak"
                    }
                }

                // Menjalankan fungsi saat halaman dimuat
                togglePeriodeValidation(programBantuanSembakoYa, programBantuanSembakoTidak, periodeSembako);
                togglePeriodeValidation(programBantuanPemdaYa, programBantuanPemdaTidak, periodeBantuanPemda);
                togglePeriodeValidation(programPKHYa, programPKHTidak, periodePKH);
                togglePeriodeValidation(programSubsidiPupukYa, programSubsidiPupukTidak, periodeSubsidiPupuk);
                togglePeriodeValidation(programBLTYa, programBLTTidak, periodeBLT);
                togglePeriodeValidation(programSubsidiLPGYa, programSubsidiLPGTidak, periodeSubsidiLPG);
                togglePeriodeValidation(programSubsidiListrikYa, programSubsidiListrikTidak, periodeSubsidiListrik);

                // Menambahkan event listener untuk mengubah validasi saat memilih radio button
                programBantuanSembakoYa.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanSembakoYa, programBantuanSembakoTidak, periodeSembako);
                });
                programBantuanSembakoTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanSembakoYa, programBantuanSembakoTidak, periodeSembako);
                });

                programBantuanPemdaYa.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanPemdaYa, programBantuanPemdaTidak, periodeBantuanPemda);
                });
                programBantuanPemdaTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programBantuanPemdaYa, programBantuanPemdaTidak, periodeBantuanPemda);
                });

                programPKHYa.addEventListener('change', function() {
                    togglePeriodeValidation(programPKHYa, programPKHTidak, periodePKH);
                });
                programPKHTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programPKHYa, programPKHTidak, periodePKH);
                });

                programSubsidiPupukYa.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiPupukYa, programSubsidiPupukTidak, periodeSubsidiPupuk);
                });
                programSubsidiPupukTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiPupukYa, programSubsidiPupukTidak, periodeSubsidiPupuk);
                });

                programBLTYa.addEventListener('change', function() {
                    togglePeriodeValidation(programBLTYa, programBLTTidak, periodeBLT);
                });
                programBLTTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programBLTYa, programBLTTidak, periodeBLT);
                });

                programSubsidiLPGYa.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiLPGYa, programSubsidiLPGTidak, periodeSubsidiLPG);
                });
                programSubsidiLPGTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiLPGYa, programSubsidiLPGTidak, periodeSubsidiLPG);
                });

                programSubsidiListrikYa.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiListrikYa, programSubsidiListrikTidak, periodeSubsidiListrik);
                });
                programSubsidiListrikTidak.addEventListener('change', function() {
                    togglePeriodeValidation(programSubsidiListrikYa, programSubsidiListrikTidak, periodeSubsidiListrik);
                });
            });
        </script>

    </body>

</html>
