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

            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <p class="mt-4 mb-10 text-xl font-bold text-left text-textlp">
                    Lupa Kata Sandi Anda? <br>
                    <span class="text-sm font-light">Masukan Email Anda Untuk Mereset Kata Sandi</span>
                </p>

                <div class="items-center justify-center w-full mt-10">
                    <div class="mb-4">
                        <label for="email" class="block mb-1 text-base text-gray-950">Email</label>
                        <input type="text" id="email" name="email" :value="old('email')" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Masukkan Email" required>
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:bg-blue-800">Selanjutnya</button>
                    <p class="mt-4 text-sm text-center text-gray-600:underline">
                        <a href="/login" class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Halaman Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
