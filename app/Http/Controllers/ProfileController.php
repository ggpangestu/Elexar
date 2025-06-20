<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }

    // ProfileController.php

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $isEmailChanged = $request->email !== $user->email;

        $user->name = $request->name;
        $user->email = $request->email;

        if ($isEmailChanged) {
            $user->email_verified_at = null;
            $user->google_id = null; // opsional jika Google login
        }

        $user->save();

        // Kirim ulang email verifikasi jika email berubah
        if ($isEmailChanged) {
            $user->sendEmailVerificationNotification();

            //session tersedia di blade
            session()->flash('email_changed', true);
            session()->flash('password_null', $user->password == null);
        }

        return redirect()->route('profile.show')->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'confirmation' => ['required', 'in:DELETE'],
        ], [
            'confirmation.in' => 'Konfirmasi tidak cocok. Anda harus mengetik "DELETE" untuk menghapus akun.',
        ]);

        $user = Auth::user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('account_deleted', true);
    }

}

