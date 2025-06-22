<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        // Cek jika user batal login dari halaman Google
        if (request()->has('error')) {
            return redirect()->route('login')->with('status', 'Login dengan Google dibatalkan.');
        }

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $email = $googleUser->getEmail();

            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                if (is_null($existingUser->email_verified_at)) {
                    // Akun belum terverifikasi, tolak login dengan Google
                    return redirect()->route('login')->with('status', 'Akun Anda belum diverifikasi. Silakan login manual dan verifikasi email terlebih dahulu.');
                }

                if (! $existingUser->is_active) {
                    return redirect()->route('login')->with('status', 'Akun Anda telah dinonaktifkan oleh admin.');
                }

                // Jika email sudah diverifikasi tapi google_id belum ada, isi google_id
                if (is_null($existingUser->google_id)) {
                    $existingUser->google_id = $googleUser->getId();
                    $existingUser->save();
                }

                // Sudah verifikasi → lanjut login
                Auth::login($existingUser);
                return redirect()->intended('/');
            } else {
                // Belum ada user, buat baru (langsung verified)
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'password' => null, // Kosongkan agar bisa diatur nanti
                ]);

                Auth::login($newUser);
            }

            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

}
