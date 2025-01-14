<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-100">


    <nav class="fixed z-10 w-[calc(100%-16rem)] bg-blue-300 border-b border-blue-300 shadow-sm ml-64">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-center h-16">
                <h1 class="font-bold text-center text-white">
                    DATA REGISTRASI SOSIAL EKONOMI KELURAHAN SENDANGARUM
                </h1>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex min-h-screen pt-20">
        <!-- Sidebar -->
        <div class="w-64">
            @include('components.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="flex items-center justify-between px-8 py-4 text-blue-950">
                <h1 class="text-xl font-semibold">Dashboard</h1>
            </header>

            <!-- Dashboard Content -->
            <div class="px-8">
                <div class="grid grid-cols-1 gap-6 py-4 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Card: Jumlah Kartu Keluarga -->
                    <div class="flex items-center p-4 bg-white rounded-lg shadow">
                        <div class="p-4 rounded">
                            <img src="Images/jumlahkk.png" alt="Home Icon" class="w-12 h-12">
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-700">Jumlah Kartu Keluarga</h2>
                            <p class="text-2xl font-bold">{{ $jumlahKartuKeluarga }}</p>
                        </div>
                    </div>

                    <!-- Card: Jumlah Masyarakat -->
                    <div class="flex items-center p-4 bg-white rounded-lg shadow">
                        <div class="p-4 rounded">
                            <img src="Images/masyarakat.png" alt="Family Icon" class="w-12 h-12">
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-700">Jumlah Masyarakat</h2>
                            <p class="text-2xl font-bold">{{ $jumlahMasyarakat }}</p>
                        </div>
                    </div>

                    <!-- Card: Jumlah Penerima Bantuan -->
                    <div class="flex items-center p-4 bg-white rounded-lg shadow">
                        <div class="p-4 rounded">
                            <img src="Images/money.png" alt="Insurance Icon" class="w-12 h-12">
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-700">Jumlah Penerima Bantuan</h2>
                            <p class="text-2xl font-bold">{{ $totalPenerimaBantuan }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 py-4">
                    <!-- Chart: Jumlah Kartu Keluarga -->
                    <div class="p-4 bg-white rounded-lg shadow">
                        <p class="text-sm text-gray-500">Tiap Padukuhan</p>
                        <h3 class="mb-4 text-lg font-semibold text-blue-950">Jumlah Kartu Keluarga</h3>
                        <canvas id="jumlahKeluargaChart" class="p-4 bg-white rounded-lg shadow" style="height: 400px;max-height: 400px;"></canvas> <!-- Ganti canvas dengan div -->
                    </div>
                </div>

                <div class="flex py-4 space-x-4">
                    <!-- Data Kepemilikan Rumah -->
                    <div class="w-1/3 p-4 bg-white rounded-lg shadow">
                        <p class="text-sm text-gray-500">Status</p>
                        <h3 class="mb-4 text-lg font-semibold text-blue-950">Data Kepemilikan Rumah</h3>
                        <div class="relative h-48"> <!-- Gunakan `relative` dan tinggi yang konsisten -->
                            <canvas id="SatusRumahChart" class="bg-white rounded-lg shadow"></canvas>
                        </div>
                    </div>

                    <!-- Jumlah Keluarga Penerima Bantuan -->
                    <div class="w-2/3 p-4 bg-white rounded-lg shadow">
                        <p class="text-sm text-gray-500">Statistik</p>
                        <h3 class="mb-4 text-lg font-semibold text-blue-950">Jumlah Keluarga Penerima Bantuan</h3>
                        <div class="relative h-96"> <!-- Tinggi lebih besar untuk chart kedua -->
                            <canvas id="JumlahBantuanChart" class="bg-white rounded-lg shadow"></canvas>
                        </div>
                    </div>
                </div>
                @include('components.footer')
            </div>
        </div>
    </div>



    <!-- Script untuk grafik -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

    <script>
        // Data dari backend (diubah menjadi JSON)
        const jumlahKeluargaPerPadukuhan = @json($jumlahKeluargaPerPadukuhan);
        const padukuhanLabels = [
            'Daratan 1', 'Daratan 2', 'Daratan 3', 'Jonggrangan', 'Soromintan', 'Kerdan',
            'Kebitan', 'Tinggen', 'Sanan', 'Klodran', 'Ngijon', 'Blantikan', 'Toglengan', 'Singojayan'
        ];

        const jumlahKeluargaData = padukuhanLabels.map(function(padukuhan) {
        // Mencocokkan nama padukuhan dengan data yang ada di database
            const padukuhanData = jumlahKeluargaPerPadukuhan.find(function(item) {
                return item.Padukuhan === padukuhan; // Pencocokan berdasarkan nama padukuhan
            });

            // Jika padukuhan ditemukan, ambil jumlah keluarga, jika tidak, set menjadi 0
            return padukuhanData ? padukuhanData.jumlah_keluarga : 0;
        });


        const rumahData = @json($jumlahKeluargaPerStatus);
        const statusLabels = ['Milik sendiri', 'Kontrak/sewa', 'Bebas Sewa', 'Dinas', 'Lainnya'];
        const statusRumah = statusLabels.map(function(status){

            const statusRumahData = rumahData.find(function(item){
                return item.StatusKepemilikanBangunan === status;
            })

            return statusRumahData ? statusRumahData.status_rumah : 0;
        });

        const jumlahPenerimaBantuan = @json($jumlahPenerimaBantuan);
        // Mengambil label (NomorKK) dan data jumlah penerima bantuan
        const labelsBantuan = [
            'Program Bantuan Sosial Sembako/BPNT', 'Program Bantuan Pemerintah Daerah', 'Program Keluarga Harapan (PKH)',
            'Program Subsidi Pupuk', 'Program Bantuan Langsung Tunai (BLT) Desa','Program Subsidi LPG','Program Subsidi Listrik'
        ];
        const dataBantuan = labelsBantuan.map(label => jumlahPenerimaBantuan[label] || 0);

        let chartInstance;

        // Fungsi untuk membuat chart jumlah keluarga
        function createJumlahKeluargaChart() {
            const ctx = document.getElementById('jumlahKeluargaChart').getContext('2d');

            // Hapus instance Chart.js sebelumnya, jika ada
            if (chartInstance) {
                chartInstance.destroy();
            }

            // Variabel untuk menyimpan indeks bar yang sedang di-hover
            let hoveredIndex = null;

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: padukuhanLabels,
                    datasets: [
                        {
                            label: 'Jumlah Keluarga',
                            data: jumlahKeluargaData,
                            backgroundColor: '#93c5fd',
                            hoverBackgroundColor: '#172554',
                            borderRadius: 5,
                            barThickness: 30,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                            position: 'top',
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10,
                                font: {
                                    size: 10,
                                },
                            },
                            grid: {
                                borderDash: [5, 5], // Garis putus-putus
                            },
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 10,
                                    weight: function (context) {
                                        // Tebal jika indeks sesuai dengan hoveredIndex
                                        return hoveredIndex === context.index ? 'bold' : 'normal';
                                    },
                                },
                            },
                            barPercentage: 0.3, // Lebar bar lebih kecil
                            categoryPercentage: 0.5,
                            grid: {
                                display: false, // Tidak menampilkan garis vertikal
                            },
                        },
                    },
                    onHover: (event, elements) => {
                        if (elements.length > 0) {
                            // Ambil indeks bar yang sedang di-hover
                            hoveredIndex = elements[0].index;
                        } else {
                            // Reset jika kursor tidak di atas bar
                            hoveredIndex = null;
                        }
                        // Perbarui chart
                        chartInstance.update();
                    },
                },
            });
        }

        // Fungsi untuk membuat chart status rumah
        function createStatusRumahChart() {
            const ctx = document.getElementById('SatusRumahChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        label: 'Jumlah',
                        data: statusRumah,
                        backgroundColor: '#93c5fd',
                        hoverBackgroundColor: '#172554',
                        borderRadius: 5,
                        barThickness: 20,
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar chart
                    responsive: true,
                    scales: {
                        x: {

                            ticks: {
                                stepSize: 10,
                                color: '#666'
                            },
                            grid: {
                                display: false,
                            }
                        },
                        y: {
                            ticks: {
                                color: '#333',
                            },
                            grid: {
                                display: false,
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `Jumlah: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Panggil fungsi untuk membuat chart jumlah bantuan
        function createJumlahBantuanChart() {
            const ctx = document.getElementById('JumlahBantuanChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsBantuan,
                    datasets: [{
                        label: 'Jumlah',
                        data: dataBantuan,
                        backgroundColor: '#93c5fd',
                        hoverBackgroundColor: '#172554',
                        borderRadius: 5,
                        barThickness: 20,
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar chart
                    responsive: true,
                    scales: {
                        x: {
                            ticks: {
                                stepSize: 10,
                                color: '#666'
                            },
                            grid: {
                                display: false,
                            }
                        },
                        y: {
                            ticks: {
                                color: '#333',
                            },
                            grid: {
                                display: false,
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `Jumlah: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });

        }


        // Panggil fungsi untuk membuat chart
        createJumlahKeluargaChart();
        createStatusRumahChart();
        createJumlahBantuanChart();
    </script>


</body>

</html>
