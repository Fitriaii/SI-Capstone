<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-100">

    <nav class="fixed z-10 w-[calc(100%-16rem)] bg-blue-300 border-b border-blue-300 shadow-sm ml-64">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-center h-16">
                <h1 class="font-semibold text-center text-blue-950">
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
            <header class="flex items-center justify-between p-8 text-blue-950">
                <h1 class="text-xl font-semibold">Dashboard</h1>
            </header>

            <!-- Dashboard Content -->
            <div class="px-8">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Card: Jumlah Kartu Keluarga -->
                    <div class="flex items-center p-4 bg-white rounded-lg shadow">
                        <div class="p-4 rounded">
                            <img src="Images/home.png" alt="Home Icon">
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-700">Jumlah Kartu Keluarga</h2>
                            <p class="text-2xl font-bold">{{ $jumlahKartuKeluarga }}</p>
                        </div>
                    </div>

                    <!-- Card: Jumlah Masyarakat -->
                    <div class="flex items-center p-4 bg-white rounded-lg shadow">
                        <div class="p-4 rounded">
                            <img src="Images/family.png" alt="Family Icon">
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-700">Jumlah Masyarakat</h2>
                            <p class="text-2xl font-bold">{{ $jumlahMasyarakat }}</p>
                        </div>
                    </div>

                    <!-- Card: Jumlah Penerima Bantuan -->
                    <div class="flex items-center p-4 bg-white rounded-lg shadow">
                        <div class="p-4 rounded">
                            <img src="Images/insurance.png" alt="Insurance Icon">
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-700">Jumlah Penerima Bantuan</h2>
                            <p class="text-2xl font-bold">30</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="p-8">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Chart: Data Kepemilikan Rumah -->
                    <div class="p-4 bg-white rounded-lg shadow">
                        <h3 class="mb-4 text-lg font-semibold text-blue-950">Jumlah Kartu Keluarga</h3>
                        <canvas id="chart1"></canvas>
                    </div>

                    {{-- <!-- Chart: Data Kepemilikan Aset -->
                    <div class="p-4 bg-white rounded-lg shadow">
                        <h3 class="mb-4 text-lg font-semibold text-blue-950">Data Kepemilikan Aset</h3>
                        <canvas id="chart2"></canvas>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>


    <!-- Script untuk grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx1 = document.getElementById('chart1').getContext('2d');

        // Data dari server
        const labels = @json($allPadukuhan); // Nama padukuhan
        const data = @json($data->pluck('jumlah_keluarga')); // Jumlah keluarga

        // Inisialisasi grafik
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labels, // Nama padukuhan sebagai label
                datasets: [{
                    label: 'Jumlah Keluarga',
                    data: data, // Data jumlah keluarga
                    backgroundColor: 'rgba(54, 162, 235, 0.8)', // Warna batang grafik
                    borderColor: 'rgba(54, 162, 235, 1)', // Warna garis tepi
                    borderWidth: 1 // Ketebalan garis tepi
                }]
            },
            options: {
                responsive: true, // Grafik responsif
                plugins: {
                    legend: {
                        display: true, // Tampilkan label dataset
                        position: 'top' // Posisi legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Jumlah Keluarga: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Padukuhan' // Judul sumbu X
                        },
                        ticks: {
                            callback: function(value, index) {
                                // Tampilkan nama padukuhan secara penuh
                                return labels[index];
                            },
                            maxRotation: 45, // Rotasi maksimal 45 derajat
                            minRotation: 45, // Rotasi minimal 45 derajat
                            autoSkip: false // Jangan melewatkan label
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Keluarga' // Judul sumbu Y
                        },
                        beginAtZero: true // Mulai dari 0
                    }
                }
            }
        });


        // const ctx2 = document.getElementById('chart2').getContext('2d');
        // new Chart(ctx2, {
        //     type: 'bar',
        //     data: {
        //         labels: ['Milik Sendiri', 'Kontrak/Sewa', 'Bebas Sewa', 'Dinas', 'Lainnya'],
        //         datasets: [{
        //             label: 'Jumlah',
        //             data: [50, 20, 25, 30, 10],
        //             backgroundColor: 'rgba(54, 162, 235, 0.8)',
        //         }]
        //     }
        // });

    </script>
</body>

</html>
