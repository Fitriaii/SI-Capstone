<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Data Kependudukan</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-blue-100">

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
                    <div class="flex items-center justify-between px-4 mt-4 text-blue-950">
                        <!-- Judul -->
                        <h1 class="text-xl font-semibold whitespace-nowrap">Data Kependudukan</h1>

                        <!-- Filter dan Pencarian -->
                        <div class="flex items-center space-x-4">
                            <!-- Filter Padukuhan -->
                            <form id="filterForm" action="{{ route('penduduk.index') }}" method="GET">
                                <div class="flex items-center space-x-4">
                                    <!-- Filter Padukuhan -->
                                    <div>
                                        <select
                                            id="padukuhan"
                                            name="padukuhan"
                                            class="block w-40 px-4 py-2 text-xs placeholder-gray-400 border border-gray-400 rounded-lg appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            onchange="document.getElementById('filterForm').submit();"
                                        >
                                            <option value="" hidden>Pilih Padukuhan</option>
                                            <option value="Daratan 1" {{ request('padukuhan') == 'Daratan 1' ? 'selected' : '' }}>Daratan 1</option>
                                            <option value="Daratan 2" {{ request('padukuhan') == 'Daratan 2' ? 'selected' : '' }}>Daratan 2</option>
                                            <option value="Daratan 3" {{ request('padukuhan') == 'Daratan 3' ? 'selected' : '' }}>Daratan 3</option>
                                            <option value="Jonggrangan" {{ request('padukuhan') == 'Jonggrangan' ? 'selected' : '' }}>Jonggrangan</option>
                                            <option value="Soromintan" {{ request('padukuhan') == 'Soromintan' ? 'selected' : '' }}>Soromintan</option>
                                            <option value="Kerdan" {{ request('padukuhan') == 'Kerdan' ? 'selected' : '' }}>Kerdan</option>
                                            <option value="Kebitan" {{ request('padukuhan') == 'Kebitan' ? 'selected' : '' }}>Kebitan</option>
                                            <option value="Tinggen" {{ request('padukuhan') == 'Tinggen' ? 'selected' : '' }}>Tinggen</option>
                                            <option value="Sanan" {{ request('padukuhan') == 'Sanan' ? 'selected' : '' }}>Sanan</option>
                                            <option value="Klodran" {{ request('padukuhan') == 'Klodran' ? 'selected' : '' }}>Klodran</option>
                                            <option value="Ngijon" {{ request('padukuhan') == 'Ngijon' ? 'selected' : '' }}>Ngijon</option>
                                            <option value="Blatikan" {{ request('padukuhan') == 'Blatikan' ? 'selected' : '' }}>Blatikan</option>
                                            <option value="Toglengan" {{ request('padukuhan') == 'Toglengan' ? 'selected' : '' }}>Toglengan</option>
                                            <option value="Singojayan" {{ request('padukuhan') == 'Singojayan' ? 'selected' : '' }}>Singojayan</option>
                                        </select>
                                    </div>

                                    <!-- Search Input -->
                                    <div class="relative w-60">
                                        <input
                                            id="searchInput"
                                            name="search"
                                            type="text"
                                            placeholder="Cari Data"
                                            class="block w-full py-2 pl-4 pr-10 text-xs placeholder-gray-400 border border-gray-400 rounded-lg appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ request('search') }}"
                                        >
                                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg
                                                class="w-5 h-5 text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Main Section -->
                    <div class="px-4 mt-4">
                        <!-- Tombol -->
                        <div class="flex items-center space-x-4">
                            <!-- Form Tambah Data -->
                            <form>
                                @csrf
                                <a href="{{ route('penduduk.create') }}" class="flex items-center px-4 py-2 space-x-2 text-xs font-semibold text-white transition duration-200 bg-blue-500 rounded-lg hover:bg-blue-600">
                                    <span>Tambah Data</span>
                                    <span class="items-center justify-center inline-block w-4 h-4 text-blue-500 ">
                                        <img src="/images/add.png" alt="Add Icon" class="w-4 h-4"/>
                                    </span>
                                </a>

                            </form>

                            <!-- Form Unduh Data -->
                            <form id="downloadForm" action="{{ route('penduduk.export') }}" method="GET" target="_blank">
                                <button
                                    id="downloadButton"
                                    type="submit"
                                    class="flex items-center px-4 py-2 space-x-2 text-xs font-semibold text-white transition duration-200 bg-green-500 rounded-lg hover:bg-green-600"
                                >
                                    <span>Export XLSX</span>
                                    <span class="items-center justify-center inline-block w-4 h-4 text-green-500 ">
                                        <!-- Ganti ikon SVG dengan gambar -->
                                        <img src="/images/export.png" alt="Export Icon" class="w-4 h-4" />
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="justify-start w-full px-4 mt-4 mb-8 border-b border-gray-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="sticky top-0 border-b border-gray-200 rounded-lg bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium tracking-wider text-center text-black whitespace-nowrap">No</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-black whitespace-nowrap">Nomor Kartu Keluarga</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-black whitespace-nowrap">Nama Kepala Keluarga</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-black whitespace-nowrap">Padukuhan</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-black whitespace-nowrap">Jumlah Anggota Keluarga</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-black whitespace-nowrap">Alamat Lengkap</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-black whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($keluarga as $dataKeluarga)
                                    <tr>
                                        <!-- Nomor Urut -->
                                        <td class="px-6 py-4 text-xs font-medium text-center text-black whitespace-nowrap">
                                            {{ ($keluarga->currentPage() - 1) * $keluarga->perPage() + $loop->iteration }}
                                        </td>
                                        <!-- Data Lain -->
                                        <td class="px-6 py-4 text-xs font-medium text-center text-black whitespace-nowrap">{{ $dataKeluarga->NomorKK }}</td>
                                        <td class="px-6 py-4 text-xs font-medium text-center text-black whitespace-nowrap">{{ $dataKeluarga->NamaKepalaKeluarga }}</td>
                                        <td class="px-6 py-4 text-xs font-medium text-center text-black whitespace-nowrap">{{ $dataKeluarga->Padukuhan }}</td>
                                        <td class="px-6 py-4 text-xs font-medium text-center text-black whitespace-nowrap">{{ $dataKeluarga->JumlahAnggotaKeluarga }}</td>
                                        <td class="px-6 py-4 text-xs font-medium text-center text-black whitespace-nowrap">{{ $dataKeluarga->Alamat }}</td>
                                        <!-- Aksi -->
                                        <td class="px-6 py-4 text-xs font-medium text-center text-black whitespace-nowrap">
                                            <div class="flex justify-center gap-2">
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('penduduk.edit',$dataKeluarga) }}" class="p-1 text-blue-500 hover:text-blue-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                    </svg>
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <form method="POST" action="{{ route('penduduk.destroy', $dataKeluarga) }}" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="p-1 text-red-500 hover:text-red-700 confirm-delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9L14.398 19m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">Tidak ada data tersedia.</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        <!-- Navigasi Paginasi -->
                        <div class="mt-4">
                            {{ $keluarga->links('components.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
            @include('components.footer')
        </div>

        <script>
            // Ambil elemen input search dan form
            const searchInput = document.getElementById('searchInput');
            const filterForm = document.getElementById('filterForm');  // Pastikan ID formnya sesuai

            // Tambahkan event listener untuk input event pada searchInput
            searchInput.addEventListener('input', function () {
                // Tunggu sebentar sebelum mengirimkan pencarian (debounce)
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    // Submit form ketika input berhenti
                    filterForm.submit();
                }, 500); // Tunggu 500ms setelah input berhenti
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Tangkap semua tombol dengan class 'confirm-delete'
                document.querySelectorAll('.confirm-delete').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Ambil form induk dari tombol ini
                        const form = this.closest('form');

                        // Tampilkan SweetAlert untuk konfirmasi
                        Swal.fire({
                            title: 'Yakin ingin menghapus?',
                            text: "Data ini tidak dapat dikembalikan setelah dihapus!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Submit form jika pengguna mengonfirmasi
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>

    </body>

</html>
