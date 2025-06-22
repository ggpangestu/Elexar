@extends('layouts.admin')
@section('title', 'Kelola Pengguna')

@section('content')
<h1 class="text-2xl font-bold mb-4">Kelola Pengguna</h1>

<form method="GET" class="mb-4">
    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..."
        class="px-4 py-2 rounded border w-full max-w-sm dark:bg-gray-800 dark:text-white" />
</form>

<table class="w-full bg-white dark:bg-gray-800 rounded shadow overflow-hidden">
    <thead>
        <tr class="bg-gray-100 dark:bg-gray-700 text-left text-sm text-gray-600 dark:text-gray-300">
            <th class="px-4 py-2">Nama</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Role</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2 text-right">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr class="border-t border-gray-200 dark:border-gray-700">
                <td class="px-4 py-2">{{ $user->name }}</td>
                <td class="px-4 py-2">{{ $user->email }}</td>
                <td class="px-4 py-2 capitalize">
                    <div x-data="{ originalRole: '{{ $user->role }}', selectedRole: '{{ $user->role }}' }">
                        <form method="POST" action="{{ route('admin.users.role', $user) }}" @change="selectedRole = $event.target.value">
                            @csrf
                            @method('PATCH')
                            <select name="role" x-model="selectedRole" class="px-2 py-1 rounded border dark:bg-gray-700">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <button type="submit"
                                x-show="selectedRole !== originalRole"
                                class="ml-2 px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-sm"
                                x-transition
                            >
                                Save
                            </button>
                        </form>
                    </div>
                </td>
                <td class="px-4 py-2">
                    <span class="{{ $user->is_active ? 'text-green-500' : 'text-red-500' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-4 py-2 text-right space-x-2">
                    <!-- Suspend / Aktifkan -->
                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                          id="toggle-form-{{ $user->id }}" class="inline">
                        @csrf @method('PATCH')
                        <button type="button"
                            class="px-2 py-1 text-sm rounded text-white
                                {{ $user->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }}"
                            onclick="confirmToggle({{ $user->id }}, '{{ $user->is_active ? 'Suspend' : 'Aktifkan' }}')">
                            {{ $user->is_active ? 'Suspend' : 'Aktifkan' }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada pengguna ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $users->withQueryString()->links() }}
</div>

<!-- Script SweetAlert -->
<script>
    function confirmToggle(userId, actionText) {
        Swal.fire({
            title: `Yakin ingin ${actionText.toLowerCase()} akun ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Ya, ${actionText}`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`toggle-form-${userId}`).submit();
            }
        });
    }
</script>
@endsection
