<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SimplePasswordResetController extends Controller
{
    /**
     * Tampilkan form minta email.
     */
    public function createEmailForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Cek eksistensi email.
     */
    public function storeEmailForm(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $userExists = User::where('email', $request->email)->exists();

        if (!$userExists) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email ini tidak ditemukan pada rekaman sistem kami.']);
        }

        // Simpan email ke session agar hanya user berhak yang bisa buka URL update sandi
        session(['reset_password_email' => $request->email]);

        return redirect()->route('password.reset');
    }

    /**
     * Tampilkan form reset password (input password baru)
     */
    public function createResetForm(Request $request)
    {
        // Pastikan session sudah ada
        if (!session()->has('reset_password_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi kedaluwarsa. Silakan masukkan email Anda lagi.']);
        }

        return view('auth.reset-password', ['email' => session('reset_password_email')]);
    }

    /**
     * Update password di database.
     */
    public function storeResetForm(Request $request)
    {
        // Pastikan session masih ada untuk keamanan
        if (!session()->has('reset_password_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi kedaluwarsa. Silakan masuk dari awal.']);
        }

        $email = session('reset_password_email');

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Hapus session setelah sukses
        session()->forget('reset_password_email');

        return redirect()->route('login')->with('status', 'Alhamdulillah! Password Anda berhasil diubah. Silakan masuk menggunakan sandi yang baru.');
    }
}
