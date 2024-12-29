<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-6xl bg-white rounded-lg shadow-lg">
        <!-- Gambar Ilustrasi -->
        <div class="items-center justify-center hidden w-1/2 p-6 bg-blue-100 md:flex">
            <img src="{{ asset('images/logo_sleman.png') }}" alt="Logo" class="w-2/3 h-auto">
        </div>

        <!-- Form Reset Password -->
        <div class="w-full p-8 md:w-1/2">
            <div class="mb-8 text-center">
                <h2 class="text-lg font-semibold text-gray-700">Reset Kata Sandi</h2>
                <p class="text-sm text-gray-600">Masukkan email Anda dan kata sandi baru untuk mengatur ulang akses ke akun Anda.</p>
            </div>

            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <!-- Token Reset Password -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Input Email -->
                <div class="mb-4">
                    <label for="email" class="block mb-1 text-base text-gray-950">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Email" required autofocus autocomplete="username">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password Baru -->
                <div class="mb-4">
                    <label for="password" class="block mb-1 text-base text-gray-950">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Kata Sandi Baru" required autocomplete="new-password">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block mb-1 text-base text-gray-950">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Konfirmasi Kata Sandi" required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Reset Password -->
                <button type="submit" class="w-full py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:bg-blue-800">
                    Reset Kata Sandi
                </button>
            </form>
        </div>
    </div>
</body>
</html>
