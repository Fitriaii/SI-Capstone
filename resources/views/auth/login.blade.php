<!-- resources/views/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-6xl bg-white rounded-lg shadow-lg">
        <!-- Gambar Ilustrasi -->
        <div class="items-center justify-center hidden w-1/2 p-6 bg-blue-100 md:flex">
            <img src="{{ asset('images/logo_sleman.png') }}" alt="Logo" class="w-2/3 h-auto">
        </div>

        <!-- Form Login -->
        <div class="w-full p-8 md:w-1/2">
            <div class="mb-8 text-center">
                <!-- Logo dan Judul Sistem -->
                {{-- <img src="{{ asset('images/logo_sleman.png') }}" alt="Logo" class="w-16 h-auto mx-auto mb-4"> --}}
                <h2 class="text-lg font-semibold text-gray-700">Sistem Informasi Kependudukan Sosial Ekonomi Desa Sendangarum</h2>
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <!-- Input Email -->
                <div class="mb-4">
                    <label for="email" class="block mb-1 text-base text-gray-950">Email</label>
                    <input type="text" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Email" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Input Password -->
                <div class="mb-6">
                    <label for="password" class="block mb-1 text-base text-gray-950">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Kata Sandi" required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-left text-gray-600">
                        <a href="{{ route('password.request') }}" class=" hover:underline">Lupa Kata Sandi?</a>
                    </p>
                </div>
                <!-- Tombol Login -->
                <button type="submit" class="w-full py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:bg-blue-800">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>
