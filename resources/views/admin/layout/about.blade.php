@extends('layouts.admin')

@section('title', 'ADMIN LAYOUTS-ABOUT')

<style>

#about_text {
  overflow-x: hidden;
  overflow-y: auto;
  box-sizing: border-box;
  max-height: 580px;
  min-height: 100px;
  resize: none;
}

/* WebKit-based browsers */
#about_text::-webkit-scrollbar {
    width: 6px;
}

#about_text::-webkit-scrollbar-track {
    background: transparent;
}

#about_text::-webkit-scrollbar-thumb {
    background-color: rgba(122, 122, 122, 0.562);
    border-radius: 4px;
}

#about_text {
    scrollbar-width: thin;
    scrollbar-color: rgba(100, 100, 100, 0.4) transparent;
}
</style>

@section('content')
<section class="min-h-[calc(100vh-6rem)] overflow-y-hidden flex flex-col md:flex-row text-black dark:text-white px-6 md:px-20 py-4 gap-6 dark:bg-gray-900">
    
    {{-- Container Preview (80%) --}}
    <div class="w-full md:w-4/5 flex flex-col md:flex-row gap-5 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mt-3 items-start">

        <div class="flex-shrink-0 md:w-3/5 aspect-square rounded-xl overflow-hidden shadow-md ml-10 mt-12">
            @if($section && $section->about_img)
                <img src="{{ asset('storage/' . $section->about_img) }}" alt="About Image"
                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
                    No image uploaded
                </div>
            @endif
        </div>

        {{-- About Text --}}
        <div class="flex flex-col justify-center w-full md:w-2/5 p-5 mt-6">
            <h2 class="text-3xl font-bold tracking-wide">ABOUT US</h2>
            <p class="mt-4 text-base md:text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                {{ $section->about_text ?? 'Deskripsi tentang kami belum tersedia. Silakan tambahkan melalui form di sebelah.' }}
            </p>
        </div>
    </div>

    {{-- Form Upload (20%) --}}
    <div class="w-full md:w-1/5 h-[800px] bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 space-y-6 mt-3">

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.layout.about.upload') }}" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateUpload()">
            @csrf

            {{-- Upload Gambar --}}
            <div>
                <label for="about_img" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unggah Gambar</label>
                <input type="file" name="about_img" id="about_img" class="block w-full text-sm
                    file:mr-2 file:py-1 file:px-3
                    file:rounded file:border-0
                    file:text-sm file:font-medium
                    file:bg-gray-300 file:text-gray-900
                    hover:file:bg-gray-400
                    dark:file:bg-gray-700 dark:file:text-gray-100 dark:hover:file:bg-gray-600
                    transition">
                @error('about_img')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- About Text --}}
            <div>
                <label for="about_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea
                    id="about_text"
                    name="about_text"
                    class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm
                           focus:border-blue-500 focus:ring focus:ring-blue-200
                           dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out"
                    oninput="autoResize(this)"
                    >{{ old('about_text', $section->about_text ?? '') }}</textarea>
                @error('about_text')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="w-full text-sm font-medium text-white bg-blue-600 hover:bg-blue-700
                px-4 py-2 rounded-full shadow transition
                focus:outline-none focus:ring-2 focus:ring-offset-2
                focus:ring-gray-400 dark:focus:ring-offset-gray-900
                transform hover:scale-105 duration-150">
                Simpan
            </button>
        </form>
    </div>
</section>

<script>
    function autoResize(textarea) {
        textarea.style.height = 'auto'; // reset height
        const maxHeight = 580;
        const newHeight = Math.min(textarea.scrollHeight, maxHeight);
        textarea.style.height = newHeight + 'px';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('about_text');
        if (textarea) {
            autoResize(textarea);
            textarea.addEventListener('input', () => autoResize(textarea));
        }
    });

    function validateUpload() {
        const fileInput = document.getElementById('about_img');
        const file = fileInput.files[0];

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