<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::when($search, fn ($q) =>
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            )
            ->where('id', '<>', auth('admin')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    public function changeRole(User $user)
    {
        $user->role = $user->role === User::ROLE_ADMIN ? User::ROLE_USER : User::ROLE_ADMIN;
        $user->save();

        return back()->with('success', 'Role pengguna berhasil diubah.');
    }
}
