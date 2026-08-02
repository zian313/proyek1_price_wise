<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:buyer,seller'],
        ];

        if ($request->role === 'seller') {
            $rules['nama_ktp'] = ['required', 'string', 'max:255'];
            $rules['alamat_lengkap'] = ['required', 'string', 'max:1000'];
            $rules['foto_ktp'] = ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'];
            $rules['selfie_ktp'] = ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'];
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'seller_status' => $request->role === 'seller' ? 'pending' : 'approved',
        ];

        if ($request->role === 'seller') {
            $userData['nama_ktp'] = $request->nama_ktp;
            $userData['alamat_lengkap'] = $request->alamat_lengkap;

            if ($request->hasFile('foto_ktp')) {
                $fotoKtpName = time() . '_ktp_' . uniqid() . '.' . $request->file('foto_ktp')->getClientOriginalExtension();
                $request->file('foto_ktp')->storeAs('public/seller_docs', $fotoKtpName);
                $userData['foto_ktp'] = $fotoKtpName;
            }

            if ($request->hasFile('selfie_ktp')) {
                $selfieKtpName = time() . '_selfie_' . uniqid() . '.' . $request->file('selfie_ktp')->getClientOriginalExtension();
                $request->file('selfie_ktp')->storeAs('public/seller_docs', $selfieKtpName);
                $userData['selfie_ktp'] = $selfieKtpName;
            }
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
