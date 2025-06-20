<section class="space-y-6">
    <header>
        <p class="mt-1 text-sm text-white dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua data akan dihapus secara permanen. Tidak ada cara untuk mengembalikannya.') }}
        </p>
    </header>
    
    <x-danger-button x-on:click="showConfirm = true">
    {{ __('Hapus Akun') }}
    </x-danger-button>
</section>

