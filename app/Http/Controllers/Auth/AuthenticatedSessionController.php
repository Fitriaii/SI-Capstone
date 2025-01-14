<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Proses autentikasi pengguna
            $request->authenticate();

            // Regenerasi session untuk menghindari session fixation
            $request->session()->regenerate();

            // Redirect ke halaman yang dimaksud setelah login
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangkap pengecualian jika autentikasi gagal (misalnya username/password salah)
            $errors = $e->errors();

            // Jika ada error untuk password, beri pesan pada password
            $errorMessagePassword = isset($errors['password']) ? 'Kata sandi yang Anda masukkan salah.' : null;

            // Jika ada error untuk email, beri pesan pada email
            $errorMessageEmail = isset($errors['email']) ? 'Email yang Anda masukkan tidak valid.' : null;

            // Mengembalikan pesan error untuk masing-masing field
            return back()->withErrors([
                'email' => $errorMessageEmail,
                'password' => $errorMessagePassword
            ])->withInput();
        } catch (\Exception $e) {
            // Menangkap pengecualian umum jika terjadi kesalahan lainnya
            return back()->withErrors([
                'email' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'password' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
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
