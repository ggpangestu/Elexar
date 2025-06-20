<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordController extends Controller
{
    /**
     * Update or set user password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Jika user sudah memiliki password
        if ($user->password) {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', PasswordRule::defaults()],
            ]);

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return back()->with('password_updated', 'Password berhasil diganti');
        }

        // Jika user belum memiliki password (akun dari Google)
        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('password_updated', 'Password berhasil dibuat');
    }
}
