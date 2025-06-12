@extends('layouts.admin')

@section('title', 'ADMIN LAYOUTS-HOME')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-10">

    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">

        <div class="border-b pb-4 mb-6 border-gray-200 dark:border-gray-700">
            <h2 class="text-4xl font-bold text-brown-700 dark:text-brown-300 tracking-wide">Update Home Background</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">Ganti gambar latar halaman utama di website.</p>
        </div>

        {{-- Preview Image --}}
        @if($section && $section->home_img)
            <div class="space-y-2">
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300">Gambar Saat Ini:</label>
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('storage/' . $section->home_img) }}" alt="Current Background" class="w-full object-cover">
                </div>
            </div>
        @endif
    </section>

    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.layout.home.upload') }}" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateUpload()">
            @csrf
            {{-- Upload New Image --}}
            <div>
                <label for="home_background" class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Unggah Gambar Baru (max 30MB)</label>
                <input type="file" name="home_img" id="home_background" accept="image/*" class="w-full p-2 border rounded dark:bg-gray-900 dark:text-white dark:border-gray-600">
                @error('home_img')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700
                    text-sm font-medium px-6 py-2 rounded-full shadow transition
                    focus:outline-none focus:ring-2 focus:ring-offset-2
                    focus:ring-gray-400 dark:focus:ring-offset-gray-900
                    transform hover:scale-105 duration-150">
                Simpan Perubahan
            </button>
        </form>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    // Validasi upload

    function validateUpload() {
        const fileInput = document.getElementById('home_background');
        const file = fileInput.files[0];

        if (!file) {
            Swal.fire('Error', 'Silakan pilih gambar terlebih dahulu.', 'error');
            return false;
        }

        const maxSizeMB = 30;
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

        if (!allowedTypes.includes(file.type)) {
            Swal.fire('Error', 'Format file harus berupa gambar (JPG, PNG, atau WEBP).', 'error');
            return false;
        }

        if (file.size > maxSizeMB * 1024 * 1024) {
            Swal.fire('Error', 'Ukuran gambar tidak boleh lebih dari 30MB.', 'error');
            return false;
        }

        Swal.fire({
            title: 'Mengunggah...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        return true;
    }

</script>
@endsection
