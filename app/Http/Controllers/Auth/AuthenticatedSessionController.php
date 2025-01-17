<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validasi data masukan
            $validatedData = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            // Cek apakah email terdaftar
            $user = \App\Models\User::where('email', $validatedData['email'])->first();
            if (!$user) {
                return back()->withErrors([
                    'email' => 'Email yang Anda masukkan salah atau tidak terdaftar.',
                ])->withInput();
            }

            // Proses autentikasi pengguna
            if (!Auth::attempt($validatedData, $request->boolean('remember'))) {
                return back()->withErrors([
                    'password' => 'Kata sandi yang Anda masukkan salah.',
                ])->withInput();
            }

            // Regenerasi session untuk menghindari session fixation
            $request->session()->regenerate();

            // Redirect ke halaman yang dimaksud setelah login
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangkap pengecualian validasi input
            $errors = $e->errors();

            return back()->withErrors([
                'email' => $errors['email'][0] ?? null,
                'password' => $errors['password'][0] ?? null,
            ])->withInput();
        } catch (\Exception $e) {
            // Log kesalahan untuk debugging
            Log::error('Kesalahan saat proses login: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Pesan error generik jika terjadi kesalahan lainnya
            return back()->withErrors([
                'email' => 'Terjadi kesalahan sistem saat memproses email Anda.',
                'password' => 'Terjadi kesalahan sistem saat memproses kata sandi Anda.',
            ])->withInput();
        }
    }






    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
