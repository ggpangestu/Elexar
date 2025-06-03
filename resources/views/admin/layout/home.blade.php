@extends('layouts.admin')

<style>
.custom-toast-success {
    background-color: #38a169 !important; /* Tailwind green-600 */
    color: white !important;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
</style>

@section('content')
<div class="flex bg-gray-100 dark:bg-gray-900 px-4 py-0 transition-colors overflow-auto">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 sm:p-10 space-y-8 transition-colors">

        <div class="border-b pb-4 mb-6 border-gray-200 dark:border-gray-700">
            <h2 class="text-4xl font-bold text-brown-700 dark:text-brown-300 tracking-wide">Update Home Background</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">Ganti gambar latar halaman utama di website.</p>
        </div>

        {{-- Success message --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        toast: true,
                        position: 'top',
                        customClass: {
                            popup: 'custom-toast-success'
                        }
                    });
                });
            </script>
        @endif

        {{-- Form Upload --}}
        <form method="POST" action="{{ route('admin.layout.home.upload') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Preview Image --}}
            @if($section && $section->home_img)
                <div class="space-y-2">
                    <label class="block text-lg font-medium text-gray-700 dark:text-gray-300">Gambar Saat Ini:</label>
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $section->home_img) }}" alt="Current Background" class="w-full object-cover">
                    </div>
                </div>
            @endif

            {{-- Upload New Image --}}
            <div>
                <label for="background_image" class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Unggah Gambar Baru</label>
                <input
                    type="file"
                    name="home_img"
                    id="home_background"
                    class="block w-full text-sm
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-gray-300 file:text-gray-900
                        hover:file:bg-gray-400
                        dark:file:bg-gray-700 dark:file:text-gray-100 dark:hover:file:bg-gray-600
                        transition"
                />
                @error('background_image')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="text-gray-900 bg-gray-300 hover:bg-gray-400
                    dark:text-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600
                    text-lg font-medium px-6 py-2 rounded-full shadow transition
                    focus:outline-none focus:ring-2 focus:ring-offset-2
                    focus:ring-gray-400 dark:focus:ring-offset-gray-900
                    transform hover:scale-105 duration-150">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
