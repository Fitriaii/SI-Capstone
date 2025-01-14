<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Kita akan mengirimkan tautan reset password ke email pengguna
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Memeriksa status dan menampilkan pesan yang sesuai
        if ($status == Password::RESET_LINK_SENT) {
            // Menampilkan status sukses jika email reset password berhasil dikirim
            return back()->with('status', 'Tautan untuk mereset kata sandi telah dikirim ke email Anda. Silakan cek email Anda.');
        } else {
            // Jika terjadi kesalahan, mengembalikan error dan mempertahankan input
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Terjadi kesalahan saat mengirim tautan reset password.']);
        }
    }

}
